<?php

use App\Enums\WalletTransactionCategory as TxnCat;

$wsc = App\Services\WalletService::class;
$hasRecords = count($transactions) > 0;

$userIdLabel = label('user_id');
$userNameLabel = label('user_name');

?>

<?= $this->extend('user_dashboard/layout/master') ?>

<?php $this->section('style') ?>
<style>
    table tr td:first-child {
        padding-left: 20px;
    }

    @media only screen and (max-width: 770px) {
        .search-btn {
            float: right;
        }
    }
</style>
<?php $this->endSection() ?>


<?= $this->section('slot') ?>
<div class="container-fluid">
    <div class="row">

        <div class="col-12 mb-0 mb-sm-4">

            <div class="card shining-card">
                <div class="card-body">
                    <span class="fs-5 me-2">
                        <?= wallet_label($wallet) ?> Balance
                    </span>
                    <svg width="36" height="35" viewBox="0 0 36 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3.86124 21.6224L11.2734 16.8577C11.6095 16.6417 12.041 16.6447 12.3718 16.8655L18.9661 21.2663C19.2968 21.4871 19.7283 21.4901 20.0644 21.2741L27.875 16.2534"
                            stroke="#BFBFBF" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path
                            d="M26.7847 13.3246L31.6677 14.0197L30.4485 18.7565L26.7847 13.3246ZM30.2822 19.4024C30.2823 19.4023 30.2823 19.4021 30.2824 19.402L30.2822 19.4024ZM31.9991 14.0669L31.9995 14.0669L32.0418 13.7699L31.9995 14.0669C31.9994 14.0669 31.9993 14.0669 31.9991 14.0669Z"
                            fill="#BFBFBF" stroke="#BFBFBF"></path>
                    </svg>
                    <div class="pt-3">
                        <h4 class="counter" style="visibility: visible;">
                            <?= wallet_famount(_c($walletBalance), wallet: $wallet) ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET">
                        <div class="row">

                            <div class="col-md-3">
                                <?= user_component('input', [
                                    'name' => 'track_id',
                                    'label' => 'Track Id',
                                    'placeholder' => "Search Track Id",
                                    'value' => $track_id ?? ''
                                ]) ?>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        Transaction
                                    </label>
                                    <select class="form-select" name="txn_category">
                                        <option value <?= empty($_GET['txn_category']) ? 'selected' : '' ?>>All</option>
                                        <option value="<?= TxnCat::SPONSOR_LEVEL_INCOME ?>" <?= ($_GET['txn_category'] ?? '') == TxnCat::SPONSOR_LEVEL_INCOME ? 'selected' : '' ?>>
                                            <?= strtoupper(str_replace('_', ' ', TxnCat::SPONSOR_LEVEL_INCOME)) ?>
                                        </option>
                                        <option value="<?= TxnCat::SPONSOR_ROI_LEVEL_INCOME ?>"
                                            <?= ($_GET['txn_category'] ?? '') == TxnCat::SPONSOR_ROI_LEVEL_INCOME ? 'selected' : '' ?>>
                                            <?= strtoupper(str_replace('_', ' ', TxnCat::SPONSOR_ROI_LEVEL_INCOME)) ?>
                                        </option>
                                        <option value="<?= TxnCat::ROI ?>" <?= ($_GET['txn_category'] ?? '') == TxnCat::ROI ? 'selected' : '' ?>>
                                            <?= strtoupper(str_replace('_', ' ', TxnCat::ROI)) ?>
                                        </option>
                                        <option value="<?= TxnCat::SALARY ?>" <?= ($_GET['txn_category'] ?? '') == TxnCat::SALARY ? 'selected' : '' ?>>
                                            <?= strtoupper(str_replace('_', ' ', TxnCat::SALARY)) ?>
                                        </option>

                                    </select>
                                </div>
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
                        <?= wallet_label($wallet) ?> Transactions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                                <tr class="border-bottom-primary">
                                    <th scope="col">#</th>
                                    <th scope="col">Track Id</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Balance</th>
                                    <th scope="col" class="description-td">Description</th>
                                    <th scope="col">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($hasRecords): ?>

                                    <?php
                                    $i = pager_init_serial_number($pager);
                                    foreach ($transactions as &$txn):

                                        $color = $txn->type == 'credit' ? 'success' : 'danger';

                                        ?>
                                        <tr>
                                            <td scope="row">
                                                <?= ++$i; ?>
                                            </td>
                                            <td>
                                                <?= $txn->track_id ?>
                                            </td>
                                            <td class="text-uppercase text-<?= $color ?>">
                                                <?= $txn->type ?>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-<?= $color ?>">
                                                    <?= $txn->type == 'credit' ? '+' : '-' ?>
                                                    <?= wallet_famount(_c($txn->amount), wallet: $wallet) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= wallet_famount(_c($txn->balance), wallet: $wallet) ?>
                                            </td>
                                            <td class="description-td">
                                                <?php echo $wsc::parseTransationDetailsJSON($txn, $color); ?>
                                            </td>
                                            <td>
                                                <?= date('d M, Y', strtotime($txn->created_at)) ?>
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
<?php $this->endSection() ?>


<?php $this->section('script') ?>

<script>
    const tooltipApi = '<?= current_url() ?>';
    const loadingContent = '<h5 class="p-3 m-0">Loading <i class="fas fa-spinner fa-spin ms-1"></i></h5>';
    tippy('[data-tooltip-track-id]', {
        content: loadingContent,
        allowHTML: true,
        animation: 'scale',
        followCursor: true,
        hideOnClick: false,
        inertia: true,
        interactive: true,
        theme: 'custom-theme',
        appendTo: () => document.body,
        onCreate(instance) {
            // Setup our own custom state properties
            instance._isFetching = false;
            instance._error = null;
        },
        onShow(instance) {
            if (instance._isFetching || instance._error) {
                return;
            }

            instance._isFetching = true;

            const elem = $(instance.reference);

            const tooltipTrackId = elem.attr('data-tooltip-track-id');
            const tooltipAction = elem.attr('data-tooltip-action');

            $.ajax({
                url: tooltipApi,
                method: 'POST',
                data: { action: tooltipAction, data_id: tooltipTrackId, ...csrf_data() },
                success: function (res) {
                    instance.setContent(res.html);
                },
                complete: function () {
                    instance._isFetching = false;
                },
                error: function () {
                    // location.reload();
                }
            });


        },
        onHidden(instance) {
            instance.setContent(loadingContent);
            // Unset these properties so new network requests can be initiated
            instance._error = null;
        },

    });
</script>
<?php $this->endSection() ?>