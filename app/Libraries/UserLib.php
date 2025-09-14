<?php

namespace App\Libraries;

class UserLib
{

    public static function generateNewUserId(): string
    {
        $userIdPrefix = _setting('user_id_prefix', 'TC');

        return $userIdPrefix . mt_rand(1111111, 9999999);
    }
    public static function getAfterRegistrationText(array $userData): string
    {
        $userIdLabel = label('user_id');
        $sponsorIdLabel = label('sponsor_id');

        $userId = $userData['user_id'];
        $sponsorId = $userData['sponsor_id'];
        $fullName = $userData['full_name'];
        $email = $userData['email'];
        $phoneNumber = $userData['phone'];
        $password = $userData['password'];
        $joiningDate = $userData['joining_date'];

        $text = <<<TEXT
        Full Name: $fullName
        {$userIdLabel}: $userId
        {$sponsorIdLabel} : $sponsorId
        Email : $email
        Phone Number : $phoneNumber
        Password : $password
        Joining Date : $joiningDate 
        TEXT;

        return str_replace(["\r\n", "\r", "\n"], '\n', trim($text));
    }

    public static function getAfterRegistrationTextFileName(array $userData): string
    {
        return self::getAfterRegFileName($userData) . ".txt";
    }
    public static function getAfterRegistrationImageFileName(array $userData): string
    {
        return self::getAfterRegFileName($userData) . ".png";
    }




    /**
     * Only for generating token, doesn't have to do anything with the user.
     */
    public static function generateUserToken(int $length = 64): string
    {
        $length = $length / 2;
        return bin2hex(random_bytes($length));
    }




    /*
     *------------------------------------------------------------------------------------
     * Private Helpers
     *------------------------------------------------------------------------------------
     */
    private static function getAfterRegFileName(array &$userData): string
    {
        $userId = $userData['user_id'];
        $fullName = $userData['full_name'];
        $companyName = data('company_name');

        return "{$userId}_{$fullName}_Registration_{$companyName}";

    }

}
