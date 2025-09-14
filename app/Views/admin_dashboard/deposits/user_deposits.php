<?php

use App\Models\DepositModel;

$hasRecords = (isset ($deposits) and is_array($deposits) and count($deposits) > 0);

if ($hasRecords) {
    $singleDepositUrl = route('admin.deposits.userSingleDeposit', '_1');
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
                                    'placeholder' => "Track Id / $userIdLabel / $userNameLabel / UTR",
                                    'value' => $search ?? ''
                                ]) ?>
                            </div>


                            <div class="col-lg-2">
                                <?= admin_component('select', [
                                    'name' => 'status',
                                    'label' => 'Status',
                                    'options' => [
                                        'All' => 'all',
                                        'Pending' => DepositModel::DEPOSIT_STATUS_PENDING,
                                        'Rejected' => DepositModel::DEPOSIT_STATUS_REJECT,
                                        'Complete' => DepositModel::DEPOSIT_STATUS_COMPLETE,
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
                                <th>Amount</th>
                                <th>Deposit Mode</th>
                                <th>UTR</th>
                                <th>Status</th>
                                <th>Deposited At</th>
                                <th>Completed/Rejected At</th>
                            </tr>
                        </thead>


                        <tbody id="user_table_body">


                            <?php

                            $i = pager_init_serial_number($pager);

                            if ($hasRecords): ?>

                                <?php

                                foreach ($deposits as &$deposit):
                                    $color = 'secondary';
                                    if ($deposit->status === DepositModel::DEPOSIT_STATUS_COMPLETE) {
                                        $color = 'success';
                                    } else if ($deposit->status === DepositModel::DEPOSIT_STATUS_REJECT) {
                                        $color = 'danger';
                                    }
                                    ?>
                                    <tr class="table-<?= $color ?>">
                                        <td>
                                            <?= ++$i; ?>
                                        </td>
                                        <td>
                                            <a href="<?= str_replace('_1', $deposit->track_id, $singleDepositUrl) ?>">
                                                <button class="btn btn-<?= $color ?> btn-sm">
                                                    <?php if ($deposit->status === DepositModel::DEPOSIT_STATUS_PENDING): ?>
                                                        <i class="mdi mdi-message-reply-text me-1"></i> Resolve
                                                    <?php else: ?>
                                                        <i class="mdi mdi-monitor me-1"></i> View
                                                    <?php endif; ?>
                                                </button>
                                            </a>
                                        </td>
                                        <td class="fw-bold">
                                            <?= $deposit->track_id ?>
                                        </td>
                                        <td>
                                            <?= $deposit->user_user_id ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= $deposit->user_full_name ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= f_amount($deposit->amount) ?>
                                        </td>
                                        <td>
                                            <?php
                                            $mid = $deposit->deposit_mode_id;

                                            if (!($modeName = memory('dpmd_' . $mid))) {
                                                $modeName = $deposit_model->getDepositNameFromIdPk($mid);
                                                memory('dpmd_' . $mid, $modeName);
                                            }
                                            echo $modeName;
                                            ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= escape($deposit->utr) ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($deposit->status === DepositModel::DEPOSIT_STATUS_PENDING) {
                                                $color = 'warning';
                                            } else if ($deposit->status === DepositModel::DEPOSIT_STATUS_COMPLETE) {
                                                $color = 'success';
                                            } else {
                                                $color = 'danger';
                                            }
                                            ?>
                                            <button class="btn btn-sm btn-<?= $color ?>">
                                                <?= ucfirst($deposit->status) ?>
                                            </button>
                                        </td>
                                        <td>
                                            <?= f_date($deposit->created_at) ?>
                                        </td>
                                        <td>
                                            <?= $deposit->admin_resolution_at ? f_date($deposit->admin_resolution_at) : 'N/A' ?>
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