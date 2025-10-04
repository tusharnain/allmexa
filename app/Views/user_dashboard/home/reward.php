<?php

use App\Twebsol\Plans;
use App\Models\UserRewardsModel;


$rewardsModel = new UserRewardsModel;
$user_id_pk = user('id');

$rewards = [];

$userRewards = $rewardsModel->getAllUserRewards($user_id_pk, 'team_business_reward');

foreach (Plans::REWARD_STRUCTURE as $rid => $rew) {
    $user_rew = array_filter($userRewards, function (object $r) use ($rid) {
        return $r->reward_id == $rid;
    });

    $user_rew = array_values($user_rew);
    $rewards[] = [
        ...array_merge($rew, ['user_reward' => !empty($user_rew) ? $user_rew[0] : null]),
        ...[
            'rank' => Plans::REWARD_RANK[$rew['reward_rank_id']]
        ]
    ];

}

?>

<div class="card">
    <div class="card-header">
        <h6>Rewards</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-nowrap">
                <thead>
                    <tr>
                        <th>
                            S.No
                        </th>
                        <th>
                            Rank
                        </th>
                        <th>
                            <i class="fa-solid fa-arrow-right"></i>
                            Team Business
                        </th>
                        <th>
                            <i class="fa-solid fa-arrow-right"></i>
                            Power Leg Business (50:50)
                        </th>
                        <th>
                            <i class="fa-solid fa-arrow-right"></i>
                            One Time Reward
                        </th>
                        <th>
                            <i class="fa-solid fa-arrow-right"></i>
                            Achieved
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($rewards as $index => $reward):

                        $userReward = $reward['user_reward'] ?? null;

                        ?>
                        <tr>
                            <td>
                                <?= $index + 1 ?>
                            </td>
                            <td>
                                <?= $reward['rank'] ?>
                            </td>
                            <td>
                                <?= f_amount($reward['team_business']) ?>
                            </td>
                            <td>
                                <?= f_amount($reward['team_business'] / 2) ?>
                            </td>
                            <td>
                                <?= f_amount($reward['income']) ?>
                            </td>
                            <td>
                                <?php if ($userReward): ?>
                                    <i class="fa-solid fa-check-circle text-success"></i>
                                    <?= date('j M Y', strtotime($userReward->created_at)) ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>

</div>