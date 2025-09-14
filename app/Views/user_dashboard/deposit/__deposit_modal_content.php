<?php

use App\Models\DepositModel;

?>

<div class="table-responsive">
    <table class="table table-bordered text-nowrap">
        <tbody>
            <tr>
                <td class="text-ld">
                    Track Id
                </td>
                <td class="text-ld text-end fw-bold">
                    <?= $deposit->track_id ?>
                </td>
            </tr>
            <tr>
                <td class="text-ld">
                    Deposit Amount
                </td>
                <td class="text-ld text-end fw-bold">
                    <?= f_amount($deposit->amount, symbol: '$') ?>
                </td>
            </tr>
            <tr>
                <td class="text-ld">
                    Deposit Mode
                </td>
                <td class="text-ld text-end fw-bold">
                    <?= $deposit_model->getDepositNameFromIdPk($deposit->deposit_mode_id) ?>
                </td>
            </tr>
            <tr>
                <td class="text-ld">
                    UTR
                </td>
                <td class="text-ld text-end fw-bold">
                    <?= escape($deposit->utr) ?>
                </td>
            </tr>
            <?php if ($deposit->receipt_file):
                $url = route('user.file', 'deposit-receipts', $deposit->receipt_file);
                ?>
                <tr>
                    <td class="text-ld">
                        Receipt
                    </td>
                    <td class="text-ld text-end">
                        <a href="<?= $url ?>" target="_blank">
                            <img class="deposit-receipt" src="<?= $url ?>" alt="receipt">
                        </a>
                        <div>
                            <a class="h5" href="<?= $url . '?d=1' ?>">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if ($deposit->remarks): ?>
                <tr>
                    <td class="text-ld">
                        Remarks
                    </td>
                    <td class="text-ld text-end">
                        <?= user_component('textarea', [
                            'name' => 'admin_remarks',
                            'value' => escape($deposit->remarks),
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
                    if ($deposit->status === DepositModel::DEPOSIT_STATUS_PENDING) {
                        $color = 'warning';
                    } else if ($deposit->status === DepositModel::DEPOSIT_STATUS_COMPLETE) {
                        $color = 'success';
                    } else {
                        $color = 'danger';
                    }
                    ?>
                    <span class="badge rounded-pill badge-<?= $color ?>">
                        <?= ucfirst($deposit->status) ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="text-ld">
                    Deposited At
                </td>
                <td class="text-ld text-end fw-bold">
                    <?= f_date($deposit->created_at) ?>
                </td>
            </tr>
            <?php if ($deposit->admin_resolution_at): ?>
                <tr>
                    <td class="text-ld">
                        Deposit Resolved at
                    </td>
                    <td class="text-ld text-end fw-bold">
                        <?= f_date($deposit->admin_resolution_at) ?>
                    </td>
                </tr>
            <?php endif; ?>

            <?php if (($deposit->status !== DepositModel::DEPOSIT_STATUS_PENDING) and $deposit->admin_remarks): ?>
                <tr>
                    <td class="text-ld">
                        Admin Remarks
                    </td>
                    <td class="text-ld">
                        <?= user_component('textarea', [
                            'name' => 'admin_remarks',
                            'value' => escape($deposit->admin_remarks),
                            'bool_attributes' => 'disabled'
                        ]) ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>