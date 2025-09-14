<?php
// Component only meant for logged in user

if (!isset($hasTpin) or !is_bool($hasTpin))
    $hasTpin = user_model()->hasTpin(user('id'));

?>

<?php if (!$hasTpin): ?>
    <div class="alert alert-danger mb-3" role="alert" id="np_tpin_alert">
        <i class="mdi mdi-alert me-2"></i>
        <?= label('tpin') ?> is not set up. Please set up for secure transactions.
    </div>
<?php endif; ?>