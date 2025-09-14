<?php

namespace App\Enums;

final class UserTokenType {
    const EMAIL_VERIFICATION = 'email_verification';
    const PASSWORD_RESET = 'password_reset';


    public static function getTypeArray(): array {
        $reflectionClass = new \ReflectionClass(__CLASS__);
        return array_values($reflectionClass->getConstants());
    }
}