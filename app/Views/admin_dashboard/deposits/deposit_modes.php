<?php

function setAction(string $action): string
{
    return "<input type=\"hidden\" name=\"action\"  value=\"$action\">";
}


$saveButton = admin_component('button', [
    'label' => 'Save Changes',
    'icon' => 'fas fa-save',
    'submit' => true
]);

?>


<?= $this->extend('admin_dashboard/_partials/app') ?>




<?= $this->section('slot') ?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h4 class="ps-1 mb-3">
                Manual Deposit Modes
                <hr>
            </h4>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>
                        Bank Account
                    </h5>
                </div>
                <div class="card-body">

                    <form id="bank_account_form">
                        <?= setAction('bank_account') ?>
                        <?= admin_component('input', [
                            'name' => 'account_number',
                            'label' => 'Bank Account Number',
                            'placeholder' => 'Enter Bank Account Number',
                            'value' => isset ($bank_account->data->account_number) ? escape($bank_account->data->account_number) : ''
                        ]) ?>

                        <?= admin_component('input', [
                            'name' => 'ifsc',
                            'label' => 'Bank IFSC Code',
                            'placeholder' => 'Enter Bank IFSC Code',
                            'value' => isset ($bank_account->data->ifsc) ? escape($bank_account->data->ifsc) : ''
                        ]) ?>

                        <?= admin_component('toggle', [
                            'name' => 'bank_receipt_upload',
                            'label' => 'Allow Receipt Upload',
                            'checked' => $bank_account->data->receipt_upload ?? 0
                        ]) ?>

                        <?= admin_component('toggle', [
                            'name' => 'bank_allow_remarks',
                            'label' => 'Allow Remarks',
                            'checked' => $bank_account->data->allow_remarks ?? 0
                        ]) ?>

                        <?= admin_component('toggle', [
                            'name' => 'bank_visibility',
                            'label' => 'Visibility',
                            'checked' => $bank_account->visibility ?? 0
                        ]) ?>

                        <div class="text-end mt-3">
                            <?= $saveButton ?>
                        </div>
                    </form>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>
                        UPI Address
                    </h5>
                </div>
                <div class="card-body">

                    <form id="upi_form">
                        <?= setAction('upi') ?>
                        <?= admin_component('input', [
                            'name' => 'upi_address',
                            'label' => 'UPI Address',
                            'placeholder' => 'Enter UPI Address',
                            'value' => isset ($upi_address->data->address) ? escape($upi_address->data->address) : ''
                        ]) ?>

                        <?= admin_component('toggle', [
                            'name' => 'upi_receipt_upload',
                            'label' => 'Allow Receipt Upload',
                            'checked' => $upi_address->data->receipt_upload ?? 0
                        ]) ?>

                        <?= admin_component('toggle', [
                            'name' => 'upi_allow_remarks',
                            'label' => 'Allow Remarks',
                            'checked' => $upi_address->data->allow_remarks ?? 0
                        ]) ?>

                        <?= admin_component('toggle', [
                            'name' => 'upi_visibility',
                            'label' => 'Visibility',
                            'checked' => $upi_address->visibility ?? 0
                        ]) ?>

                        <div class="text-end mt-3">
                            <?= $saveButton ?>
                        </div>
                    </form>

                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <h5>
                        Mobile Number
                    </h5>
                </div>
                <div class="card-body">

                    <form id="mobile_number_form">
                        <?= setAction('mobile_number') ?>
                        <?= admin_component('input', [
                            'name' => 'mobile_number',
                            'label' => 'Mobile Number',
                            'placeholder' => 'Enter Mobile Number',
                            'value' => isset ($mobile_number->data->number) ? escape($mobile_number->data->number) : ''
                        ]) ?>

                        <?= admin_component('toggle', [
                            'name' => 'mobile_receipt_upload',
                            'label' => 'Allow Receipt Upload',
                            'checked' => $mobile_number->data->receipt_upload ?? 0
                        ]) ?>

                        <?= admin_component('toggle', [
                            'name' => 'mobile_allow_remarks',
                            'label' => 'Allow Remarks',
                            'checked' => $mobile_number->data->allow_remarks ?? 0
                        ]) ?>

                        <?= admin_component('toggle', [
                            'name' => 'mobile_visibility',
                            'label' => 'Visibility',
                            'checked' => $mobile_number->visibility ?? 0
                        ]) ?>

                        <div class="text-end mt-3">
                            <?= $saveButton ?>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>
                        USDT (TRC20)
                    </h5>
                </div>
                <div class="card-body">

                    <form id="usdt_trc20_form">
                        <?= setAction('usdt_trc20') ?>
                        <?= admin_component('input', [
                            'name' => 'usdt_trc20_wallet_address',
                            'label' => 'USDT (TRC20) Address',
                            'placeholder' => 'Enter Wallet Address',
                            'value' => isset ($usdt_trc20->data->wallet_address) ? escape($usdt_trc20->data->wallet_address) : ''
                        ]) ?>

                        <?= admin_component('toggle', [
                            'name' => 'usdt_trc20_allow_remarks',
                            'label' => 'Allow Remarks',
                            'checked' => $usdt_trc20->data->allow_remarks ?? 0
                        ]) ?>

                        <?= admin_component('toggle', [
                            'name' => 'usdt_trc20_visibility',
                            'label' => 'Visibility',
                            'checked' => $usdt_trc20->visibility ?? 0
                        ]) ?>

                        <div class="text-end mt-3">
                            <?= $saveButton ?>
                        </div>
                    </form>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>
                        USDT (BEP20)
                    </h5>
                </div>
                <div class="card-body">

                    <form id="usdt_bep20_form">
                        <?= setAction('usdt_bep20') ?>
                        <?= admin_component('input', [
                            'name' => 'usdt_bep20_wallet_address',
                            'label' => 'USDT (BEP20) Address',
                            'placeholder' => 'Enter Wallet Address',
                            'value' => isset ($usdt_bep20->data->wallet_address) ? escape($usdt_bep20->data->wallet_address) : ''
                        ]) ?>

                        <?= admin_component('toggle', [
                            'name' => 'usdt_bep20_allow_remarks',
                            'label' => 'Allow Remarks',
                            'checked' => $usdt_bep20->data->allow_remarks ?? 0
                        ]) ?>

                        <?= admin_component('toggle', [
                            'name' => 'usdt_bep20_visibility',
                            'label' => 'Visibility',
                            'checked' => $usdt_bep20->visibility ?? 0
                        ]) ?>

                        <div class="text-end mt-3">
                            <?= $saveButton ?>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?php $this->section('script') ?>
