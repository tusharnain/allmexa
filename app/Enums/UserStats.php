<?php

namespace App\Enums;

final class UserStats
{
    //! These are the database column names of user_stats table
    const SELF_BUSINESS = 'self_business';
    const DIRECT_BUSINESS = 'direct_business';
    const TEAM_BUSINESS = 'team_business';
    const TOTAL_EARNING = 'total_earning';
    const TOTAL_PENDING_WITHDRAWAL = 'total_pending_withdrawal';
    const TOTAL_PENDING_WITHDRAWAL_ADMIN_CHARGES = 'total_pending_withdrawal_admin_charges';
    const TOTAL_COMPLETE_WITHDRAWAL = 'total_complete_withdrawal';
    const TOTAL_COMPLETE_WITHDRAWAL_ADMIN_CHARGES = 'total_complete_withdrawal_admin_charges';

    public static function getArray(): array
    {
        $reflectionClass = new \ReflectionClass(__CLASS__);
        return array_values($reflectionClass->getConstants());
    }
}
