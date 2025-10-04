<?php

namespace App\Twebsol;

use App\Enums\RoiTypes;


final class Plans
{
    //! The Index must be unique
    //! Make sure plan title length must not exceed 200 characters
    public const array SPONSOR_LEVEL_INCOMES = [5];

    public const array DEFAULT_ROI_LEVEL_INCOME = [20, 10, 10, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5,];

    public const string DEFAULT_ROI_LEVEL_INCOME_TYPE = 'p'; // p for percent and f for fixed

    public const int TEAM_UPTO_LEVEL = 25;


    // Important note - Keep key id of both roi structure and reward structure same and in sync
    public const array SALARY_ROI_STRUCTURE = [
        1 => ['monthly_income' => 25, 'frequency' => 8],
        2 => ['monthly_income' => 50, 'frequency' => 10],
        3 => ['monthly_income' => 75, 'frequency' => 12],
        4 => ['monthly_income' => 100, 'frequency' => 15],
        5 => ['monthly_income' => 125, 'frequency' => 20],
        6 => ['monthly_income' => 240, 'frequency' => 25],
        7 => ['monthly_income' => 460, 'frequency' => 30],
        8 => ['monthly_income' => 1_100, 'frequency' => 35],
        9 => ['monthly_income' => 2_500, 'frequency' => 40]
    ];


    public const array REWARD_RANK = [
        1 => 'Bronze Director',
        2 => 'Silver Director',
        3 => 'Gold Director',
        4 => 'Diamond Director',
        5 => 'Platinum Director',
        6 => 'Emerald Director',
        7 => 'Sapphire Director',
        8 => 'Topaz Director',
        9 => 'Crown Director',
        10 => 'Brand Ambassador'
    ];


    // Reward Id => Reward
    public const array REWARD_STRUCTURE = [
        1 => ['reward_rank_id' => 1, 'team_business' => 2_000, 'income' => 50],
        2 => ['reward_rank_id' => 2, 'team_business' => 5_000, 'income' => 100],
        3 => ['reward_rank_id' => 3, 'team_business' => 10_000, 'income' => 250],
        4 => ['reward_rank_id' => 4, 'team_business' => 30_000, 'income' => 500],
        5 => ['reward_rank_id' => 5, 'team_business' => 60_000, 'income' => 1_000],
        6 => ['reward_rank_id' => 6, 'team_business' => 1_00_000, 'income' => 2_500],
        7 => ['reward_rank_id' => 7, 'team_business' => 3_00_000, 'income' => 5_000],
        8 => ['reward_rank_id' => 8, 'team_business' => 6_00_000, 'income' => 10_000],
        9 => ['reward_rank_id' => 9, 'team_business' => 10_00_000, 'income' => 25_000],
        10 => ['reward_rank_id' => 10, 'team_business' => 30_00_000, 'income' => 50_000]
    ];

    public const array DIRECT_AND_BUSINESS_BASED_SALARY_STRUCTURE = [
        1 => ['direct' => 10, 'direct_business' => 200, 'income' => 5, 'freq' => 30],
        2 => ['direct' => 20, 'direct_business' => 1000, 'income' => 10, 'freq' => 30],
        3 => ['direct' => 40, 'direct_business' => 2000, 'income' => 25, 'freq' => 30],
        4 => ['direct' => 80, 'direct_business' => 5000, 'income' => 200, 'freq' => 30],
        5 => ['direct' => 100, 'direct_business' => 10000, 'income' => 500, 'freq' => 30],
        6 => ['direct' => 200, 'direct_business' => 20000, 'income' => 1200, 'freq' => 30],
    ];

    public static function getDailyRoiPercentByUser(object $user, string|float $balance): float
    {
        $directTeamCount = user_model()->getDirectActiveUsersFromUserIdPk($user->id);
        $balanceStr = (string) $balance;

        // Check if user qualifies based on direct team count and business
        if ($directTeamCount >= 2) {
            $directBusiness = user_model()->getTotalDirectBusiness($user->id);
            $requiredBusiness = bcmul('2', $balanceStr, 8); // 2 * balance, high precision

            if (bccomp((string) $directBusiness, $requiredBusiness, 8) >= 0) {
                return 2.0;
            }
        }

        // Check if balance > 1000
        if (bccomp($balanceStr, '1000', 8) === 1) {
            return 2.0;
        }

        // Default ROI
        return 1.0;
    }

    public static function getDailyCompoundRoiPercentByUser(object $user, string|float $balance): float
    {
        return 1.0;
    }

    public static function getRewardRankName(string $rewardType, int $rewardId): ?string
    {
        if ($rewardType === 'team_business_reward') {
            return self::REWARD_RANK[self::REWARD_STRUCTURE[$rewardId]['reward_rank_id']] ?? null;
        }

        return null;
    }
}
