<?php

$qrWidth = 300;

$walletAddress = escape($data->wallet_address);
?>


<?= user_component('input_copy_text', [
    'name' => 'trc20',
    'label' => 'USDT TRC20 Wallet Address',
    'bool_attributes' => 'readonly disabled',
    'value' => $walletAddress
]) ?>


<div class="lazy-image-container">
    <img src="" data-src="<?= qr_url($walletAddress) ?>">
</div>