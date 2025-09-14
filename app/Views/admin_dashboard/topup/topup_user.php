<?php


$userLabel = label('user');
$userIdLabel = label('user_id');
$userNameLabel = label('user_name');

?>

<?= $this->extend('admin_dashboard/_partials/app') ?>

<?= $this->section('slot') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">

                    <form id="topup_form">
                        <?= csrf_field() ?>

                        <?= admin_component('input', [
                            'name' => 'user_id',
                            'label' => $userIdLabel,
                            'placeholder' => "Enter $userIdLabel",
                            'id' => 'user_id_f'
                        ]) ?>

                        <?= admin_component('input', [
                            'name' => 'user_name',
                            'label' => $userNameLabel,
                            'disabled' => true,
                            'class' => 'data_disabled',
                            'id' => 'user_name_f'
                        ]) ?>

                        <?= admin_component('input', [
                            'name' => 'amount',
                            'label' => 'Amount',
                            'placeholder' => 'Enter Topup Amount',
                            'type' => 'number',
                            'id' => 'amount_f'
                        ]) ?>

                        <?= admin_component('button', [
                            'label' => 'Submit Topup',
                            'icon' => 'mdi mdi-check',
                            'class' => 'mobile-button float-end mt-2',
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


        /*
      *------------------------------------------------------------------------------------
      * User Name Fetch
      *------------------------------------------------------------------------------------
      */
        $('#user_id_f').on('change', function () {

            const user_id = $(this).val();
            const api = "<?= route('api.public.getUserNameFromUserId') ?>";

            AdminDashboard.fetchAndSetUsernameToDom({
                user_id,
                api
            });
        });



        /*
    *------------------------------------------------------------------------------------
    * Topup Form
    *------------------------------------------------------------------------------------
    */
        // form selector
        const formSelector = '#topup_form';
        const userIdLengths = <?= json_encode(_setting('user_id_length_validation')) ?>;
        const topup_range = <?= json_encode(_setting('topup_amount_range')) ?>;
        const topup_multiple_of = <?= _setting('topup_amount_multiple_of') ?? "null" ?>;

        const amountRule = { required: true, number: true, min: topup_range[0], max: topup_range[1] };
        if (topup_multiple_of)
            amountRule.multipleOf = topup_multiple_of

        // validating
        validateForm(formSelector, {
            rules: {
                user_id: { required: true, alpha_num: true, minlength: userIdLengths[0], maxlength: userIdLengths[1] },
                amount: amountRule
            },
            submitHandler: function (form) {


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
                        url: "<?= route('admin.topup.topupUser') ?>",
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