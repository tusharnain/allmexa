<?php

$hasRecords = (isset($users) and is_array($users) and count($users) > 0);

$widgets = [
    'Total Direct Users' => $pager->getTotal(),
    'Active Direct Users' => $totalActiveReferrals
];
?>

<?= $this->extend('user_dashboard/layout/master') ?>

<?php $this->section('style') ?>
<style>
    table tr td:first-child {
        padding-left: 20px;
    }

    .user-avatar {
        object-fit: cover;
    }

    @media only screen and (max-width: 770px) {
        .search-btn {
            float: right;
        }
    }

    .user-avatar-table {
        width: 30px;
        height: 30px;
        object-fit: cover;
        border-radius: 100%;
    }
</style>
<?php $this->endSection() ?>


<?= $this->section('slot') ?>

<div class="container-fluid">
    <?php if ($hasRecords): ?>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <?php foreach ($widgets as $title => $value): ?>
                        <div class="col-xl-3 col-md-6">
                            <div class="card shining-card">
                                <div class="card-body">
                                    <!-- <img src="../assets/images/coins/01.png" class="img-fluid avatar avatar-50 avatar-rounded"
                                        alt="img60"> -->
                                    <span class="fs-5 me-2">
                                        <?= $title ?>
                                    </span>
                                    <svg width="36" height="35" viewBox="0 0 36 35" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3.86124 21.6224L11.2734 16.8577C11.6095 16.6417 12.041 16.6447 12.3718 16.8655L18.9661 21.2663C19.2968 21.4871 19.7283 21.4901 20.0644 21.2741L27.875 16.2534"
                                            stroke="#BFBFBF" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path
                                            d="M26.7847 13.3246L31.6677 14.0197L30.4485 18.7565L26.7847 13.3246ZM30.2822 19.4024C30.2823 19.4023 30.2823 19.4021 30.2824 19.402L30.2822 19.4024ZM31.9991 14.0669L31.9995 14.0669L32.0418 13.7699L31.9995 14.0669C31.9994 14.0669 31.9993 14.0669 31.9991 14.0669Z"
                                            fill="#BFBFBF" stroke="#BFBFBF"></path>
                                    </svg>
                                    <div class="pt-3">
                                        <h4 class="counter" style="visibility: visible;">
                                            <?= $value ?>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET">
                            <div class="row">
                                <?php
                                $userIdLabel = label('user_id');
                                $userNameLabel = label('user_name');
                                ?>
                                <div class="col-md-6">
                                    <?= user_component('input', [
                                        'name' => 'search',
                                        'label' => 'Search',
                                        'placeholder' => "$userIdLabel / $userNameLabel / Email / Phone",
                                        'value' => $search ?? ''
                                    ]) ?>
                                </div>
                                <div class="col-md-2">
                                    <?= user_component('page_length_select', [
                                        'lengths' => $pageLengths ?? [15, 50, 100, 200],
                                        'current_page_length' => $pageLength
                                    ]) ?>
                                </div>
                                <div class="col-md-4">
                                    <div class="mt-0 mt-md-2">
                                        <?= user_component('button', [
                                            'class' => 'search-btn btn-lg',
                                            'label' => 'Go',
                                            'submit' => true
                                        ]) ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div id="direct-referrals-table-parent"></div>
            </div>
        </div>
    <?php else: ?>
        <?= view('user_dashboard/team/_no_referrals_alert') ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>


<?php if ($hasRecords): ?>
    <?php $this->section('script') ?>
    <script>
        $(document).ready(function () {
            const users = <?= json_encode($users) ?>;
            setupDirectReferralsTable(users, {
                userIdLabel: "<?= label('user_id') ?>",
                userNameLabel: "<?= label('user_name') ?>",
                activeStatusLabel: "<?= label('user_status_active') ?>",
                inactiveStatusLabel: "<?= label('user_status_inactive') ?>",
                indexInitNumber: <?= pager_init_serial_number($pager) ?>,
                paginationHtml: `<?= $pager->links(template: 'user_dashboard') ?>`,
                hasRecords: <?= $hasRecords ? 'true' : 'false' ?>,
                defaultAvatarImage: "<?= \App\Models\UserModel::getDefaultAvatarImage() ?>",
                avatarDirectoryPath: "<?= \App\Models\UserModel::getAvatarDirctoryPath() ?>",
            });

        });
    </script>
    <?php $this->endSection() ?>
<?php endif; ?>