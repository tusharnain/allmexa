<?php
use App\Twebsol\Settings;

$tpinLabel = label('tpin');
?>

<?= $this->extend('user_dashboard/layout/master') ?>


<?= $this->section('slot') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" id="transfer_form">
                        <div class="row">
                            <div class="col-lg-4">
                                <?= user_component('select', [
                                    'name' => 'from',
                                    'label' => 'From Wallet',
                                    'options' => $fromWallets,
                                    'empty_option' => 'Select Wallet',
                                    'disable_empty_option' => true,
                                    'select_empty_option' => true,
                                    'id' => 'from_select_f'
                                ]) ?>
                            </div>
                            <div class="col-lg-4" id="to_wallet_select_ctr">
                                <?= $toSelectHtml ?>
                            </div>
                            <div class="col-lg-4">
                                <?= user_component('input', [
                                    'type' => 'number',
                                    'name' => 'amount',
                                    'label' => 'Transfer Amount',
                                    'placeholder' => 'Enter Transfer Amount'
                                ]) ?>
                            </div>

                            <div class="col-lg-4">
                                <?= user_component('input', [
                                    'name' => 'tpin',
                                    'label' => $tpinLabel,
                                    'placeholder' => "Enter $tpinLabel"
                                ]) ?>
                            </div>
                        </div>

                        <div class="text-end">
                            <?= user_component('button', [
                                'label' => 'Transfer',
                                'icon' => 'fa-solid fa-circle-check',
                                'class' => 'mobile-button',
                                'iconLast' => true,
                                'submit' => true,
                                'id' => 'transfer_btn'
                            ]) ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection() ?>


<?php $this->section('script') ?>
<script>
    $(document).ready(function () {

        // form selector
        const formSelector = '#transfer_form';
        const tpin_digits = <?= _setting('tpin_digits', 6) ?>;


        const amountRule = { required: true, number: true };


        $('#from_select_f').on('change', function () {
            const wallet = $(this).val();

            $.ajax({
                url: "<?= current_url() ?>",
                method: 'POST',
                data: { action: 'get_to_wallet_select', from: wallet, ...csrf_data() },
                beforeSend: () => disable_form(formSelector),
                success: function (res) {
                    if (res.html)
                        $('#to_wallet_select_ctr').html(res.html);
                },
                complete: () => enable_form(formSelector)
            });

        });

        // validating
        validateForm(formSelector, {
            rules: {
                from: { required: true },
                to: { required: true },
                amount: amountRule,
                tpin: { required: true, no_trailing_spaces: true, number: true, exactDigits: tpin_digits }
            },
            submitHandler: function (form) {


                sConfirm(function () {
                    const formData = new FormData(form);
                    append_csrf(formData);
                    formData.append('action', 'submit_wallet_transfer');
                    const btnContent = $('#transfer_btn span').html();

                    const enableButton = () => {
                        $('#transfer_btn span').html(btnContent);
                        enable_form(formSelector);
                    };

                    $.ajax({
                        url: "<?= current_url() ?>",
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function () {

                            $('#transfer_btn span').html(spinnerLabel({ label: 'Processing Transfer' }));
                            disable_form(formSelector);

                            //loading
                            sProcessingPopup('Processing Transfer...', 'Do not close this window!');
                        },
                        complete: function () {
                            enableButton();
                        },
                        success: function (res, textStatus, xhr) {

                            <?= !isProduction() ? 'console.log(res);' : '' ?>

                            if (xhr.status === 200) {

                                if (res.title && res.message)
                                    sAlert('success', res.title, res.message);

                                clearInputs(formSelector);
                            }

                        },
                        error: function (xhr) {

                            <?= !isProduction() ? 'console.log(xhr);' : '' ?>

                            var res = xhr.responseJSON || xhr.responseText;

                            if (xhr.status === 400 && res.errors) {

                                if (res.errors.validationErrors) {

                                    $(formSelector).validate({ focusInvalid: true }).showErrors(res.errors.validationErrors);
                                    Swal.close();
                                }

                                if (res.errors.error) {
                                    sAlert('error', '', res.errors.error);
                                }
                            }
                        }
                    });
                },
                    { text: 'Are you sure you want to transfer?' });


                return false;
            }
        });


    });
</script>
<?php $this->endSection() ?>