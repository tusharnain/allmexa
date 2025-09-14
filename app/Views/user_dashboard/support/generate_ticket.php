<?= $this->extend('user_dashboard/layout/master') ?>


<?= $this->section('slot') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">

                    <?php if (isset ($openTicketLimitReached)): ?>
                        <?= user_component('alert', [
                            'type' => 'danger',
                            'icon' => 'fa-solid fa-circle-exclamation',
                            'text' => $openTicketLimitReached
                        ]) ?>
                    <?php endif; ?>



                    <form id="generate_ticket_form">
                        <div class="row">

                            <div class="col-md-6">
                                <?= user_component('input', [
                                    'name' => 'user_id',
                                    'label' => label('user_id'),
                                    'value' => user('user_id'),
                                    'bool_attributes' => 'readonly disabled',
                                ]) ?>
                            </div>

                            <div class="col-md-6">
                                <?= user_component('input', [
                                    'name' => 'user_name',
                                    'label' => label('user_name'),
                                    'value' => user('full_name'),
                                    'bool_attributes' => 'readonly disabled',
                                ]) ?>
                            </div>

                            <div class="col-md-12 clr_inps">
                                <?= user_component('input', [
                                    'name' => 'subject',
                                    'label' => 'Subject',
                                    'placeholder' => 'Enter Ticket Subject'
                                ]) ?>
                            </div>

                            <div class="col-md-12 clr_inps">
                                <?= user_component('textarea', [
                                    'name' => 'message',
                                    'label' => 'Message',
                                    'placeholder' => 'Enter you message here (max 2000 characters)'
                                ]) ?>
                            </div>

                            <div class="col-md-3 clr_inps">
                                <?= user_component('input', [
                                    'name' => 'tpin',
                                    'label' => label('tpin'),
                                    'placeholder' => 'Enter ' . label('tpin')
                                ]) ?>
                            </div>

                        </div>

                        <div class="clr_inps">
                            <?= user_component('button', [
                                'label' => 'Generate Ticket',
                                'icon' => 'fa-solid fa-ticket',
                                'id' => 'ticket_btn',
                                'class' => 'mobile-button float-end',
                                'submit' => true
                            ]) ?>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>




<?= $this->section('script') ?>

<script>
    $(document).ready(function () {

        const formBlock = <?= isset ($blockForm) ? 'true' : 'false'; ?>;
        const formSelector = '#generate_ticket_form';
        const tpin_digits = <?= _setting('tpin_digits', 6) ?>;

        if (formBlock) {

            disable_form('.clr_inps');

        } else {

            // validating
            validateForm(formSelector, {
                rules: {
                    subject: { required: true, minlength: 5, maxlength: 150 },
                    message: { required: true, minlength: 10, maxlength: 2000 },
                    tpin: { required: true, no_trailing_spaces: true, number: true, exactDigits: tpin_digits }
                },
                submitHandler: function (form) {

                    const formData = new FormData(form);
                    append_csrf(formData);

                    const btnContent = $('#ticket_btn span').html();

                    const enableButton = () => {
                        $('#ticket_btn span').html(btnContent);
                        enable_form('.clr_inps');
                    };


                    sConfirm(function () {

                        $.ajax({
                            url: "<?= route('user.support.generateTicket') ?>",
                            method: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            beforeSend: function () {
                                $('#ticket_btn span').html(spinnerLabel({ label: 'Generating Ticket' }));
                                disable_form('.clr_inps');
                            },
                            complete: function () {
                                enableButton();
                            },
                            success: function (res, textStatus, xhr) {

                                <?= !isProduction() ? 'console.log(res);' : '' ?>

                                if (xhr.status === 200) {

                                    clearInputs('.clr_inps');

                                    if (res.title && res.message)
                                        sAlert('success', res.title, res.message);

                                }

                            },
                            error: function (xhr) {

                                <?= !isProduction() ? 'console.log(xhr);' : '' ?>

                                var res = xhr.responseJSON || xhr.responseText;

                                if (xhr.status === 400 && res.errors) {

                                    if (res.errors.validationErrors) {

                                        $(formSelector).validate({ focusInvalid: true }).showErrors(res.errors.validationErrors);

                                        // Manually scroll to the first input with class 'is-invalid'
                                        const firstInvalidInput = $(formSelector).find('.is-invalid').first();
                                        scrollToElement(firstInvalidInput);
                                    }

                                    if (res.errors.error) {
                                        sAlert('error', '', res.errors.error);
                                    }
                                }
                            }
                        });

                    },
                        { text: 'Are you sure you want to generate ticket?' });

                    return false;
                }
            });


        }

    });

</script>
<?= $this->endSection() ?>