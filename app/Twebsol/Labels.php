<?php

namespace App\Twebsol;

final class Labels
{
    private static array $labels = [
        'user_id' => 'User Id',
        'sponsor_id' => 'Sponsor Id',

        'user' => 'User',
        'sponsor' => 'Sponsor',



        'users' => 'Users',
        'sponsors' => 'Sponsors',

        'user_name' => 'User Name',
        'sponsor_name' => 'Sponsor Name',


        'user_status_active' => 'Active', // for active user
        'user_status_inactive' => 'In Active', // for inactive user


        'tpin' => 'Txn Password',


        'plan' => 'Package',
        'plans' => 'Packages',
    ];





    // Also need to change the slugs from WalletServices.php
    public static array $walletLabels = [
        'income' => 'E Wallet',
        'fund' => 'Fund Wallet',
        'investment' => 'Investment',
        'direct_income' => 'Direct Income Wallet',
        'salary' => 'Salary Wallet',
        'roi' => 'ROI Wallet',
        'withdrawal' => 'Withdrawal Wallet',
        'compound_investment' => 'Compound Investment',
    ];

    public static function getLabel(string $key)
    {
        return self::$labels[$key] ?? null;
    }
}