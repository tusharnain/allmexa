<?php

$userIdLabel = label('user_id');
$userNameLabel = label('user_name');

$hasRecords = (isset($transactions) and is_array($transactions) and count($transactions) > 0);

$wallet_fields = \App\Services\WalletService::WALLETS;
$w_array = ['All' => ''];
foreach ($wallet_fields as $index => &$w) {
    $key = wallet_label($w);
    $w_array[$key] = $w;
}
?>


<?= $this->extend('admin_dashboard/_partials/app') ?>

<?= $this->section('style') ?>
<style>
    .text-bold {
        text-shadow: 0px 1px, 1px 0px, 1px 1px;
    }
</style>
<?= $this->endSection() ?>



<?= $this->section('slot') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card rounded-3">
                <div class="card-body">
                    <div>
                        <form method="GET">
                            <div class="row">
                                <div class="col-md-4 col-lg-3">
                                    <?= admin_component('input', [
                                        'name' => 'search',
                                        'label' => 'Search',
                                        'placeholder' => "Track Id / $userIdLabel / $userNameLabel",
                                        'value' => $search ?? ''
                                    ]) ?>
                                </div>

                                <div class="col-md-4 col-lg-3">
                                    <?= admin_component('select', [
                                        'name' => 'wallet',
                                        'label' => 'Wallet',
                                        'options' => $w_array,
                                        'select' => $wallet ?? ''
                                    ]) ?>
                                </div>

                                <div class="col-md-4 col-lg-3">
                                    <?= admin_component('select', [
                                        'name' => 'type',
                                        'label' => 'Type',
                                        'options' => ['All' => '', 'Credit' => 'credit', 'Debit' => 'debit'],
                                        'select' => $type ?? ''
                                    ]) ?>
                                </div>

                                <div class="col-md-4 col-lg-3">
                                    <?= admin_component('input', [
                                        'type' => 'number',
                                        'name' => 'from_amount',
                                        'label' => 'From Amount',
                                        'placeholder' => "Enter Amount",
                                        'value' => $fromAmount ?? ''
                                    ]) ?>
                                </div>

                                <div class="col-md-4 col-lg-3">
                                    <?= admin_component('input', [
                                        'type' => 'number',
                                        'name' => 'to_amount',
                                        'label' => 'To Amount',
                                        'placeholder' => "Enter Amount",
                                        'value' => $toAmount ?? ''
                                    ]) ?>
                                </div>

                                <div class="col-md-4 col-lg-3">
                                    <?= admin_component('input', [
                                        'type' => 'date',
                                        'name' => 'from_date',
                                        'label' => 'From Date',
                                        'value' => $fromDate ?? ''
                                    ]) ?>
                                </div>

                                <div class="col-md-4 col-lg-3">
                                    <?= admin_component('input', [
                                        'type' => 'date',
                                        'name' => 'to_date',
                                        'label' => 'To Date',
                                        'value' => $toDate ?? ''
                                    ]) ?>
                                </div>

                                <div class="col-md-4 col-lg-3">
                                    <?= admin_component('page_length_select', [
                                        'lengths' => $pageLengths ?? [15, 50, 100, 200],
                                        'current_page_length' => $pageLength
                                    ]) ?>
                                </div>
                            </div>

                            <?= admin_component('button', [
                                'label' => 'Apply',
                                'class' => 'float-end',
                                'submit' => true
                            ]) ?>
                        </form>

                    </div>
                </div>
            </div>
        </div>



        <div class="col-12">
            <div class="p-0">
                <div class="table-responsive rounded-3 shadow p-0">
                    <table class="table table-bordered table-hover text-nowrap m-0">
                        <thead>
                            <tr class="table-dark">
                                <th>#</th>
                                <th>Track Id</th>
                                <th>Amount</th>
                                <th>Wallet</th>
                                <th>
                                    <?= $userIdLabel ?>
                                </th>
                                <th>
                                    <?= $userNameLabel ?>
                                </th>
                                <th>Admin Remarks</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>


                        <tbody id="user_table_body">


                            <?php

                            $i = pager_init_serial_number($pager);


                            if ($hasRecords): ?>
                                <?php foreach ($transactions as &$txn):

                                    $color = $txn->type == 'credit' ? 'success' : 'danger';

                                    $remarks = ($txn->details and ($details = json_decode($txn->details) and $details->remarks))
                                        ? $details->remarks : null;
                                    ?>
                                    <tr scope="row" class="table-<?= $color ?>">
                                        <td><?= ++$i ?></td>
                                        <td><?= $txn->track_id ?></td>
                                        <td>
                                            <span class="text-bold text-<?= $color ?>">
                                                <?= $txn->type == 'credit' ? '+' : '-' ?>
                                                <?= f_amount($txn->amount) ?>
                                            </span>
                                        </td>
                                        <td><?= wallet_label($txn->wallet) ?></td>
                                        <td><?= $txn->user_user_id ?></td>
                                        <td><?= escape($txn->user_full_name) ?></td>
                                        <td class="text-center">
                                            <?php if ($remarks and !empty($remarks)): ?>
                                                <button class="btn btn-sm btn-primary w-100"
                                                    onclick="showRemarks(`<?= nl2br($remarks) ?>`);">
                                                    View
                                                    <i class="mdi mdi-eye ms-1"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= f_date($txn->created_at) ?></td>
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
    <?= admin_component('empty_modal', ['id' => 'txn_remark_modal', 'title' => 'Transaction Remarks']) ?>
</div>
<?= $this->endSection() ?>

<?php $this->section('script') ?>
<script>
    const modalSelector = '#txn_remark_modal';

    function showRemarks(remarks) {
        $(modalSelector + '_body').html(`<h6>${remarks}</h6>`);
        $(modalSelector).modal('show');
    }
</script>
<?php $this->endSection() ?>