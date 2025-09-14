<?php
use App\Services\WalletService;


$hasRecords = count($incomes) > 0;

$walletUrl = $hasRecords ? route('user.wallet.transactions', WalletService::getWalletSlug('roi')) : '';


?>

<?= $this->extend('user_dashboard/layout/master') ?>

<?= $this->section('slot') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <?= user_component('input', [
                                    'name' => 'track_id',
                                    'label' => 'Transaction Track Id',
                                    'placeholder' => "Search Track Id",
                                    'value' => $track_id ?? ''
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
            <div class="card">
                <div class="card-header">
                    <h5>
                        Self ROI Income Table
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                                <tr class="border-bottom-primary">
                                    <th scope="col">#</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Transaction Track Id</th>
                                    <th scope="col">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($hasRecords): ?>

                                    <?php
                                    $i = pager_init_serial_number($pager);
                                    foreach ($incomes as &$income):
                                        ?>
                                        <tr>
                                            <td scope="row">
                                                <?= ++$i; ?>
                                            </td>
                                            <td class="fw-bold">
                                                <?= f_amount($income->amount) ?>
                                            </td>
                                            <td>
                                                <a href="<?= "$walletUrl?track_id=$income->track_id" ?>">
                                                    <?= $income->track_id ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?= f_date($income->created_at) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="20" class="text-center">0 records found</td>
                                    </tr>
                                <?php endif; ?>
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <div class="fit-content float-end m-3">
                            <?= $pager->links(template: 'user_dashboard') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>