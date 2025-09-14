<?php
use App\Models\WithdrawalModel;

?>

<?php if ($withdrawal_status === WithdrawalModel::WD_STATUS_PENDING): ?>
    <?= admin_component('alert', ['type' => 'warning', 'text' => 'Pending Withdrawal!']) ?>
<?php elseif ($withdrawal_status === WithdrawalModel::WD_STATUS_COMPLETE): ?>
    <?= admin_component('alert', ['type' => 'success', 'text' => 'Completed Withdrawal!']) ?>
<?php elseif ($withdrawal_status === WithdrawalModel::WD_STATUS_CANCELLED): ?>
    <?= admin_component('alert', ['type' => 'secondary', 'text' => 'Cancelled Withdrawal!']) ?>
<?php else: ?>
    <?= admin_component('alert', ['type' => 'danger', 'text' => 'Rejected Withdrawal!']) ?>
<?php endif; ?>