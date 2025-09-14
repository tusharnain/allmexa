<?php

namespace App\Models;

use App\Enums\UserIncomeStats;
use App\Services\InputService;
use App\Services\WalletService;
use CodeIgniter\Database\BaseBuilder;
use App\Services\ValidationRulesService;
use App\Enums\WalletTransactionCategory as TxnCat;

class WithdrawalModel extends ParentModel
{
    protected $table = 'withdrawals';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = ['track_id', 'user_id', 'amount', 'charges', 'net_amount', 'remarks', 'status', 'utr', 'admin_remarks', 'admin_resolution_at'];

    // constants
    const TRACK_ID_INIT_NUMBER = 1000000;
    const TRACK_ID_PREFIX_WORD = 'WD';
    // constants

    //  Enums
    const WD_STATUS_PENDING = 'pending';
    const WD_STATUS_CANCELLED = 'cancelled';
    const WD_STATUS_REJECT = 'reject';
    const WD_STATUS_COMPLETE = 'complete';

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';


    //other tables
    private BaseBuilder $bankAccountTable, $walletAddressTable;


    public function bankAccountTable(): BaseBuilder
    {
        return $this->bankAccountTable ??= $this->db->table('bank_accounts');
    }
    public function walletAddressTable(): BaseBuilder
    {
        return $this->walletAddressTable ??= $this->db->table('wallet_address');
    }

    // other models
    private ?WalletModel $walletModel = null;
    private ?UserIncomeModel $userIncomeModel = null;
    public function walletModel(): WalletModel
    {
        return $this->walletModel ??= new WalletModel;
    }
    public function userIncomeModel(): UserIncomeModel
    {
        return $this->userIncomeModel ??= new UserIncomeModel;
    }

    public static function isValidWithdrawalStatus(string $status): bool
    {
        return in_array($status, [
            self::WD_STATUS_COMPLETE,
            self::WD_STATUS_PENDING,
            self::WD_STATUS_REJECT,
            self::WD_STATUS_CANCELLED
        ]);
    }



    public function getWithdrawalFromWithdrawalIdPkAndUserIdPk(int $withdrawal_id_pk, int $user_id_pk, string|array $columns = '*'): object|null
    {
        return $this->select($columns)->where(['id' => $withdrawal_id_pk, 'user_id' => $user_id_pk])->get()->getRowObject();
    }

    public function getWithdrawalFromWithdrawalIdPk(int $wd_id_pk, string|array $columns = '*'): object|null
    {
        return $this->select($columns)->find(id: $wd_id_pk);
    }
    public function getWithdrawalFromTrackId(string $trackId, string|array $columns = '*'): object|null
    {
        return $this->select($columns)->where('track_id', $trackId)->first();
    }


    public function getTotalWithdrawalsFromUserIdPk(int $user_id_pk, ?string $status = null): int|string
    {
        $where['user_id'] = $user_id_pk;

        if (!is_null($status))
            $where['status'] = $status;

        return $this->where($where)->countAllResults();
    }


    public function refundWithdrawalAmountOnCancel(int $withdrawal_id_pk)
    {
        $withdrawal = $this->select(['user_id', 'amount'])->find($withdrawal_id_pk);

        if ($withdrawal) {
            $this->walletModel()->deposit(
                user_id_pk: $withdrawal->user_id,
                amount: $withdrawal->amount,
                wallet_field: WalletService::WITHDRAW_FROM_WALLET,
                category: TxnCat::WITHDRAWAL_REFUND,
                details: [
                    'wd_id' => $withdrawal_id_pk
                ]
            );
        }
    }




    private function generateTrackId(int $id_pk): string
    {
        $trackId = self::TRACK_ID_INIT_NUMBER + $id_pk;
        $trackId = self::TRACK_ID_PREFIX_WORD . $trackId;
        return $trackId;
    }


