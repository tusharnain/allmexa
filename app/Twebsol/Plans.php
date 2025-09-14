<?php

namespace App\Twebsol;

use App\Enums\RoiTypes;


final class Plans
{
    //! The Index must be unique
    //! Make sure plan title length must not exceed 200 characters
    public const array SPONSOR_LEVEL_INCOMES = [5, 2, 1];

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


    // Reward Id => Reward
    public const array REWARD_STRUCTURE = [
        1 => ['rank' => 'Sales Manager', 'team_business' => 2_500, 'salary_reward' => 1],
        2 => ['rank' => 'Sr. Sales Manager', 'team_business' => 5_000, 'salary_reward' => 2],
        3 => ['rank' => 'Executive', 'team_business' => 10_000, 'salary_reward' => 3],
        4 => ['rank' => 'Executive Manager', 'team_business' => 25_000, 'salary_reward' => 4],
        5 => ['rank' => 'Sr. Manager', 'team_business' => 50_000, 'salary_reward' => 5],
        6 => ['rank' => 'Asst. General Manager', 'team_business' => 1_50_000, 'salary_reward' => 6],
        7 => ['rank' => 'Chairman Director', 'team_business' => 3_50_000, 'salary_reward' => 7],
        8 => ['rank' => 'Vice President', 'team_business' => 10_00_000, 'salary_reward' => 8],
        9 => ['rank' => 'Ambassador', 'team_business' => 25_00_000, 'salary_reward' => 9]
    ];

    public const array DIRECT_AND_BUSINESS_BASED_SALARY_STRUCTURE = [
        1 => ['direct' => 3, 'direct_business' => 100, 'income' => 5, 'freq' => 30],
        2 => ['direct' => 5, 'direct_business' => 150, 'income' => 10, 'freq' => 30],
        3 => ['direct' => 40, 'direct_business' => 2000, 'income' => 25, 'freq' => 30],
        4 => ['direct' => 80, 'direct_business' => 5000, 'income' => 200, 'freq' => 30],
        5 => ['direct' => 100, 'direct_business' => 10000, 'income' => 500, 'freq' => 30],
    ];

    public static function getDailyRoiPercentByUser(object $user, string|float $balance): float
    {
        return 2.0;

    }

    public static function getDailyCompoundRoiPercentByUser(object $user, string|float $balance): float
    {
        return 1.0;
    }
}
