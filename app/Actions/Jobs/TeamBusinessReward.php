<?php

namespace App\Actions\Jobs;

use App\Enums\WalletTransactionCategory;
use App\Models\UserModel;
use App\Models\UserRewardsModel;
use App\Models\WalletModel;
use App\Twebsol\Plans;

class TeamBusinessReward
{

    private const REWARD_TYPE = 'team_business_reward';

    public function run()
    {
        $users = model(UserModel::class)->select(['id'])->where(['status' => 1])->findAll();

        // iterating over each user
        foreach ($users as $user) {

            $directChilds = model(UserModel::class)->getDirectUsersFromUserIdPk($user->id, ['id']);

            $powerLegInvestment = 0;
            $userTeamInvestment = 0;

            // iterative child of the user
            foreach ($directChilds as $childUser) {

                $childUserInvestment = model(WalletModel::class)->getUserTotalInvestment($childUser->id);

                $childTeamInvestment = model(UserModel::class)->getTeamInvestment($childUser->id, 9999999999); // infinite levels

                $legInvestment = $childUserInvestment + $childTeamInvestment;

                $userTeamInvestment += $legInvestment;

                if ($legInvestment > $powerLegInvestment)
                    $powerLegInvestment = $legInvestment;
            }


            // iterating over salary structure array
            foreach (Plans::REWARD_STRUCTURE as $rewardId => $reward) {

                $teamBusiness = $reward['team_business'];
                $income = $reward['income'];

                $halfTeamBusiness = $teamBusiness / 2;


                // checking if the user is already having this reward
                if (model(UserRewardsModel::class)->hasUserAlreadyAchieved($user->id, self::REWARD_TYPE, $rewardId))
                    continue;

                $hasRequiredBusinessMade = $userTeamInvestment >= $teamBusiness; // has required business made

                if (!$hasRequiredBusinessMade)
                    continue 2; // continue with next user

                // has half team business made from single leg
                $hasHalfBusinessMadeFromSingleLeg = $powerLegInvestment >= $halfTeamBusiness;

                $hasHalfBusinessMadeFromOtherLegs = ($userTeamInvestment - $powerLegInvestment) >= $halfTeamBusiness;

                if (!($hasHalfBusinessMadeFromSingleLeg && $hasHalfBusinessMadeFromOtherLegs))
                    continue 2; // continue with next user


                // eligible for reward
                $userRewardId = model(UserRewardsModel::class)->insert([
                    'user_id' => $user->id,
                    'reward_type' => self::REWARD_TYPE,
                    'reward_id' => $rewardId,
                    'details' => json_encode([
                        'reward' => $reward,
                        'team_investment' => $userTeamInvestment,
                        'power_leg_investment' => $powerLegInvestment
                    ])
                ], returnID: true);

                model(WalletModel::class)->deposit(
                    user_id_pk: $user->id,
                    amount: $income,
                    wallet_field: 'income',
                    category: WalletTransactionCategory::TEAM_BUSINESS_REWARD,
                    isEarning: true,
                    details: [
                        'user_reward_id' => $userRewardId
                    ]
                );


                addIncomeStat($user->id, $income, 'team_business_reward');

            }


        }
    }

}