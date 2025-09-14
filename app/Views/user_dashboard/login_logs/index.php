<?php

$hasRecords = count($logs) > 0;


$widgets = [
    'Total Logins' => $totalSuccessLogins,
    'Total Failed Logins' => $totalFailedLogins
];


?>

<?= $this->extend('user_dashboard/layout/master') ?>


<?= $this->section('slot') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <?php foreach ($widgets as $title => $value): ?>
                    <div class="col-xl-4">
                        <div class="card small-widget">
                            <div class="card-body primary">
                                <span class="f-light">
                                    <h6>
                                        <?= $title ?>
                                    </h6>
                                </span>
                                <div class="d-flex align-items-end gap-1">
                                    <h4 class="mt-2">
                                        <?= $value ?>
                                    </h4>
                                </div>
                                <div class="bg-gradient">
                                    <svg class="stroke-icon svg-fill">
                                        <use href="<?= user_asset('svg/icon-sprite.svg#stroke-user') ?>"></use>
                                    </svg>
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

                            <div class="col-md-5">
                                <?= user_component('input', [
                                    'name' => 'search',
                                    'label' => 'Search',
                                    'placeholder' => "IP Address / OS / Browser",
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
                        Login Logs (History)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                                <tr class="border-bottom-primary">
                                    <th scope="col">#</th>
                                    <th scope="col">IP Address</th>
                                    <th>OS</th>
                                    <th scope="col">Browser</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Message</th>
                                    <th scope="col">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($hasRecords): ?>

                                    <?php
                                    $i = pager_init_serial_number($pager);
                                    foreach ($logs as &$log):

                                        $color = $log->status === \App\Models\UserModel::LoginLogStatus_SUCCESS ? 'success' : 'danger';

                                        ?>
                                        <tr>
                                            <td scope="row">
                                                <?= ++$i; ?>
                                            </td>
                                            <td>
                                                <?= escape($log->ip_address) ?>
                                            </td>
                                            <td>
                                                <?= escape($log->os) ?>
                                            </td>
                                            <td>
                                                <?= escape($log->browser) ?>
                                            </td>
                                            <td class="text-uppercase text-center">
                                                <span class="badge rounded-pill badge-<?= $color ?>">
                                                    <?= $log->status ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($log->message): ?>
                                                    <?= $log->message ?>
                                                <?php else: ?>
                                                    <div class="text-secondary">N/A</div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= f_date($log->created_at, format: 'jS M Y, h:i:s A') ?>
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