<?php

$user = get_user($topup->user_id, columns: ['user_id', 'full_name']);
$rows = [
    [label('user'), escape("$user->user_id ($user->full_name)")],
    ["Amount", f_amount(_c($topup->amount), isUser: true)],
    ['Date/Time', f_date($topup->created_at)],
];
?>

<div class="card p-0 mb-2" style="border-radius: 0;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td colspan="20" class="text-ld h6 py-3 text-center">
                            <i class="fa-solid fa-user me-2"></i> Topup Details (
                            <?= $topup->track_id ?>)
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