    /*
     *------------------------------------------------------------------------------------
     * Make Withdrawal
     *------------------------------------------------------------------------------------
     */
    public function makeWithdrawal(int $user_id_pk, ?array $inputs = null): array|int
    {
        $data = $inputs ?? InputService::inputWithdrawalValues();

        if (!$inputs && ($validationErrors = validate($data, ValidationRulesService::userWithdrawalRules())))
            return ['validationErrors' => $validationErrors];

        $amount = $data['amount'];
        $remarks = $data['remarks'] ?? null;

        $percentCharges = _setting('withdrawal_percent_charges');
        $fixedCharges = _setting('withdrawal_fixed_charges');

        $netAmount = $amount;
        $totalCharges = 0;

        if ($percentCharges and ($percentCharges > 0) and ($percentCharges <= 100)) {
            $charge = $amount * ($percentCharges / 100);
            $netAmount -= $charge;
            $totalCharges += $charge;
        }
        if ($fixedCharges and ($fixedCharges > 0) and ($fixedCharges < $netAmount)) {
            $netAmount -= $fixedCharges;
            $totalCharges += $fixedCharges;
        }

        $this->db->transBegin();

        try {

            // making withdrawal
            $wd_id_pk = $this->insert([
                'user_id' => $user_id_pk,
                'amount' => $amount,
                'charges' => $totalCharges,
                'net_amount' => $netAmount,
                'remarks' => $remarks,
                'status' => WithdrawalModel::WD_STATUS_PENDING
            ], returnID: true);

            $this->update($wd_id_pk, ['track_id' => $this->generateTrackId($wd_id_pk)]);

            // deducting amount from wallet
            $txnDetails = ['wd_id' => $wd_id_pk];
            $this->walletModel()->deduct(
                user_id_pk: $user_id_pk,
                amount: $amount,
                wallet_field: WalletService::WITHDRAW_FROM_WALLET,
                category: TxnCat::WITHDRAWAL,
                details: $txnDetails
            );

            // update stat
            $this->userIncomeModel()->updateUserIncomeStat(user_id_pk: $user_id_pk, stat: UserIncomeStats::TOTAL_PENDING_WITHDRAWAL, increment: $amount);

            $this->db->transCommit();

        } catch (\Exception $e) {

            $this->db->transRollback();

            throw $e;

        }


        return 1;
    }



    /*
     *------------------------------------------------------------------------------------
     * Update Withdrawal Status (Admin)
     *------------------------------------------------------------------------------------
     */
    public function updateWithdrawalStatus(int $withdrawalId): int|array
    {
        $data = InputService::admin_inputUpdateWithdrawalStatusValues();

        $validationErrors = validate($data, ValidationRulesService::admin_inputUpdateWithdrawalStatusRules());

        if ($validationErrors)
            return ['validationErrors' => $validationErrors];

        $status = $data['status'];
        $utr = $data['utr'];
        $remarks = $data['remarks'];

        $currDateTime = $this->dbDate();

        $this->db->transBegin();

        try {

            $this->update($withdrawalId, [
                'status' => $status,
                'utr' => $utr,
                'admin_remarks' => $remarks,
                'admin_resolution_at' => $currDateTime
            ]);

            if ($status === self::WD_STATUS_CANCELLED) {
                $this->refundWithdrawalAmountOnCancel(withdrawal_id_pk: $withdrawalId);
            }

            // getting withdrawa
            $withdrawal = $this->select(['user_id', 'amount'])->find(id: $withdrawalId);


            // decrement pending withdrawal stat
            $this->userIncomeModel()->updateUserIncomeStat(user_id_pk: $withdrawal->user_id, stat: UserIncomeStats::TOTAL_PENDING_WITHDRAWAL, increment: -$withdrawal->amount);

            // increament complete withdrawal stack
            if ($status === self::WD_STATUS_COMPLETE) {
                $this->userIncomeModel()->updateUserIncomeStat(user_id_pk: $withdrawal->user_id, stat: UserIncomeStats::TOTAL_COMPLETE_WITHDRAWAL, increment: $withdrawal->amount);
            }


            $info = [
                'status' => $status,
                'admin_resolution_at' => $currDateTime,
                'f_admin_resolution_at' => f_date($currDateTime),
                'remarks_given' => (bool) ($remarks and is_string($remarks) and (strlen($remarks) > 0))
            ];

            memory('admin_withdrawal_status_update_info', $info);

            $this->db->transCommit();

        } catch (\Exception $e) {

            $this->db->transRollback();

            throw $e;

        }

        return 1;
    }





