<?php

use App\Models\WithdrawalModel;

$hasRecords = (isset ($withdrawals) and is_array($withdrawals) and count($withdrawals) > 0);

if ($hasRecords) {
    $singleDepositUrl = route('admin.withdrawals.userSingleWithdrawal', '_1');
}


?>

<?= $this->extend('admin_dashboard/_partials/app') ?>


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
                            <div class="col-lg-4">
                                <?= admin_component('input', [
                                    'name' => 'search',
                                    'label' => 'Search',
                                    'placeholder' => "Track Id / $userIdLabel / $userNameLabel",
                                    'value' => $search ?? ''
                                ]) ?>
                            </div>


                            <div class="col-lg-2">
                                <?= admin_component('select', [
                                    'name' => 'status',
                                    'label' => 'Status',
                                    'options' => [
                                        'All' => 'all',
                                        'Pending' => WithdrawalModel::WD_STATUS_PENDING,
                                        'Rejected' => WithdrawalModel::WD_STATUS_REJECT,
                                        'Complete' => WithdrawalModel::WD_STATUS_COMPLETE,
                                        'Cancelled' => WithdrawalModel::WD_STATUS_CANCELLED,
                                    ],
                                    'select' => $status
                                ]) ?>
                            </div>


                            <div class="col-lg-2">
                                <?= admin_component('page_length_select', [
                                    'lengths' => $pageLengths ?? [15, 50, 100, 200],
                                    'current_page_length' => $pageLength
                                ]) ?>
                            </div>

                            <div class="col-lg-4 pt-md-1 pt-0">
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
                                    Track Id
                                </th>
                                <th>
                                    <?= label('user_id') ?>
                                </th>
                                <th>
                                    <?= label('user_name') ?>
                                </th>
                                <th>Bank Name</th>
                                <th>Account Holder Name</th>
                                <th>Account Number</th>
                                <th>IFSC Code</th>
                                <th>Amount</th>
                                <th>Net Amount</th>
                                <th>Charges</th>
                                <th>Status</th>
                                <th>Withdrawal At</th>
                                <th>Completed/Rejected At</th>
                            </tr>
                        </thead>


                        <tbody id="user_table_body">


                            <?php

                            $i = pager_init_serial_number($pager);

                            if ($hasRecords): ?>

                                <?php

                                foreach ($withdrawals as &$wd):
                                    $color = 'secondary';
                                    if ($wd->status === WithdrawalModel::WD_STATUS_COMPLETE) {
                                        $color = 'success';
                                    } else if ($wd->status === WithdrawalModel::WD_STATUS_REJECT) {
                                        $color = 'danger';
                                    } else if ($wd->status === WithdrawalModel::WD_STATUS_PENDING) {
                                        $color = 'warning';
                                    }
                                    ?>
                                    <tr class="table-<?= $color ?>">
                                        <td>
                                            <?= ++$i; ?>
                                        </td>
                                        <td>
                                            <a href="<?= str_replace('_1', $wd->track_id, $singleDepositUrl) ?>">
                                                <button class="btn btn-<?= $color ?> btn-sm">
                                                    <?php if ($wd->status === WithdrawalModel::WD_STATUS_PENDING): ?>
                                                        <i class="mdi mdi-message-reply-text me-1"></i> Resolve
                                                    <?php else: ?>
                                                        <i class="mdi mdi-monitor me-1"></i> View
                                                    <?php endif; ?>
                                                </button>
                                            </a>
                                        </td>
                                        <td class="fw-bold">
                                            <?= $wd->track_id ?>
                                        </td>
                                        <td>
                                            <?= $wd->user_user_id ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= $wd->user_full_name ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= $wd->bank_name ?? '' ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= $wd->account_holder_name ?? '' ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= $wd->account_number ?? '' ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= $wd->bank_ifsc ?? '' ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= f_amount($wd->amount) ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= f_amount($wd->net_amount) ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= f_amount($wd->charges) ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-<?= $color ?>">
                                                <?= ucfirst($wd->status) ?>
                                            </button>
                                        </td>
                                        <td>
                                            <?= f_date($wd->created_at) ?>
                                        </td>
                                        <td>
                                            <?= $wd->admin_resolution_at ? f_date($wd->admin_resolution_at) : 'N/A' ?>
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