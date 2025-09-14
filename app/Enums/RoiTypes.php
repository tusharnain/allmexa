<?php

namespace App\Enums;

final class RoiTypes
{
    const DAILY = 'daily';
    const MONTHLY = 'monthly';


    public static function getArray(): array
    {
        $reflectionClass = new \ReflectionClass(__CLASS__);
        return array_values($reflectionClass->getConstants());
    }
}