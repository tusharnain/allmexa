<?php

$rows = [
    ['Amount', f_amount(_c($withdrawal->amount), isUser: true)],
    ['Charges', f_amount(_c($withdrawal->charges), isUser: true)],
    ['Net Amount', f_amount(_c($withdrawal->net_amount), isUser: true)],
    ['Status', strtoupper($withdrawal->status)],
    ['Date/Time', f_date($withdrawal->created_at)],
];

?>

<div class="card p-0 mb-2" style="border-radius: 0;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td colspan="20" class="text-ld h6 py-3 text-center">
                            <i class="fa-solid fa-user me-2"></i> Withdrawal Details (
                            <?= $withdrawal->track_id ?>)
                        </td>
                    </tr>
                    <?php foreach ($rows as &$row): ?>
                        <tr>
                            <td class="text-ld" scope="row">
                                <?= $row[0] ?>
                            </td>
                            <td class="text-ld fw-bold">
                                <?= $row[1] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>