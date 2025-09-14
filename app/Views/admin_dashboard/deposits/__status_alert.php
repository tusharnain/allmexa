<?php
use App\Models\DepositModel;

?>

<?php if ($deposit_status === DepositModel::DEPOSIT_STATUS_PENDING): ?>
    <?= admin_component('alert', ['type' => 'warning', 'text' => 'Pending Deposit!']) ?>
<?php elseif ($deposit_status === DepositModel::DEPOSIT_STATUS_COMPLETE): ?>
    <?= admin_component('alert', ['type' => 'success', 'text' => 'Completed Deposit!']) ?>
<?php else: ?>
    <?= admin_component('alert', ['type' => 'danger', 'text' => 'Rejected Deposit!']) ?>
<?php endif; ?>