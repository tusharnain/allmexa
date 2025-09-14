<?php

namespace App\Controllers\UserDashboard\Home;

use App\Controllers\ParentController;
use App\Enums\UserIncomeStats;
use App\Models\RoiModel;
use App\Models\UserIncomeModel;
use App\Models\UserModel;
use App\Models\UserRewardsModel;
use App\Models\WalletModel;
use App\Services\WalletService;
use App\Twebsol\Plans;
use CodeIgniter\HTTP\Response;
use App\Enums\WalletTransactionCategory as TxnCat;
use App\Models\WalletTransactionModel;

class Index extends ParentController
{
    private array $vd = [];
    private WalletModel $walletModel;
    private UserModel $userModel;
    private UserIncomeModel $userIncomeModel;
    private WalletTransactionModel $userTransactionModel;
    public function __construct()
    {
        $this->userModel = new UserModel;
        $this->walletModel = new WalletModel;
        $this->userIncomeModel = new UserIncomeModel();
        $this->userTransactionModel = new WalletTransactionModel();
    }


    /*
     *------------------------------------------------------------------------------------
     * Dashboard Widget Data Ajax
     *------------------------------------------------------------------------------------
     */
    private function getWidgetComponentData()
    {
        $component = inputPost('component');
        $userIdPk = user('id');
        $data = null;

        $totalTeamInvestment = $this->userModel->getTeamInvestment($userIdPk, upto_level: 999999999);


        switch ($component) {
            case 'direct_team': {
                $data = $this->userModel->getDirectReferralsCountFromUserIdPk(user_id_pk: $userIdPk);
                break;
            }
            case 'direct_active_team': {
                $data = $this->userModel->getDirectActiveReferralsCountFromUserIdPk(user_id_pk: $userIdPk);
                break;
            }
            case 'total_team_count': {
                $data = $this->userModel->getTotalTeamCount(user_id_pk: $userIdPk, upto_level: Plans::TEAM_UPTO_LEVEL);
                break;
            }
            case 'total_active_team_count': {
                $data = $this->userModel->getTotalActiveTeamCount(user_id_pk: $userIdPk, upto_level: Plans::TEAM_UPTO_LEVEL);
                break;
            }
            case 'direct_team_investment': {
                $data = f_amount(_c($this->userModel->getTeamInvestment($userIdPk, upto_level: 1)), shortForm: true, isUser: true);
                break;
            }
            case 'total_team_investment': {
                $data = f_amount(_c($totalTeamInvestment), shortForm: true, isUser: true);
                break;
            }
            default: {
                $data = ajax_404_response();
            }
        }
        return $data;
    }


    /*
     *------------------------------------------------------------------------------------
     * Recent Referrals
     *------------------------------------------------------------------------------------
     */
    private function recentReferrals(): Response
    {
        // Recent 5 Referrals
        $recentReferrals = $this->userModel->select(['user_id', 'full_name', 'email', 'status', 'profile_picture'])
            ->where('sponsor_id', user('id'))
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get()
            ->getResultObject();
        foreach ($recentReferrals as &$user) {
            $user->full_name = escape($user->full_name);
            $user->email = escape($user->email);
            $user->pfp = UserModel::getAvatar($user);
        }
        return resJson(['success' => true, 'data' => $recentReferrals]);
    }

