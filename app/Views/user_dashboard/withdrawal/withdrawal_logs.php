<?php

use App\Models\WithdrawalModel;

$hasRecords = count($wds) > 0;

?>


<?= $this->extend('user_dashboard/layout/master') ?>



<?= $this->section('style') ?>
<style>
    .wd_view_btn {
        display: flex;
        justify-content: center;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('slot') ?>
<div class="container-fluid">
    <div class="row">



        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET">
                        <div class="row">

                            <div class="col-md-5">
                                <?= user_component('input', [
                                    'name' => 'search',
                                    'label' => 'Search',
                                    'placeholder' => "Track Id",
                                    'value' => $search ?? ''
                                ]) ?>
                            </div>

                            <div class="col-md-2">
                                <?= user_component('page_length_select', [
                                    'lengths' => $pageLengths ?? [15, 50, 100, 200],
                                    'current_page_length' => $pageLength ?? 15
                                ]) ?>
                            </div>


                            <div class="col-md-4">
                                <div class="mt-0 mt-md-2">
                                    <?= user_component('button', [
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
            <div class="card">
                <div class="card-header">
                    <h5>
                        Payouts Logs (History)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                                <tr class="border-bottom-primary">
                                    <th scope="col">#</th>
                                    <th scope="col" width="1%">Action</th>
                                    <th scope="col">Track Id</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Charges</th>
                                    <th scope="col">Net Amount</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if ($hasRecords): ?>

                                    <?php
                                    $i = pager_init_serial_number($pager);
                                    foreach ($wds as &$wd):
                                        ?>
                                        <tr>
                                            <td scope="row">
                                                <?= ++$i; ?>
                                            </td>
                                            <td class="wd_view_btn">
                                                <button class="btn btn-sm btn-primary px-3 py-1"
                                                    onclick="viewWithdrawal(<?= $wd->id ?>);">
                                                    <i class="ti-eye text-white me-1"></i> View
                                                </button>
                                            </td>
                                            </td>
                                            <td>
                                                <?= $wd->track_id ?>
                                            </td>
                                            <td>
                                                <?= f_amount(_c($wd->amount), isUser: true) ?>
                                            </td>
                                            <td>
                                                <?= f_amount(_c($wd->charges), isUser: true) ?>
                                            </td>
                                            <td class="fw-bold">
                                                <?= f_amount(_c($wd->net_amount), isUser: true) ?>
                                            </td>
                                            <td class="text-uppercase">
                                                <?php
                                                if ($wd->status === WithdrawalModel::WD_STATUS_PENDING) {
                                                    $color = 'warning';
                                                } else if ($wd->status === WithdrawalModel::WD_STATUS_COMPLETE) {
                                                    $color = 'success';
                                                } else if ($wd->status === WithdrawalModel::WD_STATUS_CANCELLED) {
                                                    $color = 'secondary';
                                                } else {
                                                    $color = 'danger';
                                                }
                                                ?>
                                                <span class="badge rounded-pill badge-<?= $color ?>">
                                                    <?= ucfirst($wd->status) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= f_date($wd->created_at) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="20" class="text-center">0 records found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($hasRecords): ?>
                        <div class="fit-content float-end m-3">
                            <?= $pager->links(template: 'user_dashboard') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <?= user_component('empty_modal', [
        'id' => 'user_wd_modal',
        'size' => 'lg',
        'center' => true,
        'static' => true
    ]) ?>
</div>
<?php $this->endSection() ?>



<?php if ($hasRecords): ?>
    <?php $this->section('script') ?>
    <script>
        function viewWithdrawal(wId) {
            $.ajax({
                url: '<?= current_url() ?>',
                method: 'POST',
                data: { action: 'get_user_withdrawal', withdrawal_id: wId, ...csrf_data() },
                beforeSend: () => {
                    disable_form('.wd_view_btn');
                },
                complete: () => {
                    enable_form('.wd_view_btn');
                },
                success: (res, textStatus, xhr) => {
                    <?= !isProduction() ? 'console.log(res);' : '' ?>
                    if (xhr.status === 200) {
                        if (res.track_id && $('#user_wd_modal_title').length > 0)
                            $('#user_wd_modal_title').html(`Withdrawal : ${res.track_id}`);
                        if (res.view && $('#user_wd_modal_body').length > 0) {
                            $('#user_wd_modal_body').html(res.view);
                            showModal('#user_wd_modal');
                        }
                    }
                },
                error: (xhr) => {
                    <?= !isProduction() ? 'console.log(xhr);' : '' ?>
                    var res = xhr.responseJSON || xhr.responseText;
                    sAlertServerError1();
                },
            });
        }
    </script>
    <?php $this->endSection() ?>
<?php endif; ?>