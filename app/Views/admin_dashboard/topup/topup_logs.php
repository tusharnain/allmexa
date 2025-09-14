<?php
use App\Twebsol\Plans;

$userIdLabel = label('user_id');
$userNameLabel = label('user_name');
$userLabel = label('user');


$hasRecords = (isset($topups) and is_array($topups) and count($topups) > 0);

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
                            <div class="col-md-6 col-lg-3">
                                <?= admin_component('input', [
                                    'name' => 'search',
                                    'label' => 'Search',
                                    'placeholder' => "Track Id / $userIdLabel / $userNameLabel / Amount / Topup by $userLabel",
                                    'value' => $search ?? ''
                                ]) ?>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <?= admin_component('page_length_select', [
                                    'lengths' => $pageLengths ?? [15, 50, 100],
                                    'current_page_length' => $pageLength
                                ]) ?>
                            </div>

                            <div class="col-12">
                                <?= admin_component('checkbox', [
                                    'label' => "Filter topups made by Admin.",
                                    'name' => 'by_admin',
                                    'checked' => $byAdmin ?? false
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


        <div class="col-12">
            <div class="p-0">
                <div class="table-responsive rounded-3 shadow p-0">
                    <table class="table table-bordered table-hover text-nowrap m-0">
                        <thead>
                            <tr class="table-dark">
                                <th>#</th>
                                <th>Track Id</th>
                                <th><?= $userIdLabel ?></th>
                                <th><?= $userNameLabel ?></th>
                                <th>Amount</th>
                                <th class="text-center">Topup By</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>


                        <tbody id="user_table_body">

                            <?php

                            $i = pager_init_serial_number($pager);

                            if ($hasRecords): ?>
                                <?php foreach ($topups as &$topup): ?>
                                    <tr class="bg-white">
                                        <td>
                                            <?= ++$i; ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= $topup->track_id ?>
                                        </td>
                                        <td>
                                            <?= $topup->user_user_id ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= $topup->user_full_name ?>
                                        </td>
                                        <td class="fw-bold">
                                            <?= f_amount($topup->amount) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($topup->topup_by_user_user_id): ?>
                                                <?= "$topup->topup_by_user_full_name ($topup->topup_by_user_user_id)" ?>
                                            <?php else: ?>
                                                <span class="btn btn-primary btn-sm">ADMIN</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= f_date($topup->created_at) ?>
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