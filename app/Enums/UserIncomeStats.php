<?php

namespace App\Enums;

final class UserIncomeStats
{

    const TOTAL_EARNING = 'total_earning';
    const TOTAL_INVESTMENT = 'total_investment';
    const TOTAL_PENDING_WITHDRAWAL = 'total_pending_withdrawal';
    const TOTAL_COMPLETE_WITHDRAWAL = 'total_complete_withdrawal';
    const TOTAL_PENDING_DEPOSIT = 'total_pending_deposit';
    const TOTAL_COMPLETE_DEPOSIT = 'total_complete_deposit';
    const TOTAL_CAPPED_INCOME = 'total_capped_income';

    private static ?array $array = null;

    public static function getArray(): array
    {
        if (!self::$array) {
            $reflectionClass = new \ReflectionClass(__CLASS__);
            self::$array = array_values($reflectionClass->getConstants());
        }
        return self::$array;
    }
}
