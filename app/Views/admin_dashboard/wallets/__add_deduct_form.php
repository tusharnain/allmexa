<?php

$userLabel = label('user');
$incomeWalletLabel = wallet_label('income');

?>


<div class="card" id="add_deduct_view">
    <div class="card-header bg-primary text-white">
        Add/Deduct Wallet Form
    </div>
    <div class="card-body">

        <form id="add_deduct_form">
            <?= csrf_field() ?>

            <?= admin_component('input', [
                'name' => '_user_id',
                'label' => label('user_id'),
                'class' => '_user_id',
                'value' => $user->user_id,
                'bool_attributes' => 'disabled readonly',
            ]) ?>

            <?= admin_component('input', [
                'name' => '_user_name',
                'label' => label('user_name'),
                'class' => '_user_name',
                'value' => $user->full_name,
                'bool_attributes' => 'disabled readonly'
            ]) ?>

            <div id="wallet_select_container">
                <?= view('admin_dashboard/wallets/mini/_add_deduct_wallet_select', ['wallets' => $wallets]); ?>
            </div>

            <?= admin_component('select', [
                'name' => 'type',
                'label' => 'Type',
                'options' => [
                    'Credit (+)' => 'credit',
                    'Debit (-)' => 'debit'
                ]
            ]) ?>

            <?= admin_component('input', [
                'name' => 'amount',
                'label' => 'Amount',
                'type' => 'number',
                'placeholder' => 'Enter Amount'
            ]) ?>


            <?= admin_component('textarea', [
                'name' => 'remarks',
                'label' => "Remarks",
                'class' => 'no-resize',
                'placeholder' => "Enter Remarks (optional)",
            ]) ?>



            <?= admin_component('checkbox', [
                'label' => "Mark transaction as $userLabel earning (Only applicable for $incomeWalletLabel)",
                'name' => 'is_earning'
            ]) ?>


            <div>
                <?= admin_component('button', [
                    'label' => 'Submit',
                    'class' => 'float-end',
                    'icon' => 'fas fa-angle-double-right',
                    'iconLast' => true,
                    'id' => 'add_deduct_btn',
                    'submit' => true
                ]) ?>
            </div>

            <input type="hidden" class="_user_id" name="user_id" value="<?= $user->user_id ?>">
        </form>

    </div>
</div>