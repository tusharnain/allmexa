<?php

$planLabel = label('plan');
?>


<?= $this->extend('user_dashboard/layout/master') ?>

<?= $this->section('slot') ?>

<div class="container-fluid">
    <div class="row">
            <?= user_component('alert', [
                'icon' => 'fa-solid fa-exclamation-circle',
                'type' => 'warning',
                'text' => "You have not subscribed to any ROI $planLabel."
            ]) ?>
    </div>
</div>


<?= $this->endSection() ?>