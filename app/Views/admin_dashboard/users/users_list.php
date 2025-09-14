<?php

$passwordHashEnabled = _setting('hash_user_password', true);
$tpinHashEnabled = _setting('hash_user_tpin', true);

$userIdLabel = label('user_id');
$userNameLabel = label('user_name');
$sponsorIdLabel = label('sponsor_id');
$sponsorNameLabel = label('sponsor_name');
$tpinLabel = label('tpin');

$hasRecords = (isset($users) and is_array($users) and count($users) > 0);

if ($hasRecords) {
    $userUrl = route('admin.users.user', '_1');
}

?>


<?= $this->extend('admin_dashboard/_partials/app') ?>


<?php $this->section('style') ?>
<style>
    .user-avatar {
        object-fit: cover;
        height: 2.4rem;
        width: 2.4rem;
    }

    #adv_filter_loader {
        display: none;
    }
</style>
<?php $this->endSection() ?>


<?= $this->section('slot') ?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card rounded-3">
                <div class="card-body">

                    <form method="GET">
                        <div class="row">

                            <?php
                            $userIdLabel = label('user_id');
                            $userNameLabel = label('user_name');
                            ?>
                            <div class="col-md-6">
                                <?= admin_component('input', [
                                    'name' => 'search',
                                    'label' => 'Search',
                                    'placeholder' => "$userIdLabel / $userNameLabel / Email / Phone",
                                    'value' => $search ?? ''
                                ]) ?>
                            </div>



                            <div class="col-md-2">
                                <?= admin_component('page_length_select', [
                                    'lengths' => $pageLengths ?? [15, 50, 100, 200],
                                    'current_page_length' => $pageLength
                                ]) ?>
                            </div>

                            <div class="col-md-4 pt-md-1 pt-0">
                                <?= admin_component('button', [
                                    'label' => 'Go',
                                    'class' => 'mt-md-4 mt-0',
                                    'submit' => true
                                ]) ?>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>



        <div class="col-12">
            <div class="p-0">
                <div class="table-responsive rounded-3 shadow p-0">
                    <table class="table table-bordered table-hover text-nowrap m-0">
                        <thead>
                            <tr class="table-dark">
                                <th>
                                    #
                                </th>
                                <th>Action</th>
                                <th>
                                    Avatar
                                </th>
                                <th>
                                    <?= $userIdLabel ?>
                                </th>
                                <th>
                                    <?= $userNameLabel ?>
                                </th>
                                <th>
                                    <?= $sponsorIdLabel ?>
                                </th>
                                <th>
                                    <?= $sponsorNameLabel ?>
                                </th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>Status</th>
                                <?php if (!$passwordHashEnabled): ?>
                                    <th>Account Password</th>
                                <?php endif; ?>
                                <?php if (!$tpinHashEnabled): ?>
                                    <th><?= $tpinLabel ?></th>
                                <?php endif; ?>
                                <th>Total Investment</th>
                                <th>Total Earning</th>
                                <th>Total Pending Withdrawal</th>
                                <th>Total Complete Withdrawal</th>
                                <th>Fund Balance</th>
                                <th>Income Balance</th>
                                <th>Joining Date</th>
                                <th>Activation Date</th>
                            </tr>
                        </thead>


                        <tbody id="user_table_body">


                            <?php

                            $i = pager_init_serial_number($pager);


                            if ($hasRecords): ?>
                                <?php foreach ($users as &$user):

                                    $color = $user->status ? 'success' : 'danger';

                                    ?>
                                    <tr class="table-<?= $color ?>">
                                        <td>
                                            <?= ++$i; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= str_replace('_1', $user->user_id, $userUrl) ?>"><button
                                                    class="btn btn-<?= $color ?> btn-sm">
                                                    <i class="mdi mdi-monitor-eye"></i>
                                                </button>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <img class="user-avatar avatar avatar-xs m-0 rounded-circle"
                                                src="<?= App\Models\UserModel::getAvatar($user); ?>" alt="user-avatar" />
                                        </td>
                                        <td class="fw-bold">
                                            <?= $user->user_id ?>
                                        </td>
                                        <td>
                                            <?= $user->full_name ?>
                                        </td>
                                        <td class="text-center">
                                            <span role="button"
                                                data-hidden-password="<?= $user->sponsor_id ?>">**********</span>
                                        </td>
                                        <td class="text-center">
                                            <span role="button"
                                                data-hidden-password="<?= $user->sponsor_full_name ?>">**********</span>
                                        </td>
                                        <td>
                                            <?= $user->email ?>
                                        </td>
                                        <td>
                                            <?= \App\Libraries\CountryLib::COUNTRIES[$user->country_code] ?? 'N/A' ?>
                                        </td>
                                        <td>
                                            <?php if ($user->status): ?>
                                                <i class="fas fa-circle text-success me-2"></i>
                                                <?= label('user_status_active') ?>
                                            <?php else: ?>
                                                <i class="fas fa-circle text-danger me-2"></i>
                                                <?= label('user_status_inactive') ?>
                                            <?php endif; ?>
                                        </td>

                                        <?php if (!$passwordHashEnabled): ?>
                                            <td class="text-center">
                                                <?php if (!$user->is_password_hashed and $user->password): ?>
                                                    <span role="button" data-hidden-password="<?= $user->password ?>">**********</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>

                                        <?php if (!$tpinHashEnabled): ?>
                                            <td class="text-center">
                                                <?php if (!$user->is_tpin_hashed and $user->tpin): ?>
                                                    <span role="button" data-hidden-password="<?= $user->tpin ?>">**********</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>

                                        <td>
                                            <?= f_amount($user->total_investment) ?>
                                        </td>
                                        <td>
                                            <?= f_amount($user->total_earning) ?>
                                        </td>
                                        <td>
                                            <?= f_amount($user->total_pending_withdrawal) ?>
                                        </td>
                                        <td>
                                            <?= f_amount($user->total_complete_withdrawal) ?>
                                        </td>
                                        <td>
                                            <?= f_amount($user->fund_wallet) ?>
                                        </td>
                                        <td>
                                            <?= f_amount($user->income_wallet) ?>
                                        </td>
                                        <td>
                                            <?= f_date($user->created_at) ?>
                                        </td>
                                        <td>
                                            <?= $user->activated_at ? f_date($user->activated_at) : '' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="table-secondary">
                                    <td class="text-center" colspan="20">0 Records Found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($hasRecords): ?>
                    <div class="table-pagination float-end mt-3">
                        <?= $pager->links(template: 'admin_dashboard') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?php $this->section('script') ?>
<script>
    $(document).ready(function () {
        $('[data-hidden-password]').each(function () {
            var originalContent = $(this).html();
            var hiddenPassword = $(this).attr('data-hidden-password');
            var isPasswordVisible = false;

            $(this).click(function () {
                if (!isPasswordVisible) {
                    if (confirm('Are you sure you want to reveal hidden information?')) {
                        $(this).html(hiddenPassword);
                        isPasswordVisible = true;
                    }
                } else {
                    $(this).html(originalContent);
                    isPasswordVisible = false;
                }
            });
        });
    });
</script>
<?php $this->endSection() ?>