    /*
     *------------------------------------------------------------------------------------
     * Bank Account
     *------------------------------------------------------------------------------------
     */
    public function getUserBankDetailsFromUserIdPk(int $user_id_pk, string|array $columns = '*'): object|null
    {
        return $this->bankAccountTable()->select($columns)->where('user_id', $user_id_pk)->get()->getRowObject();
    }
    public function getUserWalletAddressDetailsFromUserIdPk(int $user_id_pk, string|array $columns = '*')
    {
        return $this->walletAddressTable()->select($columns)->where('user_id', $user_id_pk)->get()->getRowObject();
    }
    public function saveBankAccountDetails(int $user_id_pk, \stdClass $bank, bool $lock = false): int|array
    {
        $data = InputService::inputBankDetailsValues();

        $validationErrors = validate($data, ValidationRulesService::userBankDetailsRules());

        if ($validationErrors) {
            $inputAttribs = InputService::inputBankDetailsValues_attribs();
            foreach ($validationErrors as &$error)
                $error = str_replace(array_keys($inputAttribs), $inputAttribs, $error);
            return ['validationErrors' => $validationErrors];
        }


        $bankDetails = $this->bankAccountTable()->select(['id', 'locked'])->where('user_id', $user_id_pk)->get()->getRowObject();


        $pushData = ['bank_ifsc' => $bank->code, 'bank_name' => $bank->bank, 'bank_branch' => $bank->branch, 'locked' => $lock, ...$data];

        if ($bankDetails) {
            if ($bankDetails->locked)
                return ['error' => "Bank Details Update is not allowed right now!"];

            $this->bankAccountTable()->where('id', $bankDetails->id)->update([...$pushData, ...$this->getTimestamps(3)]);

        } else {

            $this->bankAccountTable()->insert(['user_id' => $user_id_pk, ...$pushData, ...$this->getTimestamps()]);
        }

        memory('bank_updated_at_timestamp', time());

        return 1;
    }


    /*
     *------------------------------------------------------------------------------------
     * Wallet Details
     *------------------------------------------------------------------------------------
     */
    public function getUserWalletDetailsFromUserIdPk(int $user_id_pk, string|array $columns = '*'): object|null
    {
        return $this->walletAddressTable()->select($columns)->where('user_id', $user_id_pk)->get()->getRowObject();
    }
    public function saveWalletDetails(int $user_id_pk, bool $lock = false): array|int
    {

        $data = InputService::inputWalletDetailsValues();

        $validationErrors = validate($data, ValidationRulesService::userWalletDetailsRules());

        if ($validationErrors) {
            $inputAttribs = InputService::inputWalletDetailsValues_attribs();
            foreach ($validationErrors as &$error)
                $error = str_replace(array_keys($inputAttribs), $inputAttribs, $error);
            return ['validationErrors' => $validationErrors];
        }

        $walletDetails = $this->walletAddressTable()->select(['id', 'locked'])->where('user_id', $user_id_pk)->get()->getRowObject();


        $pushData = [...$data, 'locked' => $lock];

        if ($walletDetails) {
            if ($walletDetails->locked)
                return ['error' => "Wallet Address Update is not allowed right now!"];

            $this->walletAddressTable()->where('id', $walletDetails->id)->update([...$pushData, ...$this->getTimestamps(3)]);

        } else {
            $this->walletAddressTable()->insert(['user_id' => $user_id_pk, ...$pushData, ...$this->getTimestamps()]);
        }

        memory('wallet_updated_at_timestamp', time());

        return 1;
    }
}