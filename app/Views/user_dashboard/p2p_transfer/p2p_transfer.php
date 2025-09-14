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
                        <h4 class="counter" style="visibility: visible;">
                            <?= f_amount($walletBalance) ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">

            <div class="card">
                <div class="card-body">

                    <form id="p2p_transfer_form">
                        <?= csrf_field() ?>

                        <?= user_component('input', [
                            'name' => 'user_id',
                            'label' => $userIdLabel,
                            'placeholder' => "Enter $userIdLabel",
                            'id' => 'user_id_f'
                        ]) ?>

                        <?= user_component('input', [
                            'name' => 'user_name',
                            'label' => $userNameLabel,
                            'disabled' => true,
                            'class' => 'data_disabled',
                            'id' => 'user_name_f'
                        ]) ?>

                        <?= user_component('input', [
                            'type' => 'number',
                            'name' => 'amount',
                            'label' => 'Amount',
                            'placeholder' => "Enter Amount To Transfer",
                            'id' => 'amount_f'
                        ]) ?>

                        <?= user_component('textarea', [
                            'label' => 'Remarks',
                            'name' => 'remarks',
                            'placeholder' => 'Enter Transaction Remark (Optional, Max 250 Characters)',
                            'rows' => 3
                        ]) ?>

                        <div class="row">
                            <div class="col-md-3">
                                <?= user_component('input', [
                                    'name' => 'tpin',
                                    'label' => label('tpin'),
                                    'placeholder' => "Enter $tpinLabel"
                                ]) ?>
                            </div>
                        </div>


                        <?= user_component('button', [
                            'label' => 'Transfer Amount',
                            'icon' => 'fa-solid fa-circle-check',
                            'class' => 'mobile-button float-end',
                            'iconLast' => true,
                            'submit' => true,
                            'id' => 'p2p_transfer_button'
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

        Dashboard.setupP2pTransferForm({
            userIdLabel: '<?= label('user_id') ?>',
            currBal: <?= $walletBalance ?>,
            userNameApi: '<?= route('api.public.getUserNameFromUserId') ?>',
            transferPostUrl: '<?= route('user.p2pTransfer.transfer') ?>',
            userIdLengths: <?= json_encode(_setting('user_id_length_validation')) ?>,
            addDeductAmountRange: <?= json_encode(_setting('user_p2p_transfer_amount_range')) ?>,
            formSelector: '#p2p_transfer_form',
            tpin_digits: <?= _setting('tpin_digits', 6) ?>,
            isProduction: <?= isProduction() ? 'true' : 'false' ?>,
        });

    });

</script>


<?php $this->endSection() ?>