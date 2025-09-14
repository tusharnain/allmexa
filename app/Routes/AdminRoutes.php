<?php
namespace App\Routes;

use CodeIgniter\Router\RouteCollection;

class AdminRoutes
{
    private static $auth_namespace = "App\Controllers\AdminAuth";
    private static $dashboard_namespace = "App\Controllers\AdminDashboard";

    private static function authRoutes(RouteCollection &$routes)
    {
        $routes->get('login', "LoginLogout::login", ['as' => 'admin.login']);
        $routes->post('api/login', "LoginLogout::loginPost", ['as' => 'admin.loginPost']);
        $routes->post('api/logout', "LoginLogout::logoutPost", ['filter' => 'admin.auth', 'as' => 'admin.logoutPost']);
    }


    private static function dashboardRoutes(&$routes)
    {
        $user_label = label('user', 1);
        $users_label = label('users', 1);

        $routes->get('/', "Home\Index::index", ['as' => 'admin.home']);

        $routes->group($users_label, function ($routes) use ($user_label) {
            $routes->get("list/(:segment)", "Users\UsersList::index/$1", ['as' => 'admin.users.list']);
            //User page
            $routes->match(['get', 'post'], "u/(:segment)", "Users\User::index/$1", ['as' => 'admin.users.user']);
            // Add new user
            $routes->match(['get', 'post'], "add-new-$user_label", "Users\AddNewUser::index", ['as' => 'admin.users.addNewUser']);

            $routes->get('user-kyc', "Users\UserKyc::index", ['as' => 'admin.users.userKyc']);

            $routes->match(['get', 'post'], 'user-kyc/(:segment)', "Users\UserKyc::detail/$1", ['as' => 'admin.users.kycDetail']);

            $routes->get('reward-users', "Users\RewardUsers::index", ['as' => 'admin.users.rewardUsers']);
        });


        // deposits
        $routes->group('deposits', function ($routes) use ($user_label) {

            $routes->match(['get', 'post'], "$user_label-deposits", 'Deposits\UserDeposits::index', ['as' => 'admin.deposits.userDeposits']);

            $routes->match(['get', 'post'], "dp/(:segment)", "Deposits\UserSingleDeposit::index/$1", ['as' => 'admin.deposits.userSingleDeposit']);

            if (admin_role(1))
                $routes->match(['get', 'post'], 'deposit-modes', 'Deposits\DepositModes::index', ['as' => 'admin.deposits.depositModes']);
        });

        // withdrawals
        $routes->group('withdrawals', function ($routes) use ($user_label) {

            $routes->match(['get', 'post'], "$user_label-withdrawals", 'Withdrawals\UserWithdrawals::index', ['as' => 'admin.withdrawals.userWithdrawals']);

            $routes->match(['get', 'post'], "wd/(:segment)", "Withdrawals\UserSingleWithdrawal::index/$1", ['as' => 'admin.withdrawals.userSingleWithdrawal']);

        });


        // wallets
        $routes->group('wallets', function ($routes) {
            $routes->match(['get', 'post'], 'add-deduct', "Wallets\AddDeduct::index", ['as' => 'admin.wallets.addDeduct']);
            $routes->get('admin-history', "Wallets\AdminHistory::index", ['as' => 'admin.wallets.adminHistory']);
        });


        if (admin_role(1)) {
            // topup
            $routes->group('topup', function ($routes) {
                $routes->match(['get', 'post'], "topup-user", 'Topup\TopupUser::index', ['as' => 'admin.topup.topupUser']);
                //topup history
                $routes->get('logs', 'Topup\TopupLogs::index', ['as' => 'admin.topup.logs']);
            });
        }

        // tickets
        $routes->get('support/tickets/(:segment)', "Support\TicketsList::index/$1", ['as' => 'admin.support.ticketList']);
        $routes->match(['get', 'post'], 'support/tickets/tc/(:segment)', "Support\Ticket::index/$1", ['as' => 'admin.support.ticket']);

        // User File Controller
        $routes->get('files/(:segment)/(:segment)', 'AdminFilesController::index/$1/$2', ['as' => 'admin.file']);

        $routes->get('data', 'CustomDataController::index');
    }

    public static function setupRoutes(RouteCollection &$routes, string $adminRouteGroup)
    {
        /*
         *------------------------------------------------------------------------------------
         *  AUTH ROUTES
         *------------------------------------------------------------------------------------
         */
        $routes->group(
            $adminRouteGroup,
            [
                'namespace' => self::$auth_namespace,
                'filter' => 'admin.noAuth'
            ],
            static function (&$routes) {

                self::authRoutes($routes);
            }
        );



        /*
         *------------------------------------------------------------------------------------
         * DASHBOARD ROUTES
         *------------------------------------------------------------------------------------
         */
        $routes->group(
            $adminRouteGroup,
            [
                'namespace' => self::$dashboard_namespace,
                'filter' => 'admin.auth'
            ],
            static function (&$routes) {

                self::dashboardRoutes($routes);
            }
        );

    }
}