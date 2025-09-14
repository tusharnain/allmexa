<?php

use App\Actions\Jobs\DailyTopupBonus;
use App\Models\UserModel;
use App\Models\WalletModel;

$db = \Config\Database::connect();

// Get the current time
$now = time();

// Check if today's 4 AM has passed
if (date('H') >= 4) {
    // If current time is after 4 AM, last 4 AM is today
    $end = date('Y-m-d 04:00:00');
} else {
    // If current time is before 4 AM, last 4 AM is yesterday
    $end = date('Y-m-d 04:00:00', strtotime('yesterday'));
}

// Start is 24 hours before that
$start = date('Y-m-d H:i:s', strtotime('-1 day', strtotime($end)));

$records = $db->table('daily_topup_bonanza_incomes')
    ->select([
        'daily_topup_bonanza_incomes.*',
        'users.id as user_id_pk',
        'users.user_id as user_id',
        'users.profile_picture as profile_picture',
    ])
    ->join('users', 'daily_topup_bonanza_incomes.user_id = users.id')
    ->where('daily_topup_bonanza_incomes.created_at >=', $start)
    ->where('daily_topup_bonanza_incomes.created_at <', $end)
    ->orderBy('daily_topup_bonanza_incomes.created_at', 'ASC')
    ->orderBy('id', 'ASC')
    ->get()
    ->getResult();



?>



<div class="card">
    <div class="card-header">
        <h6>
            Topper(<?= count(DailyTopupBonus::CTO_STRUCTURE) ?>) Self Business Daily Deposit Bonanza
            <div class="badge bg-dark text-white h5">
                CTO (<?= array_sum(DailyTopupBonus::CTO_STRUCTURE) ?>%)
            </div>
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-nowrap">
                <tbody>

                    <?php if (!empty($records)): ?>

                        <?php foreach ($records as $i => $record): ?>
                            <?php

                            $user = new \stdClass();
                            $user->id = $record->user_id_pk;
                            $user->user_id = $record->user_id;
                            $user->profile_picture = $record->profile_picture;

                            $selfBusiness = model(WalletModel::class)
                                ->getUserTotalInvestment($user->id);
                            ?>

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="h5 text-dark me-3">
                                            <?= $i + 1 ?>
                                        </div>
                                        <img width="50px" height="50px" src="<?= UserModel::getAvatar($user) ?>"
                                            alt="profile picture">
                                        <div class="ms-3">
                                            <div class="h5 text-dark">
                                                <?= $user->user_id ?>
                                                <div class="badge bg-dark text-white">
                                                    CTO ( <?= $record->percent ?>%)
                                                </div>
                                            </div>
                                            <div>
                                                Self Business : <?= f_amount($selfBusiness) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        0 Achieved
                    <?php endif; ?>
                    <tr></tr>
                </tbody>
            </table>
        </div>

    </div>
</div>