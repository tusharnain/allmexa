<?php

use App\Models\WithdrawalModel;

?>

<div class="table-responsive">
    <table class="table table-bordered text-nowrap">
        <tbody>
            <tr>
                <td class="text-ld">
                    Track Id
                </td>
                <td class="text-ld text-end fw-bold">
                    <?= $withdrawal->track_id ?>
                </td>
            </tr>
            <tr>
                <td class="text-ld">
                    Withdrawal Amount
                </td>
                <td class="text-ld text-end fw-bold">
                    <?= f_amount(_c($withdrawal->amount), isUser: true) ?>
                </td>
            </tr>
            <tr>
                <td class="text-ld">
                    Net Amount
                </td>
                <td class="text-ld text-end fw-bold">
                    <?= f_amount(_c($withdrawal->net_amount), isUser: true) ?>
                </td>
            </tr>
            <?php if ($withdrawal->remarks): ?>
                <tr>
                    <td class="text-ld">
                        Remarks
                    </td>
                    <td class="text-ld text-end">
                        <?= user_component('textarea', [
                            'name' => 'admin_remarks',
                            'value' => escape($withdrawal->remarks),
                            'bool_attributes' => 'disabled'
                        ]) ?>
                    </td>
                </tr>
            <?php endif; ?>
            <tr>
                <td class="text-ld">
                    Status
                </td>
                <td class="text-ld text-end fw-bold">
                    <?php
                    if ($withdrawal->status === WithdrawalModel::WD_STATUS_PENDING) {
                        $color = 'warning';
                    } else if ($withdrawal->status === WithdrawalModel::WD_STATUS_COMPLETE) {
                        $color = 'success';
                    } else if ($withdrawal->status === WithdrawalModel::WD_STATUS_CANCELLED) {
                        $color = 'secondary';
                    } else {
                        $color = 'danger';
                    }
                    ?>
                    <span class="badge rounded-pill badge-<?= $color ?>">
                        <?= ucfirst($withdrawal->status) ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="text-ld">
                    Withdrawal At
                </td>
                <td class="text-ld text-end fw-bold">
                    <?= f_date($withdrawal->created_at) ?>
                </td>
            </tr>
            <?php if ($withdrawal->admin_resolution_at): ?>
                <tr>
                    <td class="text-ld">
                        Resolved at
                    </td>
                    <td class="text-ld text-end fw-bold">
                        <?= f_date($withdrawal->admin_resolution_at) ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if (($withdrawal->status !== WithdrawalModel::WD_STATUS_PENDING) and $withdrawal->utr): ?>
                <tr>
                    <td class="text-ld">
                        UTR
                    </td>
                    <td class="text-ld text-end">
                        <?= escape($withdrawal->utr) ?>
                    </td>
                </tr>
            <?php endif; ?>

            <?php if (($withdrawal->status !== WithdrawalModel::WD_STATUS_PENDING) and $withdrawal->admin_remarks): ?>
                <tr>
                    <td class="text-ld">
                        Admin Remarks
                    </td>
                    <td class="text-ld">
                        <?= user_component('textarea', [
                            'name' => 'admin_remarks',
                            'value' => escape($withdrawal->admin_remarks),
                            'bool_attributes' => 'disabled'
                        ]) ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>