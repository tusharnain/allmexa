<?php

namespace App\Twebsol;

final class Data
{
    private static array $data = [
        'company_name' => 'Allmexa',
        'company_name_in_emails' => 'AL',
        'developer' => 'WmWeb',
    ];

    public static function getData(string $key, mixed $default = null)
    {
        return self::$data[$key] ?? $default;
    }
}