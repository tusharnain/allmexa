<?php

namespace App\Enums;

/*
 *------------------------------------------------------------------------------------
 * There are not user types in this projct, this file is kind of just helping somewhere
 *------------------------------------------------------------------------------------
 */

class UserTypes
{
    public const string STARTER = 'starter';
    public const string BASIC = 'basic';
    public const string STAR = 'star';
    public const string GOLD = 'gold';
    public const string PREMIUM = 'premium';
    public static function getArray(): array
    {
        $reflectionClass = new \ReflectionClass(__CLASS__);
        return array_values($reflectionClass->getConstants());
    }


    public static function getUserTypeFromInvestment(int|float $investment): string
    {
        return match (true) {
            ($investment >= 10 && $investment < 50) => self::STARTER,
            ($investment >= 50 && $investment <= 100) => self::BASIC,
            ($investment >= 101 && $investment <= 500) => self::STAR,
            ($investment >= 501 && $investment <= 1000) => self::GOLD,
            ($investment >= 1001) => self::PREMIUM,
        };
    }


    public static function getCappingPercentFromUserType(string $userType): int|null
    {
        return match ($userType) {
            self::STARTER => null,
            self::BASIC => 300,
            self::STAR => 500,
            self::GOLD => 700,
            self::PREMIUM => null
        };
    }

    public static function getCappingPercentFromUserInvestment(string|float $investment): int|null
    {
        return match (true) {
            $investment <= 5000 => 200,
            $investment <= 10000 => 300,
            default => 500
        };
    }
}