<script>
    $(document).ready(function () {


        let formSelecters = ['#bank_account_form', '#upi_form', '#mobile_number_form', '#usdt_trc20_form', '#usdt_bep20_form'];


        formSelecters.forEach(function (formSelecter) {

            validateForm(formSelecter, {
                rules: {
                    account_number: { required: true, number: true, minlength: 9, maxlength: 25 },
                    ifsc: { required: true, alpha_num: true, exactLength: 11, regex: /^[A-Za-z]{4}0[A-Za-z0-9]{6}$/ },
                    usdt_trc20_wallet_address: { required: true, alpha_num: true, minlength: 20, maxlength: 50 },
                    usdt_bep20_wallet_address: { required: true, alpha_num: true, minlength: 20, maxlength: 50 },
                    upi_address: {
                        required: true, regex: /^[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}$/
                    },
                    mobile_number: { required: true, minlength: 10, maxlength: 14 }
                },
                messages: {
                    ifsc: {
                        exactLength: 'Invalid IFSC Code',
                        regex: 'Invalid IFSC Code',
                    },
                    upi_address: {
                        regex: 'Invalid UPI Address.'
                    }
                },
                submitHandler: function (form) {

                    const formData = new FormData(form);

                    append_csrf(formData);

                    const button = $(form).find('button span');

                    const btnContent = button.html();

                    const enableButton = () => {
                        button.html(btnContent);
                        enable_form(formSelecter);
                    };

                    $.ajax({
                        url: "<?= current_url() ?>",
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function () {

                            button.html(spinnerLabel());
                            disable_form(formSelecter);

                        },
                        complete: function () {
                            enableButton();
                        },
                        success: function (res, textStatus, xhr) {

                            <?= !isProduction() ? 'console.log(res);' : '' ?>

                            if (xhr.status === 200) {

                                if (res.message)
                                    toast.success(res.message);

                            }

                        },
                        error: function (xhr) {
                            <?= !isProduction() ? 'console.log(xhr);' : '' ?>
                            var res = xhr.responseJSON || xhr.responseText;
                            if (xhr.status === 400 && res.errors) {
                                if (res.errors.validationErrors) {
                                    $(formSelecter).validate({ focusInvalid: true }).showErrors(res.errors.validationErrors);
                                    // Manually scroll to the first input with class 'is-invalid'
                                    const firstInvalidInput = $(formSelecter).find('.is-invalid').first();
                                    scrollToElement(firstInvalidInput);
                                }
                                if (res.errors.error) {
                                    sAlert('error', '', res.errors.error);
                                }
                            }
                        }
                    });

                    return false;
                }
            });

        });

    });
</script>
<?php $this->endSection() ?>