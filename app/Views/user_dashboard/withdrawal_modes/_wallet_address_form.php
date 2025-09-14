<?php

$isWalletLocked = (isset($wallet->locked) and $wallet->locked);

if (isset($wallet->updated_at))
    $wallet->updated_at = f_date($wallet->updated_at);

?>


<?= user_component('alert', [
    'icon' => 'fa-solid fa-exclamation-circle',
    'text' => isset($wallet->updated_at) ? "Wallet Details last updated on $wallet->updated_at." : '',
    'id' => 'wallet_updated_alert',
    'hidden' => !isset($wallet->updated_at)
]) ?>

<form id="wallet-form">
    <?= user_component('input', [
        'name' => 'trc20_address',
        'label' => 'BEP20 Address',
        'placeholder' => 'Enter BEP20 Wallet Address.',
        'value' => isset($wallet->trc20_address) ? escape($wallet->trc20_address) : ''
    ]) ?>


    <?php if (!$isWalletLocked): ?>
        <div class="col-xl-2 col-lg-3 col-md-4 px-0 mt-3">
            <?= user_component('input', [
                'name' => 'tpin',
                'id' => 'tpin_inp',
                'label' => label('tpin'),
                'placeholder' => 'Enter ' . label('tpin'),
            ]) ?>
        </div>

        <?= user_component('button', [
            'label' => 'Save Wallet',
            'class' => 'mobile-button float-end',
            'icon' => 'fa-solid fa-wallet',
            'id' => 'wallet_btn',
            'submit' => true
        ]) ?>
    <?php endif; ?>
</form>