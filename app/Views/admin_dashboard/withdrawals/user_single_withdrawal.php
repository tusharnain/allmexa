<?php
use App\Models\WithdrawalModel;

$userLabel = label('user');

$isPendingWithdrawal = $withdrawal->status === WithdrawalModel::WD_STATUS_PENDING;


$select_array = [
    'Complete' => WithdrawalModel::WD_STATUS_COMPLETE,
    'Reject' => WithdrawalModel::WD_STATUS_REJECT,
    'Cancel' => WithdrawalModel::WD_STATUS_CANCELLED,
];

?>

<?= $this->extend('admin_dashboard/_partials/app') ?>


<?= $this->section('slot') ?>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>
                        Withdrawal Track Id :
                        <span class="fw-bold">
                            <?= $withdrawal->track_id ?>
                        </span>

                        <p class="fs-6 mt-1 text-secondary">
                            <?= f_date($withdrawal->created_at) ?>
                        </p>
                    </h5>
                </div>
                <div class="card-body">

                    <div id="wd_status_alert">
                        <?= view('admin_dashboard/withdrawals/__status_alert', [
                            'withdrawal_status' => $withdrawal->status
                        ]) ?>
                    </div>


                    <?= admin_component('input', [
                        'name' => 'name',
                        'label' => "$userLabel Details",
                        'value' => "{$user->full_name} ({$user->user_id})",
                        'class' => 'bg-white fw-bold text-dark',
                        'bool_attributes' => 'disabled'
                    ]) ?>


                    <?= admin_component('input', [
                        'name' => 'amount',
                        'label' => "Withdrawal Amount",
                        'value' => f_amount($withdrawal->amount),
                        'class' => 'bg-white fw-bold text-dark',
                        'bool_attributes' => 'disabled'
                    ]) ?>

                    <?= admin_component('input', [
                        'name' => 'net_amount',
                        'label' => "Net Amount",
                        'value' => f_amount($withdrawal->net_amount),
                        'class' => 'bg-white fw-bold text-dark',
                        'bool_attributes' => 'disabled'
                    ]) ?>

                    <?= admin_component('input', [
                        'name' => 'charges',
                        'label' => "Charges",
                        'value' => f_amount($withdrawal->charges),
                        'class' => 'bg-white fw-bold text-dark',
                        'bool_attributes' => 'disabled'
                    ]) ?>


                    <?= $withdrawal->remarks ? admin_component('textarea', [
                        'name' => 'remarks',
                        'label' => "$userLabel Remarks",
                        'value' => escape($withdrawal->remarks),
                        'bool_attributes' => 'disabled',
                        'class' => 'bg-white'
                    ]) : '' ?>


                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <span id="admin_card_title" class="text-dark fw-bold">
                        <?php if (!$isPendingWithdrawal and $withdrawal->admin_resolution_at): ?>
                            Resolved at
                            <?= f_date($withdrawal->admin_resolution_at) ?>
                        <?php else: ?>
                            Update Withdrawal Status
                        <?php endif; ?>
                    </span>
                </div>
                <div class="card-body">

                    <p class="text-danger">
                        * Cancelling a withdrawal will refund the amount in
                        <?= $userLabel ?>'s wallet.
                    </p>


                    <form id="admin_wd_form">

                        <?= admin_component('select', [
                            'name' => 'status',
                            'label' => 'Withdrawal Status',
                            'options' => $select_array,
                            'empty_option' => $isPendingWithdrawal ? 'Select Withdrawal Status' : null,
                            'disable_empty_option' => $isPendingWithdrawal,
                            'select_empty_option' => $isPendingWithdrawal,
                            'select' => $withdrawal->status ?? null,
                            'id' => 'wd_status_select'
                        ]) ?>

                        <div id="utr_input" <?= (!$isPendingWithdrawal and $withdrawal->utr) ? '' : 'style="display:none;"' ?>>
                            <?= admin_component('input', [
                                'name' => 'utr',
                                'label' => 'UTR',
                                'placeholder' => 'Enter UTR.',
                                'value' => $withdrawal->utr ? escape($withdrawal->utr) : ''
                            ]) ?>
                        </div>

                        <?= ((!$isPendingWithdrawal and $withdrawal->admin_remarks) or $isPendingWithdrawal)
                            ? admin_component('textarea', [
                                'name' => 'remarks',
                                'label' => "Remarks",
                                'groupClass' => 'wd_admin_remarks',
                                'placeholder' => 'Optional (max 250 characters)',
                                'value' => $withdrawal->admin_remarks ?? '',
                            ]) : '' ?>

                        <?= $isPendingWithdrawal ? admin_component('button', [
                            'label' => 'Submit',
                            'icon' => 'mdi mdi-content-save',
                            'class' => '_ras update_status_btn float-end mt-2',
                            'submit' => true
                        ]) : '' ?>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>



