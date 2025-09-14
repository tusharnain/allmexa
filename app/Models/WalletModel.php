<?php

namespace App\Models;

use App\Enums\UserIncomeStats;
use App\Enums\UserTypes;
use App\Enums\WalletTransactionCategory as TxnCat;
use App\Libraries\MyLib;
use App\Services\InputService;
use App\Services\ValidationRulesService;
use App\Services\WalletService;


class WalletModel extends ParentModel
{
    protected $table = 'wallets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'income', 'fund', 'investment', 'compound_investment', 'created_at', 'updated_at'];


    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';



    // Models
    private ?WalletTransactionModel $txnModel;
    private ?UserIncomeModel $userIncomeModel;
    private ?UserModel $userModel;


    public const MEMORY_WALLET_TRANSFER_DATA_KEY = '__wallet_transfer_data_mem';


    private function walletTransactionModel(): WalletTransactionModel
    {
        return $this->txnModel ??= new WalletTransactionModel;
    }
    private function userIncomeModel(): UserIncomeModel
    {
        return $this->userIncomeModel ??= new UserIncomeModel;
    }
    private function userModel(): UserModel
    {
        return $this->userModel ??= new UserModel;
    }




    private function getEmptyWallet(int $user_id_pk, bool $withUserId = false): object
    {
        $wallets = array();
        $walletFields = WalletService::WALLETS;
        foreach ($walletFields as &$wallet)
            $wallets[$wallet] = 0;

        if ($withUserId)
            $wallet['user_id'] = $user_id_pk;

        return MyLib::getObjectFromArray($wallets);
    }

    public function userWalletRecord(int $user_id_pk, array $columns = ['*']): object|null
    {
        return $this->select($columns, true)->where('user_id', $user_id_pk)->first();
    }
    public function getWalletsRecordFromUserIdPk(int $user_id_pk, array $columns = ['*']): object|null
    {
        $wallet = $this->select($columns)->where('user_id', $user_id_pk)->first();
        return $wallet ?? $this->getEmptyWallet($user_id_pk, withUserId: true);
    }

    public function getAllWalletsFromUserIdPk(int $user_id_pk): object|null
    {
        $walletsArray = WalletService::WALLETS;
        $wallet = $this->select($walletsArray)->where('user_id', $user_id_pk)->first();
        return $wallet ?? $this->getEmptyWallet($user_id_pk);
    }
    public function getWalletFromUserIdPk(int $user_id_pk, array $wallet_fields): object|null
    {
        $wallet = $this->select($wallet_fields)->where('user_id', $user_id_pk)->first();
        return $wallet ?? $this->getEmptyWallet($user_id_pk);
    }
    public function getWalletBalanceFromUserIdPk(int $user_id_pk, string $wallet_field): string|float
    {
        $wallet = $this->select($wallet_field)->where('user_id', $user_id_pk)->first();
        return $wallet ? $wallet->{$wallet_field} : 0.0;
    }

    public function getDayTotalEarningsByUserIdPk(int $user_id_pk, string $date): float|string
    {
        return $this->walletTransactionModel()->select('SUM(amount) as earning')
            ->where([
                'user_id' => $user_id_pk,
                'type' => 'credit',
                'DATE(created_at)' => $date
            ])->whereIn('wallet', ['income', 'roi'])
            ->get()->getRowObject()->earning ?? 0;
    }

    public function getUserTotalInvestment(int $user_id_pk): float|string
    {
        $wallet = $this->getWalletFromUserIdPk($user_id_pk, ['investment']);
        if (!$wallet) {
            return 0;
        }
        return $wallet->investment;
    }


    public function roiCapping(int $user_id_pk, float|string $income, float|string $balance): array
    {
        $cappingPercent = $this->userModel()->hasDirectActiveUser($user_id_pk) ? 300 : 200;

        if (is_null($cappingPercent))
            return [false, $income];

        $totalEarnings = $this->walletTransactionModel()->selectSum('amount')->where([
            'user_id' => $user_id_pk,
            'type' => 'credit',
        ])
            ->whereIn('category', [TxnCat::ROI, TxnCat::SPONSOR_ROI_LEVEL_INCOME])
            ->get()->getRow()->amount ?? 0;

        $maxLimit = a_percent_of_b(a: $cappingPercent, b: $balance);

        $earningPlusIncome = bcadd($totalEarnings, $income);

        if ($earningPlusIncome >= $maxLimit)
            return [true, bcsub($maxLimit, $totalEarnings)];

        return [false, $income];
    }

