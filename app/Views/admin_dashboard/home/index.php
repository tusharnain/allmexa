<?php

$userUrl = route('admin.users.user', '_1');

?>

<?= $this->extend('admin_dashboard/_partials/app') ?>

<?= $this->section('style') ?>
<style>
    .widget1 {
        box-shadow: rgba(99, 99, 99, 0.2) 6px 6px 0px 0px !important;
    }

    /* Wiget2 */
    .bg-pink {
        background-color: #ff70a3;
    }

    .bg-pink-blue {
        background: linear-gradient(127deg, #00a4b6, #8575bf);
    }

    .bg-pink-gradient {
        background: linear-gradient(104deg, #956ec5, #b00085);
    }

    .bg-black-blue-gradient {
        background: linear-gradient(104deg, #293e6bd1, #7a7a7a);
    }

    .bg-orange-yellow-gradient {
        background: linear-gradient(278deg, #ffd600, #ff6900);
    }

    .bg-sky-blue-gradient {
        background: linear-gradient(104deg, #00beffd1, #9cddf4);
    }

    .bg-pink-blue-gradient {
        background: linear-gradient(104deg, #a200ffa8, #0022fb);
    }

    .bg-light-blue-gradient {
        background: linear-gradient(104deg, #2db8a6d1, #51d6ca);
    }
</style>

<style>
    body {
        font-family: Arial, sans-serif;
    }

    .popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        display: none;
        z-index: 1000;
        text-align: center;
    }

    .popup a {
        display: block;
        margin: 10px 0;
        color: blue;
        text-decoration: none;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 999;
    }

    .close-btn {
        display: block;
        margin-top: 10px;
        cursor: pointer;
        background: red;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('slot') ?>
<div class="container-fluid">
    <div class="row">
        <!-- card1 -->
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card bg-info widget1" role="button">
                <div class="card-body pb-4">
                    <div class="d-flex text-muted">
                        <div class="flex-shrink-0 pb-4  me-3 align-self-center">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-light rounded-circle text-info h2"
                                    style="width: 70px; height : 70px;">
                                    <i class="ri-group-fill"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="mb-1 h5 single-line-ellipsis text-white">
                                Total Users
                            </p>
                            <strong id="totalUsersCount" class="mb-3 h3 text-white">
                                <?= $totalUsers ?>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- card2 -->
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card bg-success widget1" role="button">
                <div class="card-body pb-4">
                    <div class="d-flex text-muted">
                        <div class="flex-shrink-0 pb-4  me-3 align-self-center">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-light rounded-circle text-success h2"
                                    style="width: 70px; height : 70px;">
                                    <i class="ri-user-follow-fill"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="mb-1 h5 single-line-ellipsis text-white">
                                Active Users
                            </p>
                            <strong id="totalUsersCount" class="mb-3 h3 text-white">
                                <?= $totalActiveUsers ?>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- card3 -->
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card bg-pink widget1 border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <span class="h5 single-line-ellipsis font-semibold text-white text-sm d-block mb-2">
                                Today's Registrations
                            </span>
                            <span class="h2 font-bold text-white mb-0">
                                <?= $todaysRegistrations ?>
                            </span>
                        </div>
                        <div class="col-3 my-3">
                            <i class="fas fa-user-clock h1 text-white ms-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- card4 -->
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card bg-pink-blue widget1 border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <span class="h5 single-line-ellipsis font-semibold text-white text-sm d-block mb-2">
                                Today's Investment
                            </span>
                            <span class="h2 font-bold text-white mb-0">
                                <?= f_amount($todaysInvestment, true) ?>
                            </span>
                        </div>
                        <div class="col-3 my-3">
                            <i class="ri-user-follow-fill h1 text-white ms-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- card5 -->
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card bg-orange-yellow-gradient widget1 border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <span class="h5 single-line-ellipsis font-semibold text-white text-sm d-block mb-2">
                                Total Investment
                            </span>
                            <span class="h2 font-bold text-white mb-0">
                                <?= f_amount($totalInvestment, true) ?>
                            </span>
                        </div>
                        <div class="col-3 my-3">
                            <i class="fas fa-business-time h1 text-white ms-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- card6 -->
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card bg-pink-blue-gradient widget1 border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <span class="h5 single-line-ellipsis font-semibold text-white text-sm d-block mb-2">
                                Fund in Market
                            </span>
                            <span class="h2 font-bold text-white mb-0">
                                <?= f_amount($fundInMarket, true) ?>
                            </span>
                        </div>
                        <div class="col-3 my-3">
                            <i class="mdi mdi-credit-card-refund h1 text-white ms-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- card7 -->
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card bg-pink-blue-gradient widget1 border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <span class="h5 single-line-ellipsis font-semibold text-white text-sm d-block mb-2">
                                Income in Wallets
                            </span>
                            <span class="h2 font-bold text-white mb-0">
                                <?= f_amount($incomeInWallets, true) ?>
                            </span>
                        </div>
                        <div class="col-3 my-3">
                            <i class="mdi mdi-credit-card-refund h1 text-white ms-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- card8 -->
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card bg-warning widget1">
                <div class="card-body pb-4">
                    <div class="d-flex text-muted">
                        <div class="flex-shrink-0 pb-4  me-3 align-self-center">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-light rounded-circle text-warning h2"
                                    style="width: 70px; height : 70px;">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="mb-1 h5 single-line-ellipsis text-dark">
                                Pending Withdrawal
                            </p>
                            <strong id="totalUsersCount" class="mb-3 h3 text-dark">
                                <?= f_amount($pendingWithdrawal, true) ?>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- card9 -->
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card bg-light-blue-gradient widget1 border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <span class="h5 single-line-ellipsis font-semibold text-white text-sm d-block mb-2">
                                Pending Net Withdrawal
                            </span>
                            <span class="h2 font-bold text-white mb-0">
                                <?= f_amount($withdrawalNetAmount, true) ?>
                            </span>
                        </div>
                        <div class="col-3 my-3">
                            <i class="fas fa-coins h1 text-white ms-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- card10 -->
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card bg-black-blue-gradient widget1 border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <span class="h5 single-line-ellipsis font-semibold text-white text-sm d-block mb-2">
                                Total Complete Withdrawal
                            </span>
                            <span class="h2 font-bold text-white mb-0">
                                <?= f_amount($completeWithdrawal) ?>
                            </span>
                        </div>
                        <div class="col-3 my-3">
                            <i class="fas fa-level-up-alt h1 text-white ms-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="row mt-2">
            <div class="col-lg-6">
                <div wire:id="zOj9DSigB7hyFA2Xinno">
                    <div class="card widget1">
                        <div class="card-header bg-primary pt-3">
                            <h4 class="text-white">Recent <?= label('users') ?></h4>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-around">
                                <?php foreach ($recentRegisteredUsers as &$user): ?>
                                    <div class="col-4 col-lg-3 col-xl-2 text-center" role="button"
                                        onclick="redir('<?= str_replace('_1', $user->user_id, $userUrl) ?>')">
                                        <img class="rounded-circle" src="<?= App\Models\UserModel::getAvatar($user); ?>"
                                            width="60" height="60" alt="user_avatar">
                                        <h6 class="single-line-ellipsis mt-2 mb-0">
                                            <?= $user->user_id ?>
                                        </h6>
                                        <p class="single-line-ellipsis">
                                            <?= $user->full_name ?>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="<?= route('admin.users.list', 'all') ?>">
                                <button class="btn btn-sm btn-primary float-end">
                                    View All
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?php $this->section('script') ?>
<script>
    function redir(url) {
        window.location.href = url;
    }
</script>
<?php $this->endSection() ?>