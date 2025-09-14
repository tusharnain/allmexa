<?php
$userIdLabel = label('user_id');
?>

<?= $this->extend('admin_dashboard/_partials/app') ?>



<?= $this->section('slot') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body pb-1">
                    <form id="add_deduct_user_id_form">
                        <?= csrf_field() ?>
                        <?= admin_component('input', [
                            'name' => 'user_id',
                            'label' => $userIdLabel,
                            'placeholder' => "Enter $userIdLabel"
                        ]) ?>

                        <?= admin_component('button', [
                            'label' => 'Submit',
                            'class' => 'float-end',
                            'icon' => 'mdi mdi-send',
                            'iconLast' => true,
                            'submit' => true,
                            'id' => 'search_user_btn'
                        ]) ?>
                    </form>
                </div>
            </div>
            <div id="add_deduct_view_container"></div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('script') ?>



<script>

    const addDeductFormSelector = '#add_deduct_form';

    const addDeductAmountRange = <?= json_encode(_setting('admin_add_deduct_amount_range')) ?>;

    function ajaxErrorHandler(xhr, formSelector) {
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


    const addDeductAjaxValidationOptions = {
        rules: {
            wallet: { required: true },
            type: { required: true },
            amount: { required: true, number: true, min: addDeductAmountRange[0], max: addDeductAmountRange[1] },
            remarks: { required: false, minlength: 1, maxlength: 250 }
        },
        submitHandler: function (form) {

            var selectField = $('#select_wallet_field');

            sConfirm(function () {
                const formData = new FormData(form);
                const btnContent = $('#add_deduct_btn span').html();

                const enableButton = () => {
                    $('#add_deduct_btn span').html(btnContent);
                    enable_form(addDeductFormSelector);
                };

                formData.append('action', 'add_deduct');

                $.ajax({
                    url: "<?= route('admin.wallets.addDeduct') ?>",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res, textStatus, xhr) {

                        <?= !isProduction() ? 'console.log(res);' : '' ?>

                        if (xhr.status === 200) {

                            if (res.title && res.message)
                                sAlert('success', res.title, res.message);

                            if (res.wallet_select && $('#wallet_select_container').length > 0) {
                                $('#wallet_select_container').html(res.wallet_select);

                                selectField = $('#select_wallet_field');

                                // making used walllet, the selected field
                                if (res.wallet)
                                    selectField.val(res.wallet);

                                $(addDeductFormSelector).validate().settings.rules[selectField.attr('name')] = { required: true };
                            }
                        }

                    },
                    error: function (xhr) {
                        ajaxErrorHandler(xhr, addDeductFormSelector);
                    }
                });

            }, { text: "You want to make this transaction?" });

            return false;
        }
    };



    $(document).ready(function () {

        const searchUserFormSelector = '#add_deduct_user_id_form';
        const userIdLengths = <?= json_encode(_setting('user_id_length_validation')) ?>;

        // validating
        validateForm(searchUserFormSelector, {
            rules: {
                user_id: { required: true, alpha_num: true, minlength: userIdLengths[0], maxlength: userIdLengths[1] }
            },
            submitHandler: function (form) {
                const formData = new FormData(form);
                const btnContent = $('#search_user_btn span').html();

                const enableButton = () => {
                    $('#search_user_btn span').html(btnContent);
                    enable_form(searchUserFormSelector);
                };

                formData.append('action', 'search_user');


                $.ajax({
                    url: "<?= route('admin.wallets.addDeduct') ?>",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('#search_user_btn span').html(spinnerLabel({ label: 'Searching User' }));
                        disable_form(searchUserFormSelector);
                        disable_form(addDeductFormSelector);
                    },
                    complete: function () {
                        enableButton();
                    },
                    success: function (res, textStatus, xhr) {

                        <?= !isProduction() ? 'console.log(res);' : '' ?>

                        if (xhr.status === 200 && res.user && res.wallets) {

                            if (res.view) {

                                if ($('#add_deduct_view_container').length > 0) {
                                    $('#add_deduct_view_container').fadeOut('fast', function () {
                                        $(this).html(res.view).slideDown(function () {
                                            validateForm(addDeductFormSelector, addDeductAjaxValidationOptions);
                                            scrollToBottom();
                                        });
                                    });
                                }
                            }
                        }

                    },
                    error: function (xhr) {
                        ajaxErrorHandler(xhr, searchUserFormSelector);
                    }
                });
                return false;
            }
        });

    });

</script>

<?= $this->endSection('script') ?>