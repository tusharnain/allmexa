<?php

namespace App\Twebsol;

use App\Enums\WalletTransactionCategory as TxnCat;
use App\Services\WalletService;
use stdClass;

final class UserSidebarMenu
{
    private static string $CACHE_NAME;
    const CACHE_TTL = 2678400; // 1 month
    private static bool $isRootUser = false;
    private static ?string $sidebarMenusJson = null;
    public array $menus;
    private int $menuCount = 0, $submenuCount = 0;
    const NO_URL = "javascript: void(0);";
    private static array $walletSlugs = WalletService::WALLET_SLUGS;
    private static array $wallets = WalletService::WALLETS;


    public function __construct()
    {
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


    private function getObject(string &$title, string &$url = null, string $icon = null, $download = false): stdClass
    {
        $menu = new stdClass;

        $menu->title = $title;
        $menu->url = $url ? $url : self::NO_URL;
        $menu->icon = $icon;
        $menu->download = $download;
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



    private function setupMenu()
    {
        $this->addMenu('Dashboard', url: route('user.home'), icon: 'ti-home');

        $this->addMenu('Manage Profile', icon: 'ti-user')
            ->addSubMenu('My Profile & Nominee', route('user.profile.profileUpdate'))
            ->addSubMenu('Change Password', route('user.profile.changePassword'))
            ->addSubMenu('Manage ' . label('tpin'), route('user.profile.manageTpin'))
            ->addSubMenu('Wallet Address', url: route('user.withdrawalMode', 'wallet-address'));
        // ->addSubMenu('KYC', url: route('user.profile.kyc'));



        // team
        $this->addMenu('Team / Network', icon: 'ti-bar-chart')
            ->addSubMenu('Direct Referrals', route('user.team.directReferrals'))
            ->addSubMenu('Level Team', route('user.team.levelTeam'))
            ->addSubMenu('Level Downline', route('user.team.levelDownline'))
            ->addSubMenu('Tree View', route('user.team.treeView'));
        // ->addSubMenu('Binary Tree', route('user.team.binaryTree')); // binary 


        // deposit
        $this->addMenu('Deposit', icon: 'ti-share-alt')
            ->addSubMenu('Make Deposit', route('user.deposit.deposit'))
            ->addSubMenu('Deposit Logs', route('user.deposit.logs'));

        //wallets
        $this->addMenu('Wallet Manager', icon: 'ti-server');
        foreach (self::$wallets as $index => &$wallet) {
            $this->addSubMenu(wallet_label($wallet), route('user.wallet.transactions', self::$walletSlugs[$index]));
        }
        $this->addSubMenu('Wallet Transfer', route('user.wallet.walletTransfer'));

        // income logs
        $this->addMenu('Income Logs', icon: 'ti-pulse')
            ->addSubMenu('Daily Return', route('user.incomeLogs', TxnCat::ROI))
            ->addSubMenu('Direct Income', route('user.incomeLogs', TxnCat::SPONSOR_LEVEL_INCOME))
            ->addSubMenu('Level Income', route('user.incomeLogs', TxnCat::SPONSOR_ROI_LEVEL_INCOME))
            ->addSubMenu('Salary Reward', route('user.incomeLogs', TxnCat::SALARY));


        // P2P Transfer
        $this->addMenu('P2P Transfer', icon: 'ti-pulse')
            ->addSubMenu('P2P Transfer', route('user.p2pTransfer.transfer'))
            ->addSubMenu('P2P Transfer Logs', route('user.p2pTransfer.logs'));


        // Topup
        $this->addMenu('Topup', icon: 'ti-briefcase')
            ->addSubMenu('Topup', route('user.topup.topupUser'));


        // $this->addSubMenu('LP Topup', route('user.topup.lpTopupUser'));
        // $this->addSubMenu('IP Topup', route('user.topup.ipTopupUser'));


        $this->addSubMenu('Topup Logs', route('user.topup.logs'));

        // Withdrawal
        $this->addMenu('Payouts Logs', url: route('user.withdrawal.logs'), icon: 'ti-bookmark');


        // support
        // $this->addMenu('Help & Support', icon: 'ti-headphone-alt')
        //     ->addSubMenu('Generate Ticket', route('user.support.generateTicket'))
        //     ->addSubMenu('Ticket History', route('user.support.ticketHistory'));


        // login logs
        // $this->addMenu('Login Logs', url: route('user.loginLogs'), icon: 'ti-new-window');
    }


    /*
     *------------------------------------------------------------------------------------
     * Static methods
     *------------------------------------------------------------------------------------
     */
    public static function getSideberMenuJson(bool $cache = false): string
    {

        $user_id_pk = user('id');
        self::$isRootUser = is_root_user(user_id_pk: $user_id_pk);
        $flagName = self::$isRootUser ? 'root_user' : 'normal';
        self::$CACHE_NAME = 'user_sidebar_json_' . $flagName;

        if (self::$sidebarMenusJson)
            return self::$sidebarMenusJson;

        // if $cache is true and if cached, then giving cached instance
        if ($cache and ($cachedSidebarMenuJson = cache()->get(self::$CACHE_NAME)))
            return $cachedSidebarMenuJson;

        $sidebar = new UserSidebarMenu();
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
