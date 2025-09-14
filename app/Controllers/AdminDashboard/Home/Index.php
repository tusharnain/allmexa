<?php

namespace App\Controllers\AdminDashboard\Home;

use App\Controllers\ParentController;
use App\Enums\UserIncomeStats;
use App\Models\TopupModel;
use App\Models\UserIncomeModel;
use App\Models\UserModel;
use App\Models\UserRewardsModel;
use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use App\Models\WithdrawalModel;

class Index extends ParentController
{
    private array $vd = [];
    private UserModel $userModel;
    private TopupModel $topupModel;
    private WalletModel $walletModel;
    private WithdrawalModel $withdrawalModel;
    private UserIncomeModel $userIncomeModel;
    private WalletTransactionModel $walletTransactionModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
        $this->topupModel = new TopupModel;
        $this->walletModel = new WalletModel;
        $this->withdrawalModel = new WithdrawalModel;
        $this->userIncomeModel = new UserIncomeModel;
        $this->walletTransactionModel = new WalletTransactionModel;
        $this->userRewardsModel = new UserRewardsModel;
    }
    private function addWidgetData()
    {
        $today = date('Y-m-d');

        $totalIncomeWalletBalance = $this->walletModel->selectSum('income')->get()->getRow()->income ?? 0;


        $totalInvestmentCredit = $this->walletTransactionModel
            ->selectSum('amount')
            ->where('type', 'credit')
            ->where('wallet', 'investment')->first()?->amount ?? 0;

        $totalInvestmentDebit = $this->walletTransactionModel
            ->selectSum('amount')
            ->where('type', 'debit')
            ->where('wallet', 'investment')->first()?->amount ?? 0;


        $todayInvestmentCredit = $this->walletTransactionModel
            ->selectSum('amount')
            ->where('type', 'credit')
            ->where('DATE(created_at)', $today)
            ->where('wallet', 'investment')->first()?->amount ?? 0;

        $todayInvestmentDebit = $this->walletTransactionModel
            ->selectSum('amount')
            ->where('type', 'debit')
            ->where('DATE(created_at)', $today)
            ->where('wallet', 'investment')->first()?->amount ?? 0;


        $this->vd['totalUsers'] = $this->userModel->countAllResults() ?? 0;

        $this->vd['totalActiveUsers'] = $this->userModel->where('status', true)->countAllResults() ?? 0;

        $this->vd['todaysRegistrations'] = $this->userModel->where('DATE(created_at)', $today)->countAllResults() ?? 0;

        // $this->vd['todaysInvestment'] = $this->topupModel->selectSum('amount')->where('DATE(created_at)', $today)->get()->getRow()->amount ?? 0;

        // $this->vd['totalInvestment'] = $this->topupModel->selectSum('amount')->get()->getRow()->amount ?? 0;



        $this->vd['totalInvestment'] = $totalInvestmentCredit - $totalInvestmentDebit;
        $this->vd['todaysInvestment'] = $todayInvestmentCredit - $todayInvestmentDebit;




        $this->vd['fundInMarket'] = $this->walletModel->selectSum('fund')->get()->getRow()->fund ?? 0;

        $this->vd['incomeInWallets'] = $totalIncomeWalletBalance;

        $this->vd['pendingWithdrawal'] = $this->userIncomeModel
            ->getUserIncomeStatsTable()
            ->selectSum(UserIncomeStats::TOTAL_PENDING_WITHDRAWAL)
            ->get()->getRow()->{UserIncomeStats::TOTAL_PENDING_WITHDRAWAL} ?? 0;

        $this->vd['withdrawalNetAmount'] = $this->withdrawalModel->selectSum('net_amount')->where('status', WithdrawalModel::WD_STATUS_PENDING)->get()->getRow()->net_amount ?? 0;

        $this->vd['completeWithdrawal'] = $this->userIncomeModel
            ->getUserIncomeStatsTable()
            ->selectSum(UserIncomeStats::TOTAL_COMPLETE_WITHDRAWAL)
            ->get()->getRow()->{UserIncomeStats::TOTAL_COMPLETE_WITHDRAWAL} ?? 0;

        $this->vd['recentRegisteredUsers'] = $this->userModel->select(['user_id', 'full_name', 'profile_picture'])->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->limit(6)->get()->getResult();

        $this->vd['reward_users'] = (function () {
            $userRewards = $this->userRewardsModel
                ->select('user_rewards.*, users.full_name as user_full_name, users.user_id as user_user_id')
                ->join('users', 'users.id = user_rewards.user_id', 'left')
                ->orderBy('user_rewards.created_at', 'DESC') // Order by created_at in descending order
                ->limit(5)
                ->get()
                ->getResult();

            foreach ($userRewards as $reward) {
                $date = new \DateTime($reward->created_at);
                $reward->formatted_created_at = $date->format('jS M Y, h:i A');
            }

            return $userRewards;
        })();

    }
    public function index(): string
    {
        $this->vd = $this->pageData('Admin Dashboard', 'Dashboard');

        $this->addWidgetData();

        return view('admin_dashboard/home/index', $this->vd);
    }
}
