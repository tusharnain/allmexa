<?php

namespace App\Validation;

use App\Services\Captcha;


class CustomValidationRules
{
    public function alpha_numeric_spaces(?string $str): bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9\s]+$/', $str);
    }

    // No Trailing Spaces
    public function no_trailing_spaces(string $str = null): bool
    {
        if (is_null($str)) {
            return true;
        }

        // Trim the string and check for spaces at the beginning or end
        $trimmed = trim($str);
        return $trimmed === $str && $trimmed !== '' && $trimmed[0] !== ' ' && $trimmed[-1] !== ' ';
    }


    // Captcha
    public function captcha(string $code, string $key): bool
    {
        $captcha = new Captcha($key);
        return $captcha->validate($code);
    }


    public function email(string $str): bool
    {
        // Use a strict regular expression for email validation
        return (bool) preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $str);
    }

    public function ifsc(string $str): bool
    {
        return (bool) \Razorpay\IFSC\IFSC::validate($str);
    }

    public function required_if(string $value = null, string $field, array $data): bool
    {
        $arr = explode(',', $field);
        $otherField = trim($arr[0]);
        $expectedValue = trim($arr[1]);
        $otherFieldValue = isset($data[$otherField]) ? $data[$otherField] : null;
        $isEqual = $otherFieldValue == $expectedValue;
        return $isEqual ? ($value and !empty($value)) : true;
    }


    public function multiple_of($value, string $multiple, array $data): bool
    {
        if (!is_numeric($value) || !is_numeric($multiple)) {
            return false;
        }
        return $value % $multiple === 0;
    }

}
