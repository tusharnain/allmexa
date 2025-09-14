<?php

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
                                                    <?= f_amount(_c($txn->amount), isUser: true) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= f_amount(_c($txn->balance), isUser: true) ?>
                                            </td>
                                            <td class="description-td">
                                                <?php echo $wsc::parseTransationDetailsJSON($txn, $color); ?>
                                            </td>
                                            <td>
                                                <?= f_date($txn->created_at) ?>
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
    const tooltipApi = '<?= route('user.wallet.transactions') ?>';
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