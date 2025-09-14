<?php
$tpinLabel = label('tpin');
$tpinLabelShort = label('tpin', 1);
?>

<div class="card">
    <div class="card-header bg-danger text-white">
        Set/Change
        <?= $tpinLabel ?>
    </div>
    <div class="card-body pb-0">

        <form id="change_tpin_form">
            <?= csrf_field() ?>
            <?= admin_component('input', [
                'type' => 'password',
                'name' => 'ntpin',
                'label' => "New $tpinLabel",
                'class' => '_ntpin',
                'placeholder' => "Enter new $tpinLabelShort"
            ]) ?>

            <?= admin_component('input', [
                'type' => 'password',
                'name' => 'cntpin',
                'label' => "Confirm New $tpinLabel",
                'placeholder' => "Enter new $tpinLabelShort again"
            ]) ?>

            <?= admin_component('button', [
                'label' => 'Update',
                'submit' => true,
                'icon' => 'mdi mdi-lock-reset',
                'color' => 'danger',
                'class' => 'float-end',
                'id' => 'change_tpin_btn'
            ]) ?>
        </form>

    </div>
</div>