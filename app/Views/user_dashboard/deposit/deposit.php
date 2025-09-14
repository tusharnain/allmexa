<?php

$currencySymbol = 'fa-solid fa-coins';

?>

<?= $this->extend('user_dashboard/layout/master') ?>

<?= $this->section('slot') ?>
<div class="container-fluid">
    <h5 class="mb-3 ms-2 main-label" style="display:none;">
        Select Deposit Mode
    </h5>

    <div class="row" id="mode-widgets-container">
    </div>

    <?= user_component('empty_modal', [
        'id' => 'deposit_modal',
        'noCloseButton' => true,
        'static' => true
    ]) ?>
</div>

<?= $this->endSection() ?>

<?php $this->section('script') ?>
<script>

    <?php

    $amtRange = _setting('deposit_amount_range', [100, 100000]);
    $amtRangeStart = intval(_c($amtRange[0]));
    $amtRangeEnd = intval(_c($amtRange[1]));

    ?>

    const depositModes = <?= json_encode($depositModes); ?>;
    const depositAmountRange = <?= json_encode([$amtRangeStart, $amtRangeEnd]); ?>;

    $(document).ready(function () {

        let depModes = [];
        let totalModes = 0;
        let html = '';
        const currencySymbol = "<?= $currencySymbol ?>";

        depositModes.forEach(function (mode) {
            totalModes++;

            html += `<div class="col-md-4">
                    <div class="card shining-card">
                    <div class="card-body">
                        <i class="widget-1-i ${currencySymbol} me-2"></i>
                        
                        <span class="fs-5 me-2 dw_label">${mode.title}</span>
                        <svg width="36" height="35" viewBox="0 0 36 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.86124 21.6224L11.2734 16.8577C11.6095 16.6417 12.041 16.6447 12.3718 16.8655L18.9661 21.2663C19.2968 21.4871 19.7283 21.4901 20.0644 21.2741L27.875 16.2534" stroke="#BFBFBF" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M26.7847 13.3246L31.6677 14.0197L30.4485 18.7565L26.7847 13.3246ZM30.2822 19.4024C30.2823 19.4023 30.2823 19.4021 30.2824 19.402L30.2822 19.4024ZM31.9991 14.0669L31.9995 14.0669L32.0418 13.7699L31.9995 14.0669C31.9994 14.0669 31.9993 14.0669 31.9991 14.0669Z" fill="#BFBFBF" stroke="#BFBFBF"></path>
                        </svg>
                        <div class="pt-3">
                                <a class="btn btn-light f-light mt-3 fit-content" deposit-data-name="${mode.title}">
                                    Deposit
                                    <span class="ms-2">
                                        <i class="fa-solid fa-arrow-right mb-2"></i>
                                    </span>
                                </a>
                        </div>
                    </div>
                </div>
                </div>`;
        });
        html = html.trim();
        if (html.length > 0) {
            $('#mode-widgets-container').html(html);
        }

        if (totalModes > 0) {
            $('.main-label').show();
        }

        const amountInputField = `<form method="POST" onsubmit="submitDepForm(event);" id="depForm"><input type="hidden" name="mode" id="dep_mode_inp" /><?= csrf_field() . user_component('input', [
            'name' => 'amount',
            'label' => 'Deposit Amount',
            'placeholder' => 'Enter amount to deposit.',
            'id' => 'amount_f'
        ]) . user_component('button', [
                'label' => 'Deposit',
                'class' => 'btn-block mobile-button float-end',
                'icon' => 'fa-solid fa-bolt',
                'submit' => true,
            ]) ?></form>`;

        depositModes.forEach(function (mode) {
            $(`[deposit-data-name="${mode.title}"]`).on('click', function () {

                $('#deposit_modal_title').text(mode.title);
                $('#deposit_modal_body').html(amountInputField);
                $('#dep_mode_inp').val(mode.name);

                $('#deposit_modal').modal('show');

            });
        });

    });

    function submitDepForm(e) {
        e.preventDefault();
        const form = document.getElementById('depForm');

        const formData = new FormData(form);

        const data = {};
        formData.forEach((value, key) => {
            data[key] = value.trim();
        });

        if (!data.amount || data.amount.trim() == "")
            return makeFieldInvalid('#amount_f', 'Deposit amount is required.');
        const amount = data.amount;
        if (isNaN(Number(amount)))
            return makeFieldInvalid('#amount_f', 'A valid amount is required.');
        if (amount < depositAmountRange[0])
            return makeFieldInvalid('#amount_f', `Minimum amount for deposit is ${depositAmountRange[0]}.`);
        if (amount > depositAmountRange[1])
            return makeFieldInvalid('#amount_f', `Maximum amount for deposit is ${depositAmountRange[1]}.`);

        form.removeEventListener('submit', submitDepForm);
        form.submit();
    }
</script>
<?php $this->endSection() ?>