<?php $this->section('script') ?>
<?php if ($isPendingWithdrawal): ?>
    <script>
        $.validator.addMethod("required_if", function (value, element, params) {
            var dataFieldName = params[0];
            var expectedValue = params[1];
            var dataFieldValue = $('[name="' + dataFieldName + '"]').val();
            if (dataFieldValue === expectedValue) {
                return $.validator.methods.required.call(this, value, element, true);
            }
            return true;
        }, "This field is required.");

        $(document).ready(function () {

            const formSelector = '#admin_wd_form';
            const wdCompleteStatus = '<?= WithdrawalModel::WD_STATUS_COMPLETE ?>';
            var form_enabled = true;

            // validating
            validateForm(formSelector, {
                rules: {
                    status: { required: true },
                    utr: { required_if: ['status', 'complete'], minlength: 5, maxlength: 100 },
                    remarks: { required: false, minlength: 10, maxlength: 250 },
                },
                submitHandler: function (form) {

                    sConfirm(function () {

                        const formData = new FormData(form);
                        append_csrf(formData);
                        formData.append('action', 'update_status');

                        const btnContent = $('.update_status_btn span').html();

                        const enableButton = () => {
                            if ($('.update_status_btn span').length > 0)
                                $('.update_status_btn span').html(btnContent);

                            form_enabled && enable_form(formSelector);
                        };


                        $.ajax({
                            url: '<?= current_url() ?>',
                            method: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            beforeSend: function () {

                                $('.update_status_btn span').html(spinnerLabel());

                                disable_form(formSelector);
                            },
                            complete: function () {
                                enableButton();
                            },
                            success: function (res, textStatus, xhr) {

                                <?= !isProduction() ? 'console.log(res);' : '' ?>

                                if (xhr.status === 200) {

                                    form_enabled = false;

                                    if (!res.info.remarks_given) {
                                        $('.wd_admin_remarks').remove();
                                    }

                                    if (res.html.status_alert) {
                                        $('#wd_status_alert').html(res.html.status_alert);
                                    }

                                    if (res.info.f_admin_resolution_at) {
                                        $('#admin_card_title').text(`Resolved at ${res.info.f_admin_resolution_at}`);
                                    }

                                    if (res.message) {
                                        sAlert('success', '', res.message);
                                    }

                                    $('._ras').remove();
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

                    }, { text: "You want to update the withdrawal status?" });

                    return false;
                }
            });


            $('#wd_status_select').on('change', function () {
                const utrInputField = '#utr_input';
                if ($(this).val() === wdCompleteStatus) {
                    makeFieldValid(`${utrInputField} input`);
                    $(utrInputField).show();
                } else {
                    $(utrInputField).find('input').val('');
                    $(utrInputField).hide();
                }
            });

        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function () {
            const formSelector = '#admin_wd_form';
            disable_form(formSelector);
        });
    </script>
<?php endif; ?>
<?php $this->endSection() ?>