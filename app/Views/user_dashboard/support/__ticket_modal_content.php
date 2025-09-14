<?php

$__ar = '<i class="fa-solid fa-arrow-right-long me-2"></i>';
function ___get_html(string $title, string $value, ?string &$ar = null): string
{
    return "$title
    $ar
    <span>$value</span>";
}

?>

<div>

    <?php if ($ticket->status and $ticket->created_at): ?>
        <?= user_component('alert', [
            'type' => 'danger',
            'text' => "This ticket has been closed at $ticket->created_at."
        ]) ?>
    <?php endif; ?>

    <div class="bg-light-primary text-ld p-3">

        <div class="row">
            <div class="col-lg-6">
                <h6>
                    <?= ___get_html('Ticket Id', $ticket->ticket_id, $__ar) ?>
                </h6>

                <h6>
                    <?= ___get_html('Subject', escape($ticket->subject), $__ar) ?>
                </h6>
            </div>
            <div class="col-lg-6 text-lg-end">
                <h6>
                    <strong>Opened at</strong>
                    <?= f_date($ticket->created_at) ?>
                </h6>
                <?php if ($ticket->closed_at): ?>
                    <h6>
                        <strong>Closed at</strong>
                        <?= f_date($ticket->closed_at) ?>
                    </h6>
                <?php endif; ?>
            </div>
        </div>


        <h5 class="mt-3">
            <span class="fw-bold">Your Message</span>
            <?= $__ar ?>
            <p class="mt-2 p-3 lh-base border border-primary bg-light-primary text-ld">
                <?= nl2br(escape($ticket->message)) ?>
            </p>
        </h5>

    </div>


    <?php if ($ticket->status): ?>
        <div class="mt-4 p-3 bg-light-success text-ld">
            <h5 class="mt-2">
                <span class="fw-bold">Admin Reply :</span>

                <p class="mt-2 p-3 lh-base border border-success bg-light-success text-ld">
                    <?= nl2br(escape($ticket->admin_reply)) ?>
                </p>
            </h5>
        </div>
    <?php endif; ?>
</div>