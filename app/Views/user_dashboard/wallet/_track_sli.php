<?php

$user = get_user($sli->level_user_id, ['user_id', 'full_name']);

$bv = f_amount(_c($sli->bv), isUser: true);

$rows = [
    ['Income', f_amount(_c($sli->amount), isUser: true)],
    ['Level', $sli->level],
    [label('user'), escape("$user->user_id ($user->full_name)")],
    ['BV', $sli->percent ? "{$sli->percent}% of $bv" : $bv],
    ['Date/Time', f_date($sli->created_at)],
];


?>

<div class="card mb-2 p-0" style="border-radius: 0;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td colspan="20" class="text-ld h6 py-3 text-center">
                            <i class="fa-solid fa-user me-2"></i> Sponsor Level Income
                        </td>
                    </tr>
                    <?php foreach ($rows as &$row): ?>
                        <tr>
                            <td class="text-ld">
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