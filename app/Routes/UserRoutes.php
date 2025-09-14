<?php
namespace App\Routes;

use CodeIgniter\Router\RouteCollection;

final class UserRoutes
{
    private static bool $isRootUser;
    private static string $pageSuffix;
    private static string $apiSuffix;
    private static string $auth_namespace = "App\Controllers\UserAuth";
    private static string $dashboard_namespace = "App\Controllers\UserDashboard";
    private static function authRoutes(RouteCollection &$routes)
    {
        $routes->get('login', "LoginLogout::login", ['as' => 'login']);
        $routes->post('api/login', "LoginLogout::loginPost", ['as' => 'loginPost']);

        $routes->post('api/logout', "LoginLogout::logoutPost", ['filter' => 'user.auth', 'as' => 'logoutPost']);

        $routes->match(['get', 'post'], 'forget-password', 'ForgetAndResetPassword::forgetPassword', ['as' => 'forgetPassword']);
        $routes->match(['get', 'post'], 'reset-password', 'ForgetAndResetPassword::resetPassword', ['as' => 'resetPassword']);

        $routes->get('captcha/login.png', 'LoginLogout::loginCaptchaImage', ['as' => 'loginCaptchaImage']);
    }



    private static function dashboardRoutes(RouteCollection &$routes)
    {
        $routes->get('/', 'Home\Index::index', ['as' => 'user.home']);
        $routes->post('dashboardApi', 'Home\Index::handlePost', ['as' => 'user.home.dashboardPost']);



        // User Profile
        $routes->group('profile', function ($routes) {
            $routes->get('/', 'Profile\Profile::index', ['as' => 'user.profile.profileUpdate']);
            $routes->post('update-post', 'Profile\Profile::updateDetailsPost', ['as' => 'user.profile.profileUpdatePost']);

            // Change Password
            $routes->match(['get', 'post'], 'change-password', 'Profile\ChangePassword::index', ['as' => 'user.profile.changePassword']);

            // Change Tpin
            $routes->match(['get', 'post'], 'manage-' . label('tpin', 3), 'Profile\ManageTpin::index', ['as' => 'user.profile.manageTpin']);

            // Kyc
            // $routes->match(['get', 'post'], 'kyc', 'Profile\Kyc::index', ['as' => 'user.profile.kyc']);
        });


        // Wallet
        $routes->group('wallet', function ($routes) {

            $routes->match(['get', 'post'], 'wallet-transfer', 'Wallet\WalletTransfer::index', ['as' => 'user.wallet.walletTransfer']);

            // wallet transactions
            $routes->match(['get', 'post'], '(:segment)', 'Wallet\WalletTransactions::index/$1', ['as' => 'user.wallet.transactions']); // taking wallet slug as param
        });

        // Income Logs
        $routes->get('income-logs/(:segment)', 'IncomeLogs\Index::index/$1', ['as' => 'user.incomeLogs']);



        // Team
        $routes->group('team', function ($routes) {

            $routes->get('direct-referrals', 'Team\DirectReferrals::index', ['as' => 'user.team.directReferrals']);

            $routes->match(['get', 'post'], 'level-team', 'Team\LevelTeam::index', ['as' => 'user.team.levelTeam']);

            $routes->match(['get', 'post'], 'level-downline', 'Team\LevelDownline::index', ['as' => 'user.team.levelDownline']);

            $routes->get('tree-view', 'Team\TreeView::index', ['as' => 'user.team.treeView']);
        });


        // Deposit
        $routes->group('deposit', function ($routes) {
            $routes->match(['get', 'post'], '/', 'Deposit\Deposit::index', ['as' => 'user.deposit.deposit']);
            $routes->match(['get', 'post'], 'confirm', 'Deposit\DepositConfirm::index', ['as' => 'user.deposit.confirm']);
            $routes->match(['get', 'post'], 'logs', 'Deposit\DepositLogs::index', ['as' => 'user.deposit.logs']);
        });


        // Topup
        $routes->group('topup', function ($routes) {
            //main topup user page
            $routes->match(['get', 'post'], 'topup-user', 'Topup\TopupUser::index', ['as' => 'user.topup.topupUser']);
            $routes->match(['get', 'post'], 'compound', 'Topup\Compound::index', ['as' => 'user.topup.compound']);


            //topup history
            $routes->get('topup-logs', 'Topup\TopupLogs::index', ['as' => 'user.topup.logs']);
        });


        //P2P Transfer
        // $routes->group('p2p-transfer', function ($routes) {
        //     $routes->match(['get', 'post'], '/', 'P2PTransfer\P2PTransfer::index', ['as' => 'user.p2pTransfer.transfer']);
        //     $routes->get('logs', 'P2PTransfer\P2PTransferLogs::index', ['as' => 'user.p2pTransfer.logs']);
        // });

        // WithdrawalModes
        $routes->match(['get', 'post'], 'wmode/(:segment)', 'WithdrawalModes\Index::index/$1', ['as' => 'user.withdrawalMode']);


        // Withdrawals
        $routes->group('withdrawal', function ($routes) {
            $routes->match(['get', 'post'], 'withdraw-now', 'Withdrawal\WithdrawNow::index', ['as' => 'user.withdrawal.withdrawNow']);
            $routes->match(['get', 'post'], 'logs', 'Withdrawal\WithdrawalLogs::index', ['as' => 'user.withdrawal.logs']);
        });

        // Support
        // $routes->group('support', function ($routes) {
        //     $routes->match(['get', 'post'], 'generate-ticket', 'Support\GenerateTicket::index', ['as' => 'user.support.generateTicket']);
        //     $routes->match(['get', 'post'], 'ticket-history', 'Support\TicketHistory::index', ['as' => 'user.support.ticketHistory']);
        // });

        // $routes->get('login-logs', 'LoginLogs\Index::index', ['as' => 'user.loginLogs']);


        // User File Controller
        $routes->get('files/(:segment)/(:segment)', 'UserFilesController::index/$1/$2', ['as' => 'user.file']);
    }



    public static function setupRoutes(RouteCollection &$routes, string &$userRouteGroup)
    {
        $user_id_pk = user()->id ?? null;
        self::$isRootUser = $user_id_pk ? is_root_user($user_id_pk) : false;


        /*
         *------------------------------------------------------------------------------------
         *  AUTH ROUTES
         *------------------------------------------------------------------------------------
         */
        $routes->group(
            '/',
            [
                'namespace' => self::$auth_namespace,
                'filter' => 'user.noAuth'
            ],
            static function (&$routes) {
                self::authRoutes($routes);
            }
        );


        // Those auth routes, where auth/noauth, filter doesnt matter
        $routes->group(
            '/',
            ['namespace' => self::$auth_namespace],
            static function (&$routes) {
                $routes->get('register', 'Register::index', ['as' => 'register']);
                $routes->match(['get', 'post'], 'confirm-email', 'Register::otpPage', ['as' => 'confirmOtp']);
                $routes->get('refer/(:segment)', 'Register::referral/$1', ['as' => 'referral']);
                $routes->post('api/register', 'Register::registerPost', ['as' => 'registerPost']);
                $routes->get('captcha/register.png', 'Register::captchaImage', ['as' => 'registerCaptchaImage']);
            }
        );



        /*
         *------------------------------------------------------------------------------------
         * DASHBOARD ROUTES
         *------------------------------------------------------------------------------------
         */
        $routes->group(
            $userRouteGroup,
            [
                'namespace' => self::$dashboard_namespace,
                'filter' => 'user.auth'
            ],
            static function (&$routes) {

                self::dashboardRoutes($routes);
            }
        );
    }
}