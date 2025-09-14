<?php

namespace App\Models;

use App\Actions\Jobs\BoosterClubIncome;
use App\Enums\RoiTypes;
use App\Enums\TopupTypes;
use App\Enums\UserIncomeStats;
use App\Enums\UserTypes;
use App\Enums\WalletTransactionCategory as TxnCat;
use App\Services\InputService;
use App\Services\UserIncomeService;
use App\Services\ValidationRulesService;
use App\Twebsol\Plans;



class TopupModel extends ParentModel
{
    protected $table = 'topups';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = ['track_id', 'user_id', 'amount', 'topup_by', 'type', 'is_active', 'created_at'];

    // constants
    const TRACK_ID_INIT_NUMBER = 1000000;
    const TRACK_ID_PREFIX_WORD = 'TP';
    // constants

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';


    private ?RoiModel $roiModel;
    private ?UserIncomeModel $userIncomeModel;
    private ?UserModel $userModel;



    private function roiModel(): RoiModel
    {
        return $this->roiModel ??= new RoiModel;
    }

    private function userIncomeModel(): UserIncomeModel
    {
        return $this->userIncomeModel ??= new UserIncomeModel;
    }
    private function userModel(): UserModel
    {
        return $this->userModel ??= new UserModel;
    }



    public function getTopupFromTopupIdPk(int $topup_id_pk, string|array $columns = '*'): null|object
    {
        return $this->select($columns)->find($topup_id_pk);
    }
    public function getTopupFromTrackId(string $track_id, string|array $columns = '*'): null|object
    {
        return $this->select($columns)->where('track_id', $track_id)->get()->getRowObject();
    }






    public function topupUser(?int $topup_by_user_id_pk = null): array|int
    {
        // is topup made by some user
        $isTopupMadeByUser = !is_null($topup_by_user_id_pk);
        $isAdmin = !$isTopupMadeByUser;

        // Return array if validation error or object if success
        $data = $isTopupMadeByUser ? InputService::inputTopupValues() : InputService::inputAdminTopupValues();
        $validationRules = $isTopupMadeByUser ? ValidationRulesService::userTopupRules() : ValidationRulesService::admin_topupRules();

        $validationErrors = validate($data, $validationRules);
        if ($validationErrors) {
            $inputAttribs = InputService::inputTopupValues_attribs();
            foreach ($validationErrors as &$error)
                $error = str_replace(array_keys($inputAttribs), $inputAttribs, $error);
            return ['validationErrors' => $validationErrors];
        }

        //validating user_id
        $user = get_user($data['user_id'], ['id', 'sponsor_id', 'status'], is_user_id_pk: false);

        if (!$user)
            return ['error' => label('user_id') . ' is invalid!'];

        $amount = $data['amount'];
        $type = $data['type'] ?? 'investment';

        $depWallet = match ($type) {
            'compound' => 'compound_investment',
            default => 'investment'
        };

        $depCategory = match ($type) {
            'compound' => TxnCat::COMPOUND_INVESTMENT,
            default => TxnCat::INVESTMENT
        };

        $activateId = true;
        $distributeUplineIncome = $type == 'investment';


        $walletModel = new WalletModel;


        $this->db->transBegin();

        try {

            // if topup is made by some user
            if ($isTopupMadeByUser) {
                // deducting amount from user wallet // txn details  be updated later, when we get the topup track id
                $txn_pk_id = $walletModel->deduct(
                    user_id_pk: $topup_by_user_id_pk,
                    amount: $amount,
                    wallet_field: 'fund',
                    category: TxnCat::TOPUP
                );
                // if its -1, its low balance, otherwise its transaction id pk
                if ($txn_pk_id === -1)
                    return ['error' => "You do not have the required balance for topup with " . f_amount(_c($amount), isUser: true) . '.'];
            }

            // making topup record
            $topup_id_pk = $this->insert([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => $type,
                'topup_by' => $topup_by_user_id_pk ?? null, // null means topup done by admin
            ], returnID: true);
            // now updating with unique track_id

            // Updating Topup Track Id
            $trackId = self::TRACK_ID_INIT_NUMBER + $topup_id_pk;
            $trackId = self::TRACK_ID_PREFIX_WORD . $trackId;
            $this->update($topup_id_pk, ['track_id' => $trackId]);


            // incrementing total investment
            $this->userIncomeModel()->updateUserIncomeStat(
                user_id_pk: $user->id,
                stat: UserIncomeStats::TOTAL_INVESTMENT,
                increment: $amount
            );


            $walletModel->deposit(
                user_id_pk: $user->id,
                amount: $amount,
                wallet_field: $depWallet,
                category: $depCategory,
                details: ['topup_id_pk' => $topup_id_pk]
            );

            //activating user (if not activated) // this needs to be run first, because in later function, we maybe doing something on basis of its active/inactive status
            if ($activateId && !$user->status) {
                user_model(static: true)->activateUser($user->id);

                if ($user->sponsor_id) {
                    (new BoosterClubIncome)->handleOnDirectUserTopup($user->sponsor_id);
                }
            }

            // distributing sponsor level income (if has any)
            if ($distributeUplineIncome) {
                (new UserIncomeService)->distributeSponsorLevelIncome(
                    $user,
                    $amount,
                    $topup_id_pk
                );
            }

            // if topup is made by some user
            if ($isTopupMadeByUser and isset($txn_pk_id)) {
                // updating txn detail of amount deduction
                $txnDetails = ['topup_id' => $topup_id_pk];
                $walletModel->updateTransactionDetails($txn_pk_id, $txnDetails);
            }

            // committing database operations
            $this->db->transCommit();
        } catch (\Exception $e) {

            $this->db->transRollback();

            throw $e;
        }

        return 1;
    }

    public function getCtoByDate(string $date)
    {

        $start = $date . ' 00:00:00';
        $end = $date . ' 23:59:59';

        $data = $this->selectSum('amount')
            ->where('type', 'investment')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->get()
            ->getRowObject();

        return ($data && isset($data->amount)) ? $data->amount : 0.00;
    }

    public function getCtoByDateRange(string $start, string $end)
    {
        $data = $this->selectSum('amount')
            ->where('type', 'investment')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->get()
            ->getRowObject();

        return ($data && isset($data->amount)) ? $data->amount : 0.00;
    }
}
