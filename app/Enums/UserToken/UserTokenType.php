<?php

namespace App\Enums\UserToken;

final class UserTokenType
{
    const PASSWORD_RESET = 'password_reset';


    public static function getTypeArray(): array
    {
        $reflectionClass = new \ReflectionClass(__CLASS__);
        return array_values($reflectionClass->getConstants());
    }
}