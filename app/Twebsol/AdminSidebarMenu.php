<?php

namespace App\Twebsol;

use stdClass;

final class AdminSidebarMenu
{
    private static string $CACHE_NAME = '';
    const CACHE_TTL = 2678400; // 1 month
    private static ?string $sidebarMenusJson = null;
    public array $menus;
    private int $menuCount = 0, $submenuCount = 0;
    private string $defaultIcon = 'mdi mdi-arrow-right';
    const NO_URL = "javascript: void(0);";


    public function __construct()
    {
        self::$CACHE_NAME = 'admin_sidebar_' . admin('role');
        $this->setupMenu();
    }

    public function menuCount(): int
    {
        return $this->menuCount;
    }
    public function submenuCount(): int
    {
        return $this->menuCount;
    }


    private function getObject(string &$title, string &$url = null, string $icon = null): stdClass
    {
        $menu = new stdClass;

        $menu->title = $title;
        $menu->url = $url ? $url : self::NO_URL;
        $menu->icon = $icon ?? $this->defaultIcon;
        $menu->submenus = null;


        return $menu;
    }


    private function addMenu(string $title, string $url = null, string $icon = null): self
    {
        $this->menus[] = $this->getObject($title, $url, $icon);

        $this->menuCount++; // incrementing menu count

        return $this;
    }
    private function addSubMenu(string $title, string $url = null, string $icon = null): self
    {

        $menu = $this->getObject($title, $url, $icon);
        $menu->isSubmenu = true;


        $this->menus[$this->menuCount - 1]->submenus[] = $menu;

        $this->submenuCount++; // incrementing submenu count

        return $this;
    }




    /*
     *------------------------------------------------------------------------------------
     * Here start the menus
     *------------------------------------------------------------------------------------
     */

    private function setupMenu()
    {
        //labels
        $user_label = label('user');
        $users_label = label('users');


        $this->addMenu('Dashboard', route('admin.home'), 'mdi mdi-view-dashboard-outline');

        // Users
        $userslist_url = route('admin.users.list', '_1');
        $this->addMenu($users_label, icon: 'mdi mdi-account-multiple')
            ->addSubMenu("All $users_label", str_replace('_1', 'all', $userslist_url))
            ->addSubMenu("Active $users_label", str_replace('_1', 'active', $userslist_url))
            ->addSubMenu("Inactive $users_label", str_replace('_1', 'inactive', $userslist_url))
            ->addSubMenu('User Kyc', route('admin.users.userKyc'))
            ->addSubMenu('User Rewards', route('admin.users.rewardUsers'))
            ->addSubMenu("Add new $user_label", route('admin.users.addNewUser'));


        $this->addMenu('Deposits', icon: 'mdi mdi-account-cash')
            ->addSubMenu("$user_label Deposits", route('admin.deposits.userDeposits'));
        if (admin_role(1))
            $this->addSubMenu('Deposit Modes', route('admin.deposits.depositModes'));


        $this->addMenu('Withdrawals', icon: 'mdi mdi mdi-bank-transfer-in')
            ->addSubMenu("$user_label Withdrawals", route('admin.withdrawals.userWithdrawals'));


        $this->addMenu('Wallets', icon: 'mdi mdi-wallet')
            ->addSubMenu('Add/Deduct Wallet', route('admin.wallets.addDeduct'))
            ->addSubMenu('Admin History', route('admin.wallets.adminHistory'));


        if (admin_role(1)) {
            $this->addMenu('Topup', icon: 'mdi mdi-account-key')
                ->addSubMenu("Topup $user_label", route('admin.topup.topupUser'))
                ->addSubMenu('Topup Logs', route('admin.topup.logs'));
        }

        // Tickets
        $ticket_url = route('admin.support.ticketList', '_1'); // a little optimisation // a little bit
        $this->addMenu('Support Tickets', icon: 'mdi mdi-help-circle')
            // ->addSubMenu('All Tickets', str_replace('_1', 'all', $ticket_url))
            ->addSubMenu('Open Tickets', str_replace('_1', 'open', $ticket_url))
            ->addSubMenu('Closed Tickets', str_replace('_1', 'close', $ticket_url));


    }



    /*
     *------------------------------------------------------------------------------------
     * Static methods
     *------------------------------------------------------------------------------------
     */
    public static function getSideberMenuJson(bool $cache = false): string
    {
        self::$CACHE_NAME = 'admin_sidebar_' . admin('role');

        if (self::$sidebarMenusJson)
            return self::$sidebarMenusJson;

        // if $cache is true and if cached, then giving cached instance
        if ($cache and ($cachedSidebarMenuJson = cache()->get(self::$CACHE_NAME)))
            return $cachedSidebarMenuJson;

        $sidebar = new AdminSidebarMenu();
        self::$sidebarMenusJson = json_encode($sidebar->menus);

        // if $cache is true, then save it to cache
        if ($cache)
            cache()->save(self::$CACHE_NAME, self::$sidebarMenusJson, self::CACHE_TTL);

        return self::$sidebarMenusJson;
    }
    public static function removeCache(): bool
    {
        return cache()->delete(self::$CACHE_NAME);
    }
}
