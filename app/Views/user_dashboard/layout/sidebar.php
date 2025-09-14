<aside class="sidebar sidebar-default navs-rounded ">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a href="<?= route('user.home') ?>" class="navbar-brand dis-none align-items-center justify-content-center">
            <img width="100%" height="30" src="<?= base_url('images/logo.png') ?>" alt="logo">
            <!--<h4 class="logo-title m-0 ms-2">-->
            <!--    <?= strtoupper(data('company_name')) ?>-->
            <!--</h4>-->
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5">
                    </path>
                </svg>
            </i>
        </div>
    </div>
    <div class="sidebar-body p-0 data-scrollbar">
        <div class="collapse navbar-collapse pe-3" id="sidebar">
            <ul class="navbar-nav iq-main-menu">
                <li class="nav-item ">
                    <a class="nav-link " aria-current="page" href="<?= route('user.home') ?>">
                        <i class="icon">
                            <svg width="22" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.15722 20.7714V17.7047C9.1572 16.9246 9.79312 16.2908 10.581 16.2856H13.4671C14.2587 16.2856 14.9005 16.9209 14.9005 17.7047V17.7047V20.7809C14.9003 21.4432 15.4343 21.9845 16.103 22H18.0271C19.9451 22 21.5 20.4607 21.5 18.5618V18.5618V9.83784C21.4898 9.09083 21.1355 8.38935 20.538 7.93303L13.9577 2.6853C12.8049 1.77157 11.1662 1.77157 10.0134 2.6853L3.46203 7.94256C2.86226 8.39702 2.50739 9.09967 2.5 9.84736V18.5618C2.5 20.4607 4.05488 22 5.97291 22H7.89696C8.58235 22 9.13797 21.4499 9.13797 20.7714V20.7714"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </i>
                        <span class="item-name">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-user" role="button"
                        aria-expanded="false" aria-controls="sidebar-user">
                        <i class="icon">
                            <svg width="22" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.9849 15.3462C8.11731 15.3462 4.81445 15.931 4.81445 18.2729C4.81445 20.6148 8.09636 21.2205 11.9849 21.2205C15.8525 21.2205 19.1545 20.6348 19.1545 18.2938C19.1545 15.9529 15.8735 15.3462 11.9849 15.3462Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.9849 12.0059C14.523 12.0059 16.5801 9.94779 16.5801 7.40969C16.5801 4.8716 14.523 2.81445 11.9849 2.81445C9.44679 2.81445 7.3887 4.8716 7.3887 7.40969C7.38013 9.93922 9.42394 11.9973 11.9525 12.0059H11.9849Z"
                                    stroke="currentColor" stroke-width="1.42857" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </i>
                        <span class="item-name">Profile</span>
                        <i class="right-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="sidebar-user" data-bs-parent="#sidebar">
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.profile.profileUpdate') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">My Profile</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.profile.changePassword') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Change Password</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.profile.manageTpin') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name"><?= label('tpin') ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.withdrawalMode', 'wallet-address') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Wallet Address</span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-team" role="button"
                        aria-expanded="false" aria-controls="sidebar-team">
                        <i class="fa-solid fa-users"></i>
                        <span class="item-name">Team</span>
                        <i class="right-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="sidebar-team" data-bs-parent="#sidebar">
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.team.directReferrals') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Direct Team</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.team.levelDownline') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Levelwise Team</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.team.treeView') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Team Tree</span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-deposit" role="button"
                        aria-expanded="false" aria-controls="sidebar-deposit">
                        <i class="fa-solid fa-bolt"></i>
                        <span class="item-name">Deposit</span>
                        <i class="right-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="sidebar-deposit" data-bs-parent="#sidebar">
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.deposit.deposit') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Deposit</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.deposit.logs') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Deposit History</span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-wallet" role="button"
                        aria-expanded="false" aria-controls="sidebar-wallet">
                        <i class="fa-solid fa-wallet"></i>
                        <span class="item-name">Wallets</span>
                        <i class="right-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="sidebar-wallet" data-bs-parent="#sidebar">
                        <?php foreach (\App\Services\WalletService::WALLETS as $index => &$wallet): ?>
                            <li class="nav-item">
                                <a class="nav-link "
                                    href="<?= route('user.wallet.transactions', \App\Services\WalletService::WALLET_SLUGS[$index]) ?>">
                                    <i class="icon">
                                        <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> U </i>
                                    <span class="item-name">
                                        <?= wallet_label($wallet) ?>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach ?>

                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.wallet.walletTransfer') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">
                                    Wallet Transfer
                                </span>
                            </a>
                        </li>

                    </ul>
                </li>



                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-income-log" role="button"
                        aria-expanded="false" aria-controls="sidebar-income-log">
                        <i class="fa-solid fa-clock"></i>
                        <span class="item-name">Income Details</span>
                        <i class="right-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="sidebar-income-log" data-bs-parent="#sidebar">
                        <li class="nav-item">
                            <a class="nav-link "
                                href="<?= route('user.incomeLogs', \App\Enums\WalletTransactionCategory::ROI) ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Daily Returns</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link "
                                href="<?= route('user.incomeLogs', \App\Enums\WalletTransactionCategory::SPONSOR_LEVEL_INCOME) ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Direct Income</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link "
                                href="<?= route('user.incomeLogs', \App\Enums\WalletTransactionCategory::SPONSOR_ROI_LEVEL_INCOME) ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Level Income</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link "
                                href="<?= route('user.incomeLogs', \App\Enums\WalletTransactionCategory::SALARY) ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Salary Reward</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link " href="#">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Royalty Income</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link " href="#">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Override Leadership Income</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <?php if (!1): ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-p2p-transfer" role="button"
                            aria-expanded="false" aria-controls="sidebar-p2p-transfer">
                            <i class="fa-solid fa-right-left"></i>
                            <span class="item-name">P2P Transfer</span>
                            <i class="right-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </i>
                        </a>
                        <ul class="sub-nav collapse" id="sidebar-p2p-transfer" data-bs-parent="#sidebar">
                            <li class="nav-item">
                                <a class="nav-link " href="<?= route('user.p2pTransfer.transfer') ?>">
                                    <i class="icon">
                                        <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> U </i>
                                    <span class="item-name">P2P Transfer</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="<?= route('user.p2pTransfer.logs') ?>">
                                    <i class="icon">
                                        <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> U </i>
                                    <span class="item-name">P2P Transfer Logs</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                <?php endif; ?>


                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-withdrawal" role="button"
                        aria-expanded="false" aria-controls="sidebar-withdrawal">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        <span class="item-name">Withdrawal</span>
                        <i class="right-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="sidebar-withdrawal" data-bs-parent="#sidebar">
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.withdrawal.withdrawNow') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Withdraw Now</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.withdrawal.logs') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Withdrawal Logs</span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-topup" role="button"
                        aria-expanded="false" aria-controls="sidebar-topup">
                        <i class="fa-solid fa-briefcase"></i>
                        <span class="item-name">Topup</span>
                        <i class="right-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="sidebar-topup" data-bs-parent="#sidebar">
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.topup.topupUser') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Topup</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.topup.compound') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Compound Invest</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="<?= route('user.topup.logs') ?>">
                                <i class="icon">
                                    <svg width="10" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="1" width="11" height="11" stroke="currentcolor" />
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> U </i>
                                <span class="item-name">Investment History</span>
                            </a>
                        </li>

                    </ul>
                </li>


            </ul>
        </div>
        <div id="sidebar-footer" class="position-relative sidebar-footer">
            <div class="card mx-4">
                <div class="card-body">
                    <div class="sidebarbottom-content">
                        <div class="image">
                            <img src="<?= base_url('images/sidebar-lower.jpg') ?>" alt="User-Profile" class="img-fluid">
                        </div>
                        <p class="mb-0 mt-3">Be more secure with Pro Feature</p>
                        <a href="<?= route('user.topup.topupUser') ?>">
                            <button type="button" class="btn btn-primary mt-3">Upgrade Now</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>