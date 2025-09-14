<?php
use App\Services\WalletService;

$tpinLabel = label('tpin');
?>

<?= $this->extend('user_dashboard/layout/master') ?>



<?= $this->section('slot') ?>
<div class="container-fluid">
    <?php if ($walletAddress || $bankAccount): ?>
        <div class="row">

            <div class="col-xl-6">
                <div class="card small-widget">
                    <div class="card-body primary">
                        <span class="f-light">
                            <h6>
                                <?= wallet_label(WalletService::WITHDRAW_FROM_WALLET) ?>
                            </h6>
                        </span>
                        <div class="d-flex align-items-end gap-1 mt-4">
                            <h4 class="mt-2" id="wallet_balance">
                                <?= f_amount($balance) ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">

                        <form id="wd_form">
                            <?= user_component('input', [
                                'label' => 'Amount',
                                'name' => 'amount',
                                'placeholder' => 'Enter withdrawal amount',
                                'id' => 'f_amount'
                            ]) ?>

                            <?= _setting('withdrawal_remarks', false) ? user_component('textarea', [
                                'label' => 'Remarks',
                                'name' => 'remarks',
                                'placeholder' => 'Enter Remarks (Optional, Max 250 Characters)',
                                'rows' => 3
                            ]) : '' ?>

                            <div class="row">
                                <div class="col-md-4">
                                    <?= user_component('input', [
                                        'name' => 'tpin',
                                        'label' => $tpinLabel,
                                        'placeholder' => "Enter $tpinLabel"
                                    ]) ?>
                                </div>
                            </div>

                            <div class="text-end">
                                <?= user_component('button', [
                                    'label' => 'Withdraw Now',
                                    'icon' => 'fa-solid fa-circle-check',
                                    'class' => 'mobile-button',
                                    'iconLast' => true,
                                    'submit' => true,
                                    'id' => 'wd_btn'
                                ]) ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?= user_component('alert', [
            'text' => 'Wallet Address is not submitted yet!, Only after then you can access withdrawal page.',
            'type' => 'danger',
            'icon' => 'fa-solid fa-exclamation-circle'
        ]) ?>
    <?php endif; ?>
</div>
<?php $this->endSection() ?>



<?php $this->section('script') ?>
<script>

    $(document).ready(function () {

        // form selector
        const formSelector = '#wd_form';
        const tpin_digits = <?= _setting('tpin_digits', 6) ?>;
        const wd_range = <?= json_encode(_setting('withdrawal_amount_range', [500, 1000])) ?>;
        const wd_multipleOf = <?= _setting('withdrawal_amount_multiple_of', null) ?? "null" ?>;
        var currBal = <?= $balance ?>;

        const amountRule = { required: true, number: true, min: wd_range[0], max: wd_range[1] };
        if (wd_multipleOf)
            amountRule.multipleOf = wd_multipleOf

        // validating
        validateForm(formSelector, {
            rules: {
                amount: amountRule,
                remarks: { required: false, minlength: 1, maxlength: 250 },
                tpin: { required: true, no_trailing_spaces: true, number: true, exactDigits: tpin_digits }
            },
            messages: {
                amount: {
                    min: 'Minimum withdrawal amount is {0}.',
                    max: 'Maximum amount for withdrawal is {0}.',
                }
            },
            submitHandler: function (form) {

                const amount = $('#f_amount').val().trim();
                if (amount > currBal) {
                    sAlert('error', '', 'Insufficient Wallet Balance!');
                    return false;
                }


                sConfirm(function () {
                    const formData = new FormData(form);
                    formData.append('action', 'wd_submit');
                    append_csrf(formData);
                    const btnContent = $('#wd_btn span').html();

                    const enableButton = () => {
                        $('#wd_btn span').html(btnContent);
                        enable_form(formSelector);
                    };

                    $.ajax({
                        url: "<?= current_url() ?>",
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function () {

                            $('#wd_btn span').html(spinnerLabel({ label: 'Processing Withdrawal' }));
                            disable_form(formSelector);

                            //loading
                            sProcessingPopup('Processing Withdrawal...', 'Do not close this window!');
                        },
                        complete: function () {
                            enableButton();
                        },
                        success: function (res, textStatus, xhr) {

                            <?= !isProduction() ? 'console.log(res);' : '' ?>

                            if (xhr.status === 200) {

                                if (res.title && res.message)
                                    sAlert('success', res.title, res.message);


                                jqueryElementAction('#wallet_balance', function (el) {
                                    res.fWalletBalance && el.text(res.fWalletBalance);
                                });

                                if (res.walletBalance)
                                    currBal = res.walletBalance;

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
                    { text: 'Are you sure you want to withdraw?' });
                return false;
            }
        });

    });
</script>
<?php $this->endSection() ?>