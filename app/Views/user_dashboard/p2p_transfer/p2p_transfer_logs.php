<?php

$hasRecords = count($p2ps) > 0;
$userIdLabel = label('user_id');
$userNameLabel = label('user_name');

$widgets = [
    'Total Amount Transferred' => f_amount($totalAmountTransferred, shortForm: true),
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
                        <div class="card shining-card">
                            <div class="card-body">
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

                            <div class="col-md-5">
                                <?= user_component('input', [
                                    'name' => 'search',
                                    'label' => 'Search',
                                    'placeholder' => "$userIdLabel / $userNameLabel / Transaction Track Id",
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
                        P2P Transaction Logs (History)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                                <tr class="border-bottom-primary">
                                    <th scope="col">#</th>
                                    <th scope="col">
                                        <?= $userIdLabel ?>
                                    </th>
                                    <th>
                                        <?= $userNameLabel ?>
                                    </th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Wallet</th>
                                    <th scope="col">Transaction Track Id</th>
                                    <th scope="col">Remarks</th>
                                    <th scope="col">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($hasRecords): ?>

                                    <?php
                                    $i = pager_init_serial_number($pager);
                                    foreach ($p2ps as &$p2p):
                                        ?>
                                        <tr>
                                            <td scope="row">
                                                <?= ++$i; ?>
                                            </td>
                                            <td class="fw-bold">
                                                <?= $p2p->receiverUserId ?>
                                            </td>
                                            <td class="fw-bold">
                                                <?= $p2p->receiverUserFullName ?>
                                            </td>
                                            <td>
                                                <?= f_amount($p2p->amount) ?>
                                            </td>
                                            <td>
                                                <?= wallet_label($p2p->wallet) ?>
                                            </td>
                                            <td>
                                                <?= $p2p->senderTransactionTrackId ?>
                                            </td>
                                            <td class="description-td">
                                                <?= $p2p->sender_remarks ? escape($p2p->sender_remarks) : '' ?>
                                            </td>
                                            <td>
                                                <?= f_date($p2p->created_at) ?>
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