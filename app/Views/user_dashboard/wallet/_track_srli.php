<?php

$user = get_user($srli->level_user_id, ['user_id', 'full_name']);

$roiBv = f_amount(_c($srli->roi_bv), isUser: true);

$rows = [
    ['Income', f_amount(_c($srli->amount), isUser: true)],
    ['Level', $srli->level],
    [label('user'), escape("$user->user_id ($user->full_name)")],
    ['ROI BV', "{$srli->percent}% of $roiBv"],
    ['Date/Time', f_date($srli->created_at)],
];


?>

<div class="card mb-2 p-0" style="border-radius: 0;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td colspan="20" class="text-ld h6 py-3 text-center">
                            <i class="fa-solid fa-user me-2"></i> Sponsor ROI Level Income
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