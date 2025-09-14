<?php

$hasRecords = count($topups) > 0;
$userIdLabel = label('user_id');
$userNameLabel = label('user_name');

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

                            <div class="col-md-5">
                                <?= user_component('input', [
                                    'name' => 'search',
                                    'label' => 'Search',
                                    'placeholder' => "Track Id / $userIdLabel / $userNameLabel / Amount",
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
                        Topup Logs (History)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                                <tr class="border-bottom-primary">
                                    <th scope="col">#</th>
                                    <th scope="col">Track Id</th>
                                    <th scope="col">
                                        <?= $userIdLabel ?>
                                    </th>
                                    <th scope="col">
                                        <?= $userNameLabel ?>
                                    </th>
                                    <th scope="col">
                                        Amount
                                    </th>
                                    <th scope="col">
                                        Type
                                    </th>
                                    <th scope="col">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if ($hasRecords): ?>

                                    <?php
                                    $i = pager_init_serial_number($pager);
                                    foreach ($topups as &$topup):
                                        ?>
                                        <tr>
                                            <td scope="row">
                                                <?= ++$i; ?>
                                            </td>
                                            <td>
                                                <?= $topup->track_id ?>
                                            </td>
                                            <td>
                                                <?= $topup->userId ?>
                                                <?php if ($topup->user_id === user('id')): ?>
                                                    <span class="text-primary fw-bold">(SELF)</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="fw-bold">
                                                    <?= escape($topup->userFullName) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= f_amount(_c($topup->amount), symbol: '$') ?>
                                            </td>
                                            <td>
                                                <?= strtoupper($topup->type) ?>
                                            </td>
                                            <td>
                                                <?= f_date($topup->created_at) ?>
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
</div>

<?= $this->endSection() ?>