    private function userProfile(): Response
    {
        $user = null;

        if (session()->has('__login_user_info')) {

            $user = session()->get('__login_user_info');
        } else {

            $user_id_pk = user('id');
            $user_created_at = user('created_at');
            $user_activated_at = user('activated_at');

            $user = $this->userModel->getUserFromUserIdPk($user_id_pk, ['user_id', 'full_name', 'status', 'phone', 'email', 'profile_picture']);

            $userAgent = $this->request->getUserAgent();

            $ip = $this->request->getIPAddress();
            $browser = $userAgent->getBrowser();
            $platform = $userAgent->getPlatform();
            $logins = $this->userModel->getUserLastSuccessLoginFromUserIdPk($user_id_pk, lastHowMany: 2, columns: ['created_at']) ?? null;
            $currentLogin = $logins[0]->created_at ?? null;
            $secondLastLoginTime = $logins[1]->created_at ?? null;

            $user->full_name = escape($user->full_name);
            $user->email = escape($user->email);
            $user->pfp = UserModel::getAvatar($user);
            $user->ip = $ip === '::1' ? '127.0.0.1' : $ip;
            $user->browser = $browser;
            $user->platform = $platform;
            $user->currentLogin = $currentLogin ? f_date($currentLogin, 'd M Y h:i A') : 'N/A';
            $user->lastLogin = $secondLastLoginTime ? f_date($secondLastLoginTime, 'd M Y h:i A') : 'N/A';
            $user->joiningDate = $user_created_at ? f_date($user_created_at) : 'N/A';
            $user->activationDate = $user_activated_at ? f_date($user_activated_at) : 'N/A';

            session()->set('__login_user_info', $user);
        }

        return resJson(['success' => true, 'user' => $user]);
    }

    private function earningChart(): Response
    {
        load_helper_if_not_function('date', 'chart_get_last_n_days');
        $datesArray = chart_get_last_n_days(N: 7);
        $dailyEarnigsArray = [];
        $dailyFormattedEarningsArray = [];

        foreach ($datesArray as &$date) {
            $dayEarning = $this->walletModel->getDayTotalEarningsByUserIdPk(user_id_pk: user('id'), date: $date);
            array_push($dailyEarnigsArray, $dayEarning);
            array_push($dailyFormattedEarningsArray, f_amount(_c($dayEarning), isUser: true));
        }
        return resJson([
            'success' => true,
            'days_array' => $datesArray,
            'earnings_array' => $dailyEarnigsArray,
            'earnings_f_array' => $dailyFormattedEarningsArray
        ]);
    }

    public function incomeProgress(): Response
    {

        $userIdPk = user('id');

        $totalInvestmentCredit = $this->userTransactionModel->selectSum('amount')
            ->where('user_id', $userIdPk)
            ->where('type', 'credit')
            ->whereIn('wallet', ['investment'])
            ->get()->getRow()->amount ?? 0;
        $totalInvestmentDebit = $this->userTransactionModel->selectSum('amount')
            ->where('user_id', $userIdPk)
            ->where('type', 'debit')
            ->whereIn('wallet', ['investment'])
            ->get()->getRow()->amount ?? 0;

        $totalInvestment = $totalInvestmentCredit - $totalInvestmentDebit;

        $received = $this->userTransactionModel->selectSum('amount')
            ->where('user_id', $userIdPk)
            ->where('type', 'credit')
            ->get()->getRow()->amount ?? 0;


        $hasDirectActive = user_model()->getDirectActiveReferralsCountFromUserIdPk($userIdPk);
        $multiplier = $hasDirectActive ? 3 : 2;

        $max = $totalInvestment * $multiplier;

        $receivedPercentage = $max == 0 ? 0 : ($received / $max) * 100;

        return resJson([
            'success' => true,
            'html' => view('user_dashboard/home/_income_progress', [
                'max' => $max,
                'investment' => $totalInvestment,
                'received' => $received,
                'receivedPercentage' => $receivedPercentage,
                'multiplier' => $multiplier
            ])
        ]);
    }


    public function handlePost()
    {
        try {
            session()->close(); // for concurrent  access, cant write into session now
            $action = inputPost('action');
            if (!$action)
                return ajax_404_response();
            switch ($action) {
                // for widget components
                case 'widget_component':
                    return resJson(['data' => $this->getWidgetComponentData()]);
                case 'recent_referrals':
                    return $this->recentReferrals();
                case 'profile':
                    return $this->userProfile();
                case 'earning_chart':
                    return $this->earningChart();
                case 'income_progress':
                    return $this->incomeProgress();
                default:
                    return ajax_404_response();
            }
        } catch (\Exception $e) {
            return server_error_ajax($e);
        } finally {
            session()->start(); // resuming session, now can write into session
        }
    }


