<?php
$userLabel = label('user');
$userIdLabel = label('user_id');
$userNameLabel = label('user_name');
$tpinLabel = label('tpin');
?>

<?= $this->extend('user_dashboard/layout/master') ?>




<?= $this->section('slot') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shining-card">
                <div class="card-body">
                    <span class="fs-5 me-2">
                        <?= wallet_label('fund') ?> Balance
                    </span>
                    <svg width="36" height="35" viewBox="0 0 36 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3.86124 21.6224L11.2734 16.8577C11.6095 16.6417 12.041 16.6447 12.3718 16.8655L18.9661 21.2663C19.2968 21.4871 19.7283 21.4901 20.0644 21.2741L27.875 16.2534"
                            stroke="#BFBFBF" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path
                            d="M26.7847 13.3246L31.6677 14.0197L30.4485 18.7565L26.7847 13.3246ZM30.2822 19.4024C30.2823 19.4023 30.2823 19.4021 30.2824 19.402L30.2822 19.4024ZM31.9991 14.0669L31.9995 14.0669L32.0418 13.7699L31.9995 14.0669C31.9994 14.0669 31.9993 14.0669 31.9991 14.0669Z"
                            fill="#BFBFBF" stroke="#BFBFBF"></path>
                    </svg>
                    <div class="pt-3">
                        <h4 class="counter" style="visibility: visible;" id="wallet_balance">
                            <?= wallet_famount($walletBalance, wallet: 'fund') ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <form class="row" id="topup_form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="type" value="compound">

                        <div class="col-12">
                            <?= user_component('input', [
                                'name' => 'user_id',
                                'label' => $userIdLabel,
                                'placeholder' => "Enter $userIdLabel",
                                'id' => 'user_id_f'
                            ]) ?>
                        </div>

                        <div class="col-12">
                            <?= user_component('input', [
                                'name' => 'user_name',
                                'label' => $userNameLabel,
                                'disabled' => true,
                                'class' => 'data_disabled',
                                'id' => 'user_name_f'
                            ]) ?>
                        </div>

                        <div class="col-12">
                            <?= user_component('input', [
                                'name' => 'amount',
                                'label' => 'Invest Amount',
                                'id' => 'amount_f',
                                'placeholder' => 'Enter investment amount'
                            ]) ?>
                        </div>


                        <div class="col-md-3">
                            <?= user_component('input', [
                                'name' => 'tpin',
                                'label' => $tpinLabel,
                                'placeholder' => "Enter $tpinLabel"
                            ]) ?>
                        </div>


                        <?= user_component('button', [
                            'label' => 'Submit Topup',
                            'icon' => 'fa-solid fa-circle-check',
                            'class' => 'mobile-button',
                            'iconLast' => true,
                            'submit' => true,
                            'id' => 'topup_btn'
                        ]) ?>
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

        var currBal = <?= $walletBalance ?>;



        /*
        *------------------------------------------------------------------------------------
        * User Name Fetch
        *------------------------------------------------------------------------------------
        */
        $('#user_id_f').on('change', function () {

            const user_id = $(this).val();
            const api = "<?= route('api.public.getUserNameFromUserId') ?>";

            Dashboard.fetchAndSetUsernameToDom({
                user_id,
                api
            });
        });



        /*
        *------------------------------------------------------------------------------------
        * Topup Form
        *------------------------------------------------------------------------------------
        */
        <?php

        $topupAmountRange = _setting('topup_amount_range');
        $topupAmountStart = _c($topupAmountRange[0]);
        $topupAmountEnd = _c($topupAmountRange[1]);

        $topupAmountMultipleOf = _setting('topup_amount_multiple_of');
        if ($topupAmountMultipleOf) {
            $topupAmountMultipleOf = intval(_c($topupAmountMultipleOf));
        }

        ?>
        // form selector
        const formSelector = '#topup_form';
        const userIdLengths = <?= json_encode(_setting('user_id_length_validation')) ?>;
        const topup_range = <?= json_encode([intval($topupAmountStart), intval($topupAmountEnd)]) ?>;
        const topup_multiple_of = <?= $topupAmountMultipleOf ?? 'null' ?>;
        const tpin_digits = <?= _setting('tpin_digits', 6) ?>;

        const amountRule = { required: true, number: true, min: topup_range[0], max: topup_range[1] };
        if (topup_multiple_of)
            amountRule.multipleOf = topup_multiple_of

        // validating
        validateForm(formSelector, {
            rules: {
                user_id: { required: true, alpha_num: true, minlength: userIdLengths[0], maxlength: userIdLengths[1] },
                amount: amountRule,
                duration: { required: true },
                tpin: { required: true, no_trailing_spaces: true, number: true, exactDigits: tpin_digits }
            },
            submitHandler: function (form) {

                const fData = new FormData(form);
                const i_amount = fData.get('amount') ?? 0;

                if (currBal < i_amount) {
                    $('#plan_select_f').val('');
                    sAlert('warning', 'Insufficient Wallet Balance!', 'You do not have the required fund for this topup.');
                    return;
                }


                sConfirm(function () {
                    const formData = new FormData(form);
                    formData.append('action', 'submit_topup');
                    const btnContent = $('#topup_btn span').html();

                    const enableButton = () => {
                        $('#topup_btn span').html(btnContent);
                        enable_form(formSelector);
                        $(formSelector + ' .data_disabled').prop('disabled', true);
                    };

                    $.ajax({
                        url: "<?= current_url() ?>",
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function () {

                            $('#topup_btn span').html(spinnerLabel({ label: 'Processing Topup' }));
                            disable_form(formSelector);

                            //loading
                            sProcessingPopup('Processing Toupup...', 'Do not close this window!');
                        },
                        complete: function () {
                            enableButton();
                        },
                        success: function (res, textStatus, xhr) {

                            <?= !isProduction() ? 'console.log(res);' : '' ?>

                            if (xhr.status === 200) {

                                if (res.title && res.message)
                                    sAlert('success', res.title, res.message);

                                if (res.fWalletBalance && $('#wallet_balance').length > 0) {
                                    $('#wallet_balance').text(res.fWalletBalance);
                                }

                                if (res.walletBalance)
                                    currBal = res.walletBalance;

                                clearInputs(formSelector);
                                $('#user_name_f').removeClass('is-valid is-invalid');
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
                    { text: 'Are you sure you want to topup?' });


                return false;
            }
        });
    });
</script>
<?php $this->endSection() ?>