    // return [isCapped, income]
    public function userIncomeCappingFilter(int $user_id_pk, float|string $income): array
    {
        $totalInvestment = $this->getUserTotalInvestment($user_id_pk);


        $cappingPercent = UserTypes::getCappingPercentFromUserInvestment($totalInvestment);

        if (is_null($cappingPercent))
            return [false, $income];

        $userStat = $this->userIncomeModel()->getUserIncomeStatsFromUserIdPk(user_id_pk: $user_id_pk, columns: [UserIncomeStats::TOTAL_EARNING]);

        $userTotalEarning = $userStat->{UserIncomeStats::TOTAL_EARNING};

        $maxEarningLimit = a_percent_of_b(a: $cappingPercent, b: $totalInvestment);


        $totalEarningPlusIncome = bcadd($userTotalEarning, $income);

        if ($totalEarningPlusIncome >= $maxEarningLimit) {
            return [true, bcsub($maxEarningLimit, $userTotalEarning)]; // capped
        }

        return [false, $income];
    }

    //setters // its same function as available in WalletTransactionModel Class
    public function updateTransactionDetails(int $txn_pk_id, array $details): bool
    {
        return $this->walletTransactionModel()->updateTransactionDetails($txn_pk_id, $details);
    }


    public function deposit(int $user_id_pk, string|float $amount, string $wallet_field, string $category, bool $isEarning = false, array $details = null, ?string $userType = null): int
    {

        $wallet = $this->userWalletRecord($user_id_pk, ['id']);

        // if wallet exists, then update
        if (isset($wallet->id)) {
            $this->where('id', $wallet->id)
                ->set($wallet_field, "$wallet_field + $amount", false)
                ->update();
        } else {
            $this->insert([
                'user_id' => $user_id_pk,
                $wallet_field => $amount
            ]);
        }

        $txn_pk_id = $this->walletTransactionModel()->saveTransaction(
            user_id_pk: $user_id_pk,
            amount: $amount,
            type: 'credit',
            wallet: $wallet_field,
            details: $details,
            category: $category
        );

        // adding to totalEarning, if marekd, only for income wallet
        if ($isEarning) {
            $this->userIncomeModel()->updateUserIncomeStat(user_id_pk: $user_id_pk, stat: UserIncomeStats::TOTAL_EARNING, increment: $amount);
        }


        return $txn_pk_id; //success // txn pk id
    }

    public function deduct(int $user_id_pk, string $amount, string $wallet_field, string $category, bool $isEarning = false, array $details = null): int
    {
        $wallet = $this->userWalletRecord($user_id_pk, ['id', $wallet_field]);

        // if wallet doesnt exists OR balance amount is low
        if (!isset($wallet->id) or !$wallet->{$wallet_field} or ($wallet->{$wallet_field} < $amount)) {
            return -1; // low balance
        }

        $this->where('id', $wallet->id)
            ->set($wallet_field, "$wallet_field - $amount", false)
            ->update();

        $txn_pk_id = $this->walletTransactionModel()->saveTransaction(
            user_id_pk: $user_id_pk,
            amount: $amount,
            type: 'debit',
            wallet: $wallet_field,
            details: $details,
            category: $category
        );


        // deducting from totalEarning, if marekd, only for income wallet
        if ($isEarning and ($wallet_field === 'income')) {
            $this->userIncomeModel()->updateUserIncomeStat(user_id_pk: $user_id_pk, stat: UserIncomeStats::TOTAL_EARNING, increment: -$amount);
        }

        return $txn_pk_id; //success
    }





    /*
     *------------------------------------------------------------------------------------
     * Making User P2P Transfer
     *------------------------------------------------------------------------------------
     */

    public function makeP2PTransfer(int $user_id_pk): array|int
    {
        $data = InputService::inputP2PTransferValues();

        $validationErrors = validate($data, ValidationRulesService::p2pTransferRules());

        $userIdLabel = label('user_id');

        if ($validationErrors) {

            $inputAttribs = ['user_id' => $userIdLabel];

            foreach ($validationErrors as &$error)
                $error = str_replace(array_keys($inputAttribs), $inputAttribs, $error);

            return ['validationErrors' => $validationErrors];
        }


        //validating user_id
        $receiver = get_user($data['user_id'], ['id', 'full_name'], is_user_id_pk: false);

        if (!$receiver)
            return ['error' => "$userIdLabel is invalid."];

        //checking if reciever is the sender itself
        if ($user_id_pk == $receiver->id)
            return ['error' => "You've entered your own $userIdLabel mistakenly."];

        $amount = $data['amount']; // amount to transfer
        $remarks = $data['remarks']; // if has any


        $this->db->transBegin();

        try {
            // Deducting from sender's account
            $sender_transaction_id_pk = $this->deduct(
                user_id_pk: $user_id_pk,
                amount: $amount,
                wallet_field: 'fund',
                category: TxnCat::P2P_TRANSFER
            );

            // -1 means low balance
            if ($sender_transaction_id_pk === -1) {
                return ['error' => "You do not have required balance to transfer."];
            }

            // Adding to receiver's account
            $receiver_transaction_id_pk = $this->deposit(
                user_id_pk: $receiver->id,
                amount: $amount,
                wallet_field: 'fund',
                category: TxnCat::P2P_TRANSFER
            );


            // Saving P2P Transfer Record
            $p2p_id_pk = $this->walletTransactionModel()->saveP2PTransfer(
                amount: $amount,
                sender_user_id_pk: $user_id_pk,
                receiver_user_id_pk: $receiver->id,
                wallet_field: 'fund',
                sender_transaction_id_pk: $sender_transaction_id_pk,
                receiver_transaction_id_pk: $receiver_transaction_id_pk,
                sender_remarks: $remarks
            );


            //now saving transaction details
            $details = ['p2p_id' => $p2p_id_pk];
            $this->updateTransactionDetails(
                txn_pk_id: $sender_transaction_id_pk,
                details: $details
            );
            $this->updateTransactionDetails(
                txn_pk_id: $receiver_transaction_id_pk,
                details: $details
            );

            memory('p2p_transfer_data', [
                'amount' => $amount,
                'receiver_full_name' => $receiver->full_name
            ]);

            $this->db->transCommit();
        } catch (\Exception $e) {

            $this->db->transRollback();

            throw $e;
        }

        return 1;
    }



