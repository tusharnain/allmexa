<?php
$wallet_fields = \App\Services\WalletService::WALLETS;

$w_array = array();

foreach ($wallet_fields as $index => &$w) {

    $key = wallet_label($w) . ' : ' . wallet_famount($wallets->{$w}, $w);
    $w_array[$key] = $index;
}

?>

<?= admin_component('select', [
    'name' => 'wallet',
    'label' => 'Wallet',
    'options' => $w_array,
    'id' => 'select_wallet_field'
]) ?>