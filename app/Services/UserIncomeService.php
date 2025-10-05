<?php

namespace App\Services;


use App\Models\UserModel;
use App\Models\UserRewardsModel;
use App\Models\WalletModel;
use App\Models\UserIncomeModel;
use App\Models\RoiModel;
use App\Models\TopupModel;
use App\Models\WalletTransactionModel;
use App\Models\WithdrawalModel;
use App\Twebsol\Plans;
use App\Enums\WalletTransactionCategory as TxnCat;
use App\Twebsol\Settings;

class UserIncomeService
{
    const SPONSOR_ROI_INCOME_WALLET = 'income';
    private UserModel $userModel;
    private UserIncomeModel $userIncomeModel;
    private TopupModel $topupModel;
    private WalletTransactionModel $walletTransactionModel;
    private WalletModel $walletModel;
    private RoiModel $roiModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
        $this->walletModel = new WalletModel;
        $this->roiModel = new RoiModel;
        $this->topupModel = new TopupModel;
        $this->userIncomeModel = new UserIncomeModel;
        $this->walletTransactionModel = new WalletTransactionModel;
    }

    private function getSponsor(?int $user_id_pk = null, string|array $columns = '*')
    {
        if (is_int($user_id_pk) and $user = user_model()->getUserFromUserIdPk($user_id_pk, $columns))
            return $user;
        return null;
    }



    /*
     *------------------------------------------------------------------------------------
     * Sponosor Income / Level Income (Traverse by sponsor_id) // will return distributed total amount
     *------------------------------------------------------------------------------------
     */
    public function distributeSponsorLevelIncome(object $user, string|float $investAmount, int $topup_id_pk): float
    {

        $incomeArraySize = count(Plans::SPONSOR_LEVEL_INCOMES);

        // getting the first sponsor
        $sponsor = $this->getSponsor($user->sponsor_id);

        $distributedAmount = 0;

        foreach (range(1, $incomeArraySize) as $index => $currentLevel) {

            if (!$sponsor)
                break; // no need to iterate more, because sponsor is now not exists

            $eligible = true;

            // if (!$this->userModel->isUserEligibleForIncome($sponsor->id))
            //    $eligible = false;

            if (!$sponsor->status)
                $eligible = false;

            $openLevels = model(UserModel::class)->getUserOpenLevel($sponsor->id);

            if ($openLevels < $currentLevel) {
                $eligible = false;
            }

            if ($eligible) {

                $amount = 0;

                $income = Plans::SPONSOR_LEVEL_INCOMES[$index];

                if ($income and ($income > 0)) {

                    $amount = a_percent_of_b($income, $investAmount);

                    if ($amount > 0) {
                        // making transaction description
                        $transactionIdPk = $this->walletModel->deposit(
                            user_id_pk: $sponsor->id,
                            amount: $amount,
                            wallet_field: 'income',
                            category: TxnCat::SPONSOR_LEVEL_INCOME,
                            isEarning: true
                        );


                        // saving sponsor level income record
                        $sli_id_pk = $this->userIncomeModel->saveSponsorLevelIncomeRecord(
                            user_id_pk: $sponsor->id,
                            amount: $amount,
                            level: $currentLevel,
                            level_user_id_pk: $user->id,
                            topup_id_pk: $topup_id_pk,
                            transaction_id_pk: $transactionIdPk,
                            percent: $income,
                            bv: $investAmount
                        );


                        addIncomeStat($sponsor->id, $amount, 'level_income');

                        // updating transaction with details
                        $this->walletTransactionModel->updateTransactionDetails($transactionIdPk, [
                            'sli_id_pk' => $sli_id_pk // Sponsor Level Income Id Pk
                        ]);
                    }

                }

                $distributedAmount += $amount;
            }

            $sponsor = $this->getSponsor($sponsor->sponsor_id);
        }

        return $distributedAmount;
    }

    /*
     !------------------------------------------------------------------------------------
     !------------------------------------------------------------------------------------
     !             ROI Income
     !------------------------------------------------------------------------------------
     !------------------------------------------------------------------------------------
     */


    private function distributeSponsorRoiLevelIncome(object $user, float|string $baseRoiIncome)
    {
        // $user must have exist $user->id, $user->sponsor_id

        $roiLevelIncomes = Plans::DEFAULT_ROI_LEVEL_INCOME;

        // if level income array is empty, then simply return
        if (empty($roiLevelIncomes))
            return 0;

        // getting the first sponsor
        $sponsor = $this->getSponsor($user->sponsor_id);



        foreach ($roiLevelIncomes as $index => $income) {
            //$index is not coming from plans array, its from 0 based array indexing

            $currentLevel = $index + 1; // because, index is on 0 based thing, ...you know

            if (!$sponsor)
                break; // no need to iterate more, because sponsor is now not exists


            if (
                $sponsor->status
                and $income
                and ($income > 0)
            ) {

                $eligible = true;

                $openLevels = model(UserModel::class)->getUserOpenLevel($sponsor->id);

                if ($openLevels < $currentLevel) {
                    $eligible = false;
                }

                if ($eligible) {

                    $amount = a_percent_of_b($income, $baseRoiIncome);

                    if ($amount > 0) {

                        $balance = $this->walletModel->getWalletBalanceFromUserIdPk($sponsor->id, 'investment');

                        [$isCapped, $amount] = $this->walletModel->roiCapping($sponsor->id, $amount, $balance);

                        if ($amount <= 0)
                            continue;

                        // making transaction description
                        $transactionIdPk = $this->walletModel->deposit(
                            user_id_pk: $sponsor->id,
                            amount: $amount,
                            wallet_field: 'income',
                            category: TxnCat::SPONSOR_ROI_LEVEL_INCOME,
                            isEarning: true
                        );


                        // saving sponsor roi level income record
                        $srli_id_pk = $this->userIncomeModel->saveSponsorRoiLevelIncomeRecord(
                            user_id_pk: $sponsor->id,
                            amount: $amount,
                            level: $currentLevel,
                            level_user_id_pk: $user->id,
                            transaction_id_pk: $transactionIdPk,
                            percent: $income,
                            roi_bv: $baseRoiIncome
                        );

                        addIncomeStat($sponsor->id, $amount, 'roi_level_income');


                        // updating transaction with details
                        $this->walletTransactionModel->updateTransactionDetails($transactionIdPk, [
                            'srli_id_pk' => $srli_id_pk // Sponsor Roi Level Income Id Pk
                        ]);

                    }
                }

            }

            $sponsor = $this->getSponsor($sponsor->sponsor_id);

        }

    }




    /*
     *------------------------------------------------------------------------------------
     * Distribute Investment Wallet ROI
     *------------------------------------------------------------------------------------
     */
    public function distributeInvestmentROI()
    {
        // select users.*, investment from users left join wallets on users.id = wallets.user_id;

        $oneDayAgo = date('Y-m-d H:i:s', time() - 86400);

        $users = $this->userModel->select([
            'users.id',
            'users.user_id',
            'users.full_name',
            'users.sponsor_id',
            'users.status',
            'users.activated_at',
            'COALESCE(wallets.investment, 0) as investment',
        ])
            ->where('users.status', 1)
            ->where('users.activated_at IS NOT NULL')
            ->where('users.activated_at <=', $oneDayAgo)
            ->join('wallets', 'users.id = wallets.user_id', 'LEFT')
            ->get()
            ->getResult();

        $this->userModel->db->transBegin();

        try {


            foreach ($users as $user) {

                $balance = $user->investment;
                $txnCategory = TxnCat::ROI;

                if ($balance > 0) {

                    $percent = Plans::getDailyRoiPercentByUser($user, $balance);
                    $amount = a_percent_of_b($percent, $balance);


                    [$isCapped, $amount] = $this->walletModel->roiCapping($user->id, $amount, $balance);

                    if ($amount <= 0)
                        continue;

                    $txnDetails = ['percent' => $percent, 'bv' => $balance];

                    // Depositing ROI Amount
                    $this->walletModel->deposit(
                        user_id_pk: $user->id,
                        amount: $amount,
                        wallet_field: 'income',
                        category: $txnCategory,
                        isEarning: true,
                        details: $txnDetails
                    );

                    addIncomeStat($user->id, $amount, 'roi');

                    $this->distributeSponsorRoiLevelIncome($user, $amount);
                }
            }

            $this->userModel->db->transCommit();

        } catch (\Exception $e) {
            $this->userModel->db->transRollback();

            throw $e;
        }

    }

    public function giveCompoundRoi()
    {
        // select users.*, investment from users left join wallets on users.id = wallets.user_id;

        $oneDayAgo = date('Y-m-d H:i:s', time() - 86400);

        $users = $this->userModel->select([
            'users.id',
            'users.user_id',
            'users.full_name',
            'users.sponsor_id',
            'users.status',
            'users.activated_at',
            'wallets.income',
        ])
            ->where('users.status', 1)
            ->where('users.activated_at IS NOT NULL')
            // ->where('users.activated_at <=', $oneDayAgo)
            ->where('wallets.income >=', 10)
            ->join('wallets', 'users.id = wallets.user_id', 'LEFT')
            ->get()
            ->getResult();

        $this->userModel->db->transBegin();

        try {


            foreach ($users as $user) {

                $balance = $user->income;
                $txnCategory = TxnCat::COMPOUND_ROI;

                if ($balance > 0) {

                    $percent = Plans::getDailyCompoundRoiPercentByUser($user, $balance);
                    $amount = a_percent_of_b($percent, $balance);

                    if ($amount <= 0)
                        continue;

                    $txnDetails = ['percent' => $percent, 'bv' => $balance];

                    // Depositing ROI Amount
                    $this->walletModel->deposit(
                        user_id_pk: $user->id,
                        amount: $amount,
                        wallet_field: 'income',
                        category: $txnCategory,
                        isEarning: true,
                        details: $txnDetails
                    );

                }
            }

            $this->userModel->db->transCommit();

        } catch (\Exception $e) {
            $this->userModel->db->transRollback();

            throw $e;
        }

    }


    public function withdrawal()
    {
        $wdModal = new WithdrawalModel();
        $wtModel = new WalletTransactionModel();
        $walletModel = new WalletModel;
        $users = $this->userModel->select(['id', 'is_usd'])->findAll();



        $wdModal->db->transBegin();
        try {
            foreach ($users as &$user) {

                //   $incomeAmount = $walletModel->getWalletBalanceFromUserIdPk($user->id, 'income');
                //   $roiAmount = $walletModel->getWalletBalanceFromUserIdPk($user->id, 'roi');

                $incomeAmount = $wtModel->selectSum('amount')
                    ->where('user_id', $user->id)
                    ->whereIn('category', [TxnCat::SPONSOR_LEVEL_INCOME])
                    ->where('type', 'credit')
                    ->where('created_at >=', '2025-05-31 23:59:59')
                    ->where('created_at <=', '2025-06-15 23:59:59')
                    ->first()->amount ?? 0;

                $roiAmount = 0;

                $amount = $incomeAmount + $roiAmount;

                $minimumWithdrawable = $user->is_usd ? 10 * USD_VALUE : 500;

                if ($amount or ($amount >= $minimumWithdrawable)) {

                    if ($incomeAmount > 0) {
                        $walletModel->deduct(
                            user_id_pk: $user->id,
                            amount: $incomeAmount,
                            wallet_field: 'income',
                            category: TxnCat::WITHDRAWAL
                        );

                    }

                    if ($roiAmount > 0) {
                        $walletModel->deduct(
                            user_id_pk: $user->id,
                            amount: $roiAmount,
                            wallet_field: 'roi',
                            category: TxnCat::WITHDRAWAL
                        );
                    }

                    $walletModel->deposit(
                        user_id_pk: $user->id,
                        amount: $amount,
                        wallet_field: 'withdrawal',
                        category: TxnCat::WITHDRAWAL
                    );

                }

                $wdAmount = $walletModel->getWalletBalanceFromUserIdPk($user->id, 'withdrawal');

                if ($wdAmount >= $minimumWithdrawable) {
                    $withdrawalInputs = [
                        'amount' => $wdAmount,
                        'remarks' => 'Withdrawal'
                    ];

                    $wdModal->makeWithdrawal(user_id_pk: $user->id, inputs: $withdrawalInputs);

                }
            }
            $wdModal->db->transCommit();
        } catch (\Exception $e) {
            $wdModal->db->transRollback();
            throw $e;
        }
    }
}
