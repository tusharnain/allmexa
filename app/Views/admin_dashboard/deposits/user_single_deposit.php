<?php
use App\Models\DepositModel;

$userLabel = label('user');

$isPendingDeposit = $deposit->status === DepositModel::DEPOSIT_STATUS_PENDING;


$select_array = [
    'Complete' => DepositModel::DEPOSIT_STATUS_COMPLETE,
    'Reject' => DepositModel::DEPOSIT_STATUS_REJECT,
];

?>

<?= $this->extend('admin_dashboard/_partials/app') ?>

<?= $this->section('style') ?>
<style>
    .deposit-receipt-image {
        max-width: 100%;
        width: 250px;
        height: 250px;
        object-fit: cover;
        margin-bottom: 10px;
    }
</style>
<?= $this->endSection() ?>


<?= $this->section('slot') ?>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>
                        Deposit Track Id :
                        <span class="fw-bold">
                            <?= $deposit->track_id ?>
                        </span>

                        <p class="fs-6 mt-1 text-secondary">
                            <?= f_date($deposit->created_at) ?>
                        </p>
                    </h5>
                </div>
                <div class="card-body">

                    <div id="dep_status_alert">
                        <?= view('admin_dashboard/deposits/__status_alert', [
                            'deposit_status' => $deposit->status
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
                        'label' => "Deposit Amount",
                        'value' => $deposit->amount ? f_amount($deposit->amount) : '',
                        'class' => 'bg-white fw-bold text-dark',
                        'bool_attributes' => 'disabled'
                    ]) ?>

                    <?= admin_component('input', [
                        'name' => 'utr',
                        'label' => "UTR",
                        'value' => $deposit->utr ? escape($deposit->utr) : '',
                        'class' => 'bg-white fw-bold text-dark',
                        'bool_attributes' => 'disabled'
                    ]) ?>

                    <?php
                    if ($deposit->receipt_file):
                        $url = route('admin.file', 'deposit-receipts', $deposit->receipt_file);
                        ?>
                        <div class="card fit-content">
                            <div class="card-header">
                                Receipt Image
                            </div>
                            <div class="card-body">
                                <img src="<?= $url ?>" alt="deposit_receipt" class="deposit-receipt-image">
                                <div>
                                    <a href="<?= $url ?>?d=1">
                                        <button class="btn btn-sm btn-success">
                                            <i class="mdi mdi-download"></i> Download
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>


                    <?= $deposit->remarks ? admin_component('textarea', [
                        'name' => 'remarks',
                        'label' => "$userLabel Remarks",
                        'value' => escape($deposit->remarks),
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
                        <?php if (($deposit->status !== DepositModel::DEPOSIT_STATUS_PENDING) and $deposit->admin_resolution_at): ?>
                            Resolved at
                            <?= f_date($deposit->admin_resolution_at) ?>
                        <?php else: ?>
                            Update Deposit Status
                        <?php endif; ?>
                    </span>
                </div>
                <div class="card-body">

                    <form id="admin_deposit_form">


                        <?= admin_component('select', [
                            'name' => 'status',
                            'label' => 'Deposit Status',
                            'options' => $select_array,
                            'empty_option' => $isPendingDeposit ? 'Select Deposit Status' : null,
                            'disable_empty_option' => $isPendingDeposit,
                            'select_empty_option' => $isPendingDeposit
                        ]) ?>


                        <?= ((!$isPendingDeposit and $deposit->admin_remarks) or $isPendingDeposit)
                            ? admin_component('textarea', [
                                'name' => 'remarks',
                                'label' => "Remarks",
                                'groupClass' => 'dep_admin_remarks',
                                'placeholder' => 'Optional (max 250 characters)',
                                'value' => $deposit->admin_remarks ?? '',
                            ]) : '' ?>

                        <?= $isPendingDeposit ? admin_component('checkbox', [
                            'name' => 'credit_to_wallet',
                            'label' => 'Credit deposit amount to ' . wallet_label('fund'),
                            'groupClass' => '_ras'
                        ]) : '' ?>

                        <?= $isPendingDeposit ? admin_component('button', [
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
<?php if ($isPendingDeposit): ?>
    <script>
        $(document).ready(function () {

            const formSelector = '#admin_deposit_form';
            var form_enabled = true;

            // validating
            validateForm(formSelector, {
                rules: {
                    status: { required: true },
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
                                        $('.dep_admin_remarks').remove();
                                    }

                                    if (res.html.status_alert) {
                                        $('#dep_status_alert').html(res.html.status_alert);
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

                    }, { text: "You want to update the deposit status?" });

                    return false;
                }
            });

        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function () {
            const formSelector = '#admin_deposit_form';
            disable_form(formSelector);
        });
    </script>
<?php endif; ?>
<?php $this->endSection() ?>