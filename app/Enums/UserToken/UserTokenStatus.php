<?php

namespace App\Enums\UserToken;

final class UserTokenStatus
{
    const UNUSED = 'unused';
    const EXTENDED = 'extended';
    const USED = 'used';


    public static function getTypeArray(): array
    {
        $reflectionClass = new \ReflectionClass(__CLASS__);
        return array_values($reflectionClass->getConstants());
    }
}