    /*
     *------------------------------------------------------------------------------------
     * Wallet Transfer
     *------------------------------------------------------------------------------------
     */

    public function makeWalletTransfer(int $userIdPk): array|int
    {

        $data = InputService::inputWalletTransferValues();

        $validationErrors = validate($data, ValidationRulesService::userWalletTransferRules());


        if ($validationErrors) {
            $inputAttribs = InputService::inputWalletTransferValues_attribs();
            foreach ($validationErrors as &$error)
                $error = str_replace(array_keys($inputAttribs), $inputAttribs, $error);
            return ['validationErrors' => $validationErrors];
        }

        $fromWallet = $data['from'];
        $toWallet = $data['to'];
        $amount = $data['amount'];

        $this->db->transBegin();

        try {

            $deductPercent = 0;

            if ($toWallet === 'fund') {
                // $deductPercent = 5;
            }

            // deducting from wallet
            $deduct_txn_id_pk = $this->deduct(
                user_id_pk: $userIdPk,
                amount: $amount,
                wallet_field: $fromWallet,
                category: TxnCat::WALLET_TRANSFER
            );

            if ($deduct_txn_id_pk === -1) {
                return ['error' => "You do not have required balance to transfer."];
            }


            if ($deductPercent > 0) {
                $amount = $amount - ($amount * $deductPercent / 100);
            }


            // depositing to wallet
            $deposit_txn_id_pk = $this->deposit(
                user_id_pk: $userIdPk,
                amount: $amount,
                wallet_field: $toWallet,
                category: TxnCat::WALLET_TRANSFER,
                details: [
                    'from' => $fromWallet,
                    'from_txn_id' => $deduct_txn_id_pk
                ]
            );

            // updating $deduct transaction detali
            $this->updateTransactionDetails($deduct_txn_id_pk, [
                'to' => $toWallet,
                'to_txn_id' => $deposit_txn_id_pk,
                'deduction_percent' => $deductPercent
            ]);


            // setting output data
            memory(self::MEMORY_WALLET_TRANSFER_DATA_KEY, $data);


            $this->db->transCommit();

        } catch (\Exception $e) {

            $this->db->transRollback();

            throw $e;
        }

        return 1;
    }


    /*
     *------------------------------------------------------------------------------------
     * Admin Only
     *------------------------------------------------------------------------------------
     */
    public function admin_addDeduct()
    {
        $data = InputService::admin_inputAddDeductValues();

        $validationErrors = validate($data, ValidationRulesService::admin_addDeductRules());

        if ($validationErrors) {
            return ['validationErrors' => $validationErrors];
        }

        $userIdLabel = label('user_id'); // User Label

        // validating user
        $user = user_model()->getUserFromUserId($data['user_id'], ['id', 'full_name', 'status']);

        if (!$user or !isset($user->id)) {
            return ['error' => "$userIdLabel is invalid."];
        }

        $walletField = WalletService::getWalletFieldFromIndex($data['wallet']);

        $isEarning = (bool) $data['is_earning'];

        $details = array();

        $call_func = $data['type'] === 'credit' ? 'deposit' : 'deduct';

        if ($data['remarks'] and strlen($data['remarks']) > 0) {
            $details['remarks'] = $data['remarks'];
        }

        $this->db->transBegin();

        try {

            $transRes = $this->{$call_func}(
                user_id_pk: $user->id,
                amount: $data['amount'],
                wallet_field: $walletField,
                category: TxnCat::ADMIN,
                isEarning: $isEarning,
                details: $details
            );

            $this->db->transCommit();
        } catch (\Exception $e) {

            $this->db->transRollback();

            throw $e;
        }

        if (($data['type'] === 'debit') and isset($transRes) and ($transRes == -1))
            return ['error' => 'Can\'t deduct, low balance in ' . wallet_label($walletField) . '.'];

        $data['user_id_pk'] = $user->id;
        $data['full_name'] = $user->full_name;
        memory('admin_add_deduct_data', $data);

        return 1;
    }
}
