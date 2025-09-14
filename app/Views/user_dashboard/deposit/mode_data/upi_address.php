<?php

$qrWidth = 300;

$address = escape($data->address);
?>


<?= user_component('input_copy_text', [
    'name' => 'upi',
    'label' => 'UPI Address',
    'bool_attributes' => 'readonly disabled',
    'value' => $address
]) ?>

<img class="img-fluid" src="" />