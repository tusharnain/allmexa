<?php

$__ar = '<i class="mdi mdi-arrow-right"></i>';
function ___get_html(string $title, string $value, ?string &$ar = null): string
{
    return "<span class=\"fw-bold\">$title</span>$ar<span>$value</span>";
}

?>

<div>

    <?= $ticket->status ? admin_component('alert', [
        'type' => 'danger',
        'text' => 'This ticket has been closed.'
    ]) : '' ?>

    <div class="row">
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Ticket Id :
                    <?= $ticket->ticket_id ?>
                </div>
                <div class="card-body">
                    <div class="row fw-bold border-bottom border-primary mb-4 pb-2">
                        <div class="col-lg-6">
                            <h6>Ticket Id :
                                <?= $ticket->ticket_id ?>
                            </h6>
                        </div>
                        <div class="col-lg-6">
                            <p class="my-0 text-lg-end">
                                Opened at
                                <?= f_date($ticket->created_at) ?>
                            </p>
                            <?php if ($ticket->closed_at): ?>
                                <p class="my-0 text-lg-end">
                                    Closed at
                                    <?= f_date($ticket->closed_at) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="user-container p-2 pb-0 mb-3">
                        <h6>
                            <?= ___get_html(label('user_id'), $user->user_id, $__ar) ?>
                        </h6>

                        <h6>
                            <?= ___get_html(label('user_name'), escape($user->full_name), $__ar) ?>
                        </h6>

                        <h6>
                            <?= ___get_html('Subject', escape($ticket->subject), $__ar) ?>
                        </h6>
                    </div>

                    <h6 class="mb-3 message-container p-2">
                        <span class="fw-bold">Message</span>
                        <?= $__ar ?>
                        <p class="mt-2 lh-base">
                            <?= nl2br(escape($ticket->message)) ?>
                        </p>
                    </h6>
                </div>
            </div>
        </div>

        <div class="col-xxl-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Your Reply
                </div>
                <div class="card-body pb-2">

                    <?php if ($ticket->status): ?>

                        <h6 class="mb-3 message-container p-2">
                            <span class="fw-bold">Admin Reply</span>
                            <?= $__ar ?>
                            <p class="mt-2 lh-base">
                                <?= $ticket->admin_reply ? nl2br(escape($ticket->admin_reply)) : '' ?>
                            </p>
                        </h6>

                    <?php else: ?>

                        <form id="ticket_reply_form">
                            <?= admin_component('textarea', [
                                'name' => 'reply',
                                'label' => 'Reply Message',
                                'placeholder' => 'Enter your reply here. (max 2000 characters)'
                            ]) ?>

                            <?= admin_component('button', [
                                'label' => 'Submit',
                                'icon' => 'mdi mdi-send',
                                'class' => 'float-end ticket_reply_btn',
                                'iconLast' => true,
                                'submit' => true
                            ]) ?>
                        </form>

                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>