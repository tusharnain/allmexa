<?php

namespace App\Enums;

final class WalletTransactionCategory
{
    const SPONSOR_LEVEL_INCOME = 'sponsor_level_income';
    const SPONSOR_ROI_LEVEL_INCOME = 'sponsor_roi_level_income';
    const P2P_TRANSFER = 'p2p_transfer';
    const WALLET_TRANSFER = 'wallet_transfer';
    const WITHDRAWAL = 'withdrawal';
    const SHOPPING_REPURCHASE_INCOME = 'shopping_repurchase_income';
    const TOPUP = 'topup';
    const ADMIN = 'admin';
    const WITHDRAWAL_REFUND = 'withdrawal_refund';
    const ROI = 'roi';
    const SALARY = 'salary';
    const REWARD = 'reward';
    const INVESTMENT = 'investment';
    const COMPOUND_INVESTMENT = 'compound_investment';
    const COMPOUND_ROI = 'compound_roi';
    const WEEKLY_SALARY = 'weekly_salary';
    const DAILY_TOPUP_BONANZA = 'daily_topup_bonanza';
    const BOOSTER_CLUB_INCOME = 'booster_club_income';

    public static function getArray(): array
    {
        $reflectionClass = new \ReflectionClass(__CLASS__);
        return array_values($reflectionClass->getConstants());
    }
}