    // Saved data means data which do not need any calculation, which we just simple can get from single select query, like wallet balance, like income stats, etc. so we will simply get that data on the main request
    private function setupSavedWidgetData()
    {
        $user_id_pk = user('id');
        $user_id = user('user_id');

        // wallet data
        $wallets = $this->walletModel->getAllWalletsFromUserIdPk(user('id'));
        $walletsWithLabel = WalletService::getWalletBalanceWithWalletLabel($wallets, fAmount: true, walletSlug: true);
        $walletUrl = route('user.wallet.transactions', '___');

        // income stats
        $incomeStats = $this->userIncomeModel->getUserIncomeStatsFromUserIdPk(user_id_pk: $user_id_pk);

        $totalInvestment = $wallets?->investment ?? 0;

        $data = [];

        foreach ($walletsWithLabel as &$wallet) {
            if (!in_array($wallet['wallet'], []))
                array_push($data, ['title' => $wallet['label'], 'value' => $wallet['fAmount'], 'url' => str_replace('___', $wallet['slug'], $walletUrl), 'icon' => 'fa-solid fa-wallet']);
        }


        $data = array_merge($data, [
            // [
            //     'title' => 'Investment',
            //     'value' => f_amount(_c($totalInvestment)),
            // ],
            [
                'title' => 'Level Open',
                'value' => $this->userModel->getUserOpenLevel($user_id_pk, $user_id),
                'icon' => 'fa-solid fa-layer-group'
            ],
            // [
            //     'title' => 'Total Profit Earning',
            //     'value' => f_amount(_c($incomeStats->{UserIncomeStats::TOTAL_EARNING}), shortForm: true, symbol: '$')
            // ],
            [
                'title' => 'Total Pending Deposit',
                'value' => f_amount(_c($incomeStats->{UserIncomeStats::TOTAL_PENDING_DEPOSIT}), shortForm: true, symbol: '$'),
            ],
            [
                'title' => 'Total Complete Deposit',
                'value' => f_amount(_c($incomeStats->{UserIncomeStats::TOTAL_COMPLETE_DEPOSIT}), shortForm: true, symbol: '$'),
            ],
            // [
            //     'title' => 'Total Pending Withdrawal',
            //     'value' => f_amount(_c($incomeStats->{UserIncomeStats::TOTAL_PENDING_WITHDRAWAL}), shortForm: true, isUser: true),
            // ],
            // [
            //     'title' => 'Complete Payouts Withdrawal',
            //     'value' => f_amount(_c($incomeStats->{UserIncomeStats::TOTAL_COMPLETE_WITHDRAWAL}), shortForm: true, isUser: true),
            // ]
        ]);


        $this->vd['savedData'] = [
            'data' => $data,
            'defaultIcon' => 'fa-solid fa-coins'
        ];
    }





    public function index()
    {
        $user = user();

        $rewardsModel = new UserRewardsModel;

        $latestReward = $rewardsModel->select('reward_id')->where('user_id', $user->id)->orderBy('reward_id', 'desc')->limit(1)->first();

        $dashboardTitle = $user->user_id . ' - ';
        $dashboardTitle .= $user->full_name;

        if ($latestReward?->reward_id) {
            $rew = Plans::REWARD_STRUCTURE[$latestReward?->reward_id];
            $rank = $rew['rank'] ?? null;
            if ($rank) {
                $dashboardTitle .= " ($rank)";
            }
        }

        $this->vd = $this->pageData('Dashboard', $dashboardTitle);

        $this->setupSavedWidgetData();


        // direct count check
        $this->vd['hasDirectUser'] = $hasDirectUser = $this->userModel->hasDirectUser(user_id_pk: $user->id);


        return view('user_dashboard/home/index', $this->vd);
    }
}
