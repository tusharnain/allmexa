<?php

$hasRecords = (isset($tickets) and is_array($tickets) and count($tickets) > 0);

?>

<?= $this->extend('user_dashboard/layout/master') ?>


<?php $this->section('style') ?>
<style>
    .closed-ticket-row {
        background-color: rgba(0, 0, 0, 0.2);
    }

    .description-td {
        min-width: 400px;
        width: 40%;
        text-wrap: wrap;
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

                            <div class="col-md-4 col-xl-2">
                                <?= user_component('input', [
                                    'name' => 'search',
                                    'label' => 'Ticket Id',
                                    'placeholder' => "Search Ticket Id",
                                    'value' => $search ?? ''
                                ]) ?>
                            </div>

                            <div class="col-md-2">
                                <?= user_component('page_length_select', [
                                    'lengths' => $pageLengths ?? [15, 50, 100, 200],
                                    'current_page_length' => $pageLength
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
                        Ticket History
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                                <tr class="border-bottom-primary">
                                    <th scope="col">#</th>
                                    <th scope="col">View</th>
                                    <th scope="col">Ticket id</th>
                                    <th scope="col" width="50%" class="text-wrap">Subject</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Open Date/Time</th>
                                    <th scope="col">Close Date/Time</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if ($hasRecords): ?>

                                    <?php
                                    $i = pager_init_serial_number($pager);
                                    foreach ($tickets as &$ticket):
                                        ?>
                                        <tr class="<?= $ticket->status ? 'closed-ticket-row' : '' ?>">
                                            <td scope="row">
                                                <?= ++$i; ?>
                                            </td>
                                            <td class="ticket_view_btn text-center">
                                                <h5 role="button" onclick="viewTicket(<?= $ticket->id ?>);">
                                                    <i class="ti-eye text-primary"></i>
                                                </h5>
                                            </td>
                                            <td class="fw-bold">
                                                <?= $ticket->ticket_id ?>
                                            </td>
                                            <td class="text-wrap description-td">
                                                <?= $ticket->subject ?>
                                            </td>
                                            <td>
                                                <?= $ticket->status ? 'CLOSED' : 'OPEN' ?>
                                            </td>
                                            <td>
                                                <?= isset($ticket->created_at) ? f_date($ticket->created_at) : '' ?>
                                            </td>
                                            <td>
                                                <?= isset($ticket->closed_at) ? f_date($ticket->closed_at) : '' ?>
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
        'id' => 'user_ticket_modal',
        'size' => 'xl',
        'center' => true
    ]) ?>
</div>

<?= $this->endSection() ?>

<?php $this->section('script') ?>
<script>
    function viewTicket(ticketId) {
        $.ajax({
            url: '<?= route('user.support.ticketHistory') ?>',
            method: 'POST',
            data: { action: 'get_user_ticket', ticket_id: ticketId, ...csrf_data() },
            beforeSend: () => {

                disable_form('.ticket_view_btn');
            },
            complete: () => {

                enable_form('.ticket_view_btn');
            },
            success: (res, textStatus, xhr) => {
                <?= !isProduction() ? 'console.log(res);' : '' ?>
                if (xhr.status === 200) {

                    if (res.ticket_id && $('#user_ticket_modal_title').length > 0)
                        $('#user_ticket_modal_title').html(`Support Ticket <strong>#${res.ticket_id}</strong>`);

                    if (res.view && $('#user_ticket_modal_body').length > 0) {
                        $('#user_ticket_modal_body').html(res.view);
                        showModal('#user_ticket_modal');
                    }

                }
            },
            error: (xhr) => {
                <?= !isProduction() ? 'console.log(xhr);' : '' ?>
                var res = xhr.responseJSON || xhr.responseText;
                if (xhr.status === 404) {
                    location.reload();
                }
            },
        });
    }
</script>
<?php $this->endSection() ?>