<?php
use App\Models\DepositModel;

$hasRecords = count($deposits) > 0;

$widgets = [
    'Total Pending Deposit' => f_amount(_c($totalPendingDepositSum), shortForm: true, symbol: '$'),
    'Total Complete Deposit' => f_amount(_c($totalCompleteDepositSum), shortForm: true, symbol: '$')
];

?>

<?= $this->extend('user_dashboard/layout/master') ?>

<?php $this->section('style') ?>
<style>
    img.deposit-receipt {
        max-width: 100%;
        width: 250px;
        height: 250px;
        object-fit: cover;
        margin-bottom: 10px;
    }
</style>
<?php $this->endSection() ?>

<?= $this->section('slot') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <?php foreach ($widgets as $title => $value): ?>
                    <div class="col-xl-4">

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

                            <div class="col-md-4">
                                <?= user_component('input', [
                                    'name' => 'search',
                                    'label' => 'Search',
                                    'placeholder' => "Track Id / UTR",
                                    'value' => $search ?? ''
                                ]) ?>
                            </div>

                            <div class="col-md-3">
                                <?= user_component('select', [
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

                            <div class="col-md-2">
                                <?= user_component('page_length_select', [
                                    'lengths' => $pageLengths ?? [15, 50, 100, 200],
                                    'current_page_length' => $pageLength ?? 15
                                ]) ?>
                            </div>


                            <div class="col-md-3">
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
                        Deposit Logs (History)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                                <tr class="border-bottom-primary">
                                    <th scope="col">#</th>
                                    <th scope="col">View</th>
                                    <th scope="col">Track Id</th>
                                    <th scope="col">
                                        Amount
                                    </th>
                                    <th scope="col">
                                        Deposit Mode
                                    </th>
                                    <th scope="col">
                                        UTR
                                    </th>
                                    <th scope="col">
                                        Status
                                    </th>
                                    <th scope="col">Deposited At</th>
                                    <th scope="col">Resolved At</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if ($hasRecords): ?>

                                    <?php
                                    $i = pager_init_serial_number($pager);
                                    foreach ($deposits as &$deposit):
                                        ?>
                                        <tr>
                                            <td scope="row">
                                                <?= ++$i; ?>
                                            </td>
                                            <td class="deposit_view_btn text-center">
                                                <button class="btn btn-sm btn-primary px-3"
                                                    onclick="viewDeposit(<?= $deposit->id ?>);">
                                                    <i class="ti-eye text-white me-1"></i> View
                                                </button>
                                            </td>
                                            <td>
                                                <?= $deposit->track_id ?>
                                            </td>
                                            <td class="fw-bold">
                                                <?= f_amount(_c($deposit->amount), symbol: '$') ?>
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
                                            <td>
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
                                                <span class="badge rounded-pill badge-<?= $color ?>">
                                                    <?= ucfirst($deposit->status) ?>
                                                </span>
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
        'id' => 'user_deposit_modal',
        'size' => 'lg',
        'center' => true
    ]) ?>
</div>

<?= $this->endSection() ?>


<?php $this->section('script') ?>
<script>
    function viewDeposit(depositId) {
        $.ajax({
            url: '<?= current_url() ?>',
            method: 'POST',
            data: { action: 'get_user_deposit', deposit_id: depositId, ...csrf_data() },
            beforeSend: () => {
                disable_form('.deposit_view_btn');
            },
            complete: () => {
                enable_form('.deposit_view_btn');
            },
            success: (res, textStatus, xhr) => {
                <?= !isProduction() ? 'console.log(res);' : '' ?>
                if (xhr.status === 200) {
                    if (res.track_id && $('#user_deposit_modal_title').length > 0)
                        $('#user_deposit_modal_title').html(`Deposit : ${res.track_id}`);
                    if (res.view && $('#user_deposit_modal_body').length > 0) {
                        $('#user_deposit_modal_body').html(res.view);
                        showModal('#user_deposit_modal');
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