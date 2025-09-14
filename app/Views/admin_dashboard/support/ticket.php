<?php $this->section('style') ?>
<style>
    .user-container {
        border: 1px solid var(--bs-info);
        background-color: rgba(var(--bs-info-rgb), 0.22);
    }

    .message-container {
        border: 1px solid var(--bs-primary);
        background-color: rgba(var(--bs-primary-rgb), 0.22);
    }
</style>
<?php $this->endSection() ?>


<?= $this->extend('admin_dashboard/_partials/app') ?>

<?= $this->section('slot') ?>

<div class="container-fluid">
    <div id="admin_ticket_page_content">
        <?= view('admin_dashboard/support/__ticket_page_content', [
            'ticket' => &$ticket,
            'user' => &$user
        ]) ?>
    </div>
</div>

<?= $this->endSection() ?>

<?php if (!$ticket->status): ?>
    <?php $this->section('script') ?>
    <script>

        $(document).ready(function () {

            const formSelector = '#ticket_reply_form';

            // validating
            validateForm(formSelector, {
                rules: {
                    reply: { required: true, minlength: 10, maxlength: 2000 },
                },
                submitHandler: function (form) {

                    sConfirm(function () {

                        const formData = new FormData(form);
                        append_csrf(formData);
                        formData.append('action', 'admin_reply');

                        const btnContent = $('.ticket_reply_btn span').html();

                        const enableButton = () => {
                            if ($('.ticket_reply_btn span').length > 0) {
                                $('.ticket_reply_btn span').html(btnContent);
                                enable_form(formSelector);
                            }
                        };



                        $.ajax({
                            url: '<?= route('admin.support.ticket', $ticket->ticket_id) ?>',
                            method: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            beforeSend: function () {

                                $('.ticket_reply_btn span').html(spinnerLabel());

                                disable_form(formSelector);
                            },
                            complete: function () {
                                enableButton();
                            },
                            success: function (res, textStatus, xhr) {

                                <?= !isProduction() ? 'console.log(res);' : '' ?>

                                if (xhr.status === 200) {

                                    const pageC = '#admin_ticket_page_content';

                                    if (res.message) {
                                        toast.success(res.message);
                                        sAlert('success', '', res.message)
                                    }


                                    if (res.view && $(pageC).length > 0)
                                        $(pageC).html(res.view);
                                    else
                                        location.reload();

                                }
                            },
                            error: function (xhr) {
                                <?= !isProduction() ? 'console.log(xhr);' : '' ?>
                                var res = xhr.responseJSON || xhr.responseText;
                                if (xhr.status === 400 && res.errors) {
                                    if (res.errors.validationErrors) {
                                        $(formSelector).validate({ focusInvalid: true }).showErrors(res.errors.validationErrors);
                                    }
                                    if (res.errors.error) {
                                        sAlert('error', '', res.errors.error);
                                    }
                                }
                            }
                        });

                    }, { text: "You want to reply to the ticket?" });

                    return false;
                }
            });

        });
    </script>
    <?php $this->endSection() ?>
<?php endif; ?>