<?php
$userIdLabel = label('user_id');
$userNameLabel = label('user_name');
$hasRecords = (isset ($tickets) and is_array($tickets) and count($tickets) > 0);

if ($hasRecords) {
    $ticketUrl = route('admin.support.ticket', '_1');
}

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


                            <div class="col-md-6">
                                <?= admin_component('input', [
                                    'name' => 'search',
                                    'label' => 'Search',
                                    'placeholder' => "Ticket Id / $userIdLabel / $userNameLabel",
                                    'value' => $search ?? ''
                                ]) ?>
                            </div>



                            <div class="col-md-2">
                                <?= admin_component('page_length_select', [
                                    'lengths' => $pageLengths ?? [15, 50, 100, 200],
                                    'current_page_length' => $pageLength
                                ]) ?>
                            </div>

                            <div class="col-md-4 pt-md-1 pt-0">
                                <?= admin_component('button', [
                                    'label' => 'Go',
                                    'class' => 'mt-md-4 mt-0',
                                    'submit' => true
                                ]) ?>
                            </div>

                        </div>
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
                                <th>
                                    #
                                </th>
                                <th>
                                    Action
                                </th>
                                <th>
                                    Ticket Id
                                </th>
                                <th>
                                    <?= label('user_id') ?>
                                </th>
                                <th>
                                    <?= label('user_name') ?>
                                </th>
                                <th width="40%">
                                    Subject
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Open Date/Time
                                </th>
                                <?php if ($tableStatus === 1): ?>
                                    <th>
                                        Close Date/Time
                                    </th>
                                <?php endif; ?>
                            </tr>
                        </thead>


                        <tbody>


                            <?php

                            $i = pager_init_serial_number($pager);


                            if ($hasRecords): ?>
                                <?php foreach ($tickets as &$ticket):
                                    $color = $ticket->status ? 'secondary' : 'warning';
                                    ?>
                                    <tr class="table-<?= $color ?>">
                                        <td>
                                            <?= ++$i; ?>
                                        </td>
                                        <td>
                                            <a href="<?= str_replace('_1', $ticket->ticket_id, $ticketUrl) ?>">
                                                <button class="btn btn-<?= $color ?> btn-sm">
                                                    <?php if ($ticket->status): ?>
                                                        <i class="mdi mdi-monitor me-1"></i> View
                                                    <?php else: ?>
                                                        <i class="mdi mdi-message-reply-text me-1"></i> Reply
                                                    <?php endif; ?>
                                                </button>
                                            </a>
                                        </td>
                                        <td class="fw-bold">
                                            <?= $ticket->ticket_id ?>
                                        </td>
                                        <td>
                                            <?= $ticket->user_user_id ?>
                                        </td>
                                        <td>
                                            <?= escape($ticket->user_full_name) ?>
                                        </td>
                                        <td class="text-wrap">
                                            <?= escape($ticket->subject) ?>
                                        </td>
                                        <td>
                                            <?= $ticket->status ? 'CLOSE' : 'OPEN' ?>
                                        </td>
                                        <td>
                                            <?= f_date($ticket->created_at) ?>
                                        </td>
                                        <?php if ($tableStatus === 1): ?>
                                            <td>
                                                <?= $ticket->closed_at ? f_date($ticket->closed_at) : '' ?>
                                            </td>
                                        <?php endif; ?>
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