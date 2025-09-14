<?php

namespace App\Services;

use App\Enums\UserTypes;
use App\Twebsol\Settings;



class InputService
{

    const PASSWORD_CREATE_ATTRIBS = [
        'password' => 'Password',
        'cpassword' => 'Confirm Password',
    ];


    /*
     *------------------------------------------------------------------------------------
     * User Registration Inputs
     *------------------------------------------------------------------------------------
     */
    public static function inputRegistrationValues_attribs(): array
    {
        return [
            'user_id' => label('user_id'),
            'sponsor_id' => label('sponsor_id'),
            'full_name' => 'Full name',
            'email' => 'Email Address',
            // 'phone' => 'Phone Number',
            'country_code' => 'Country',
            'password' => 'Password',
            'cpassword' => 'Confirm Password',
            'tpin' => label('tpin'),
            'tnc' => 'Terms & Conditions'
        ];
    }
    public static function inputRegistrationValues(): array
    {
        return [
            'sponsor_id' => inputPost('sponsor_id'),
            'full_name' => inputPost('full_name'),
            'email' => inputPost('email'),
            // 'phone' => inputPost('phone'),
            'country_code' => inputPost('country_code'),
            'password' => request()->getPost('password'),
            'cpassword' => request()->getPost('cpassword'),
            'tpin' => request()->getPost('tpin'),
            'tnc' => inputPost('tnc'),
            'captcha' => inputPost('captcha')
        ];
    }


    /*
     *------------------------------------------------------------------------------------
     * User Login Inputs
     *------------------------------------------------------------------------------------
     */
    public static function inputLoginValues(): array
    {
        $emailLoginStatus = _setting('allow_user_login_with_email', false);

        return $emailLoginStatus ?
            [
                'username' => inputPost('username'),
                'password' => request()->getPost('password'),
                'captcha' => inputPost('captcha')
            ] :
            [
                'user_id' => inputPost('user_id'),
                'password' => request()->getPost('password'),
                'captcha' => inputPost('captcha')
            ];
    }



    /*
     *------------------------------------------------------------------------------------
     * User Profile Inputs (user details table)
     *------------------------------------------------------------------------------------
     */
    public static function inputProfileValues_attribs(): array
    {
        return [
            // 'nominee_phone' => 'Nominee Phone Number',
            // 'nominee_email' => 'Nominee Email Address',
            // 'nominee_relation' => 'Nominee Relation',
            // 'nominee_address' => 'Nominee Address',
            // 'nominee' => 'Nominee',
            'full_name' => 'Full Name',
            'email' => 'Email Address',
            // 'phone' => 'Phone Number',
            'profile_picture' => 'Profile Picture',
            // 'date_of_birth' => 'Date of Birth',
            // 'gender' => 'Gender',
            // 'father_name' => "Father's Name",
            // 'mother_name' => "Mother's Name",
            // 'employment_status' => 'Employment Status',
            // 'occupation' => 'Occupation',
            // 'postal_code' => 'Postal Code',
            // 'state' => 'State',
            // 'city' => 'City',
            // 'address' => 'Address',
        ];
    }
    public static function inputProfileValues(bool $isAdmin = false): array
    {
        $inputs = [
            // 'date_of_birth' => inputPost('date_of_birth', 1),
            // 'gender' => inputPost('gender', 1),
            // 'father_name' => inputPost('father_name', 1),
            // 'mother_name' => inputPost('mother_name', 1),
            // 'employment_status' => inputPost('employment_status', 1),
            // 'occupation' => inputPost('occupation', 1),
            // 'postal_code' => inputPost('postal_code', 1),
            // 'state' => inputPost('state', 1),
            // 'city' => inputPost('city', 1),
            // 'address' => inputPost('address', 1),
            // 'nominee' => inputPost('nominee', 1),
            // 'nominee_aadhar' => inputPost('nominee_aadhar', 1),
            // 'nominee_relation' => inputPost('nominee_relation', 1),
            // 'nominee_phone' => inputPost('nominee_phone', 1),
            // 'nominee_email' => inputPost('nominee_email', 1),
            // 'nominee_address' => inputPost('nominee_address', 1)
        ];

        // User Restrictions
        if (!$isAdmin) {

            $restrictedInputs = Settings::USER_PROFILE_UPDATE_RESTRICTIONS;

            // if ($ud = user_model(static: true)->getUserDetailsFromUserIdPk(user('id')))
            //     foreach ($inputs as $key => $val)
            //         if ($ud->$key)
            //             unset($inputs[$key]);


            foreach ($restrictedInputs as &$input)
                unset($inputs[$input]);
        }

        return $inputs;
    }


    /*
     *------------------------------------------------------------------------------------
     * Update User Inputs (users table)
     *------------------------------------------------------------------------------------
     */
    public static function inputUserValues(bool $isAdmin = false): array
    {
        // Same attributes will be used as defnied in profile update attributes
        $inputs = [
            'full_name' => inputPost('full_name'),
            'email' => inputPost('email'),
            'phone' => inputPost('phone'),
            'profile_picture' => request()->getFile('profile_picture')
        ];

        // User Restrictions
        if (!$isAdmin) {

            $restrictedInputs = Settings::USER_PROFILE_UPDATE_RESTRICTIONS;
            foreach ($restrictedInputs as &$input)
                unset($inputs[$input]);
        }

        return $inputs;
    }






    /*
     *------------------------------------------------------------------------------------
     * Change Password Inputs
     *------------------------------------------------------------------------------------
     */
    public static function inputChangePasswordValues_attribs(): array
    {
        return [
            'cpassword' => 'Current Password',
            'npassword' => 'New Password',
            'cnpassword' => 'Confirm Password'
        ];
    }
    public static function inputChangePasswordValues(bool $isAdmin = false): array
    {
        $inputs = [
            'cpassword' => request()->getPost('cpassword'),
            'npassword' => request()->getPost('npassword'),
            'cnpassword' => request()->getPost('cnpassword'),
        ];

        if ($isAdmin)
            unset($inputs['cpassword']);

        return $inputs;
    }


    /*
     *------------------------------------------------------------------------------------
     * Change TPIN Inputs
     *------------------------------------------------------------------------------------
     */
    public static function inputChangeTPinValues_attribs(): array
    {
        $tpinLabel = label('tpin');
        return [
            'ctpin' => "Current $tpinLabel",
            'ntpin' => "New $tpinLabel",
            'cntpin' => "Confirm $tpinLabel"
        ];
    }
    public static function inputChangeTPinValues(bool $hasTpin, bool $isAdmin = false): array
    {
        $inputs = [
            'ctpin' => request()->getPost('ctpin'),
            'ntpin' => request()->getPost('ntpin'),
            'cntpin' => request()->getPost('cntpin')
        ];

        if (!$hasTpin or $isAdmin)
            unset($inputs['ctpin']);

        return $inputs;
    }


    /*
     *------------------------------------------------------------------------------------
     * Topup Inputs
     *------------------------------------------------------------------------------------
     */
    public static function inputTopupValues_attribs(): array
    {
        return [
            'user_id' => label('user_id'),
            'amount' => 'Amount',
        ];
    }
    public static function inputTopupValues(): array
    {
        return [
            'user_id' => inputPost('user_id'),
            'amount' => inputPost('amount'),
            'type' => inputPost('type')
        ];
    }

    /*
     *------------------------------------------------------------------------------------
     * P2P Transfer Inputs
     *------------------------------------------------------------------------------------
     */
    public static function inputP2PTransferValues(): array
    {
        // No need of attribs, coz field names are simple enough
        return [
            'user_id' => inputPost('user_id'),
            'amount' => inputPost('amount'),
            'remarks' => inputPost('remarks', true)
        ];
    }

    /*
     *------------------------------------------------------------------------------------
     * Support Ticket Inputs
     *------------------------------------------------------------------------------------
     */
    public static function inputTicketValues(): array
    {
        // No need of attribs, coz field names are simple enough
        return [
            'subject' => inputPost('subject'),
            'message' => inputPost('message'),
        ];
    }

    /*
     *------------------------------------------------------------------------------------
     * Deposit Inputs
     *------------------------------------------------------------------------------------
     */
    public static function inputDepositValues_attribs(): array
    {
        return [
            'utr' => 'UTR Number',
            'receipt_file' => 'Receipt File',
            'remarks' => 'remarks',
        ];
    }
    public static function inputDepositValues(bool $rcFile = false, bool $remarks = false): array
    {
        $arr['utr'] = inputPost('utr');

        if ($rcFile)
            $arr['receipt_file'] = request()->getFile('receipt_file');

        if ($remarks)
            $arr['remarks'] = inputPost('remarks', null_if_empty: true);

        return $arr;
    }

    /*
     *------------------------------------------------------------------------------------
     * Bank Details Input
     *------------------------------------------------------------------------------------
     */
    public static function inputBankDetailsValues_attribs(): array
    {
        return [
            'account_holder_name' => 'Account Holder Name',
            'account_number' => 'Accoun Number'
        ];
    }
    public static function inputBankDetailsValues(): array
    {
        return [
            'account_holder_name' => inputPost('account_holder_name'),
            'account_number' => inputPost('account_number')
        ];
    }

    /*
     *------------------------------------------------------------------------------------
     * Wallet Details Input
     *------------------------------------------------------------------------------------
     */
    public static function inputWalletDetailsValues_attribs(): array
    {
        return [
            'trc20_address' => 'TRC20 Wallet Address',
            // 'bep20_address' => 'BEP20 Wallet Address',
        ];
    }
    public static function inputWalletDetailsValues(): array
    {
        return [
            'trc20_address' => inputPost('trc20_address'),
            // 'bep20_address' => inputPost('bep20_address')
        ];
    }

    /*
     *------------------------------------------------------------------------------------
     * WIthdrawal Data Input
     *------------------------------------------------------------------------------------
     */
    public static function inputWithdrawalValues(): array
    {
        $remarks = _setting('withdrawal_remarks', false);

        $arr['amount'] = inputPost('amount');

        if ($remarks)
            $arr['remarks'] = inputPost('remarks', null_if_empty: true);

        return $arr;
    }

    /*
     *------------------------------------------------------------------------------------
     * Wallet Transfer Input
     *------------------------------------------------------------------------------------
     */
    public static function inputWalletTransferValues_attribs(): array
    {
        return [
            'from' => 'From Wallet',
            'to' => 'To Wallet',
            'amount' => 'Amount',
        ];
    }
    public static function inputWalletTransferValues(): array
    {
        return [
            'from' => inputPost('from'),
            'to' => inputPost('to'),
            'amount' => inputPost('amount'),
        ];
    }



    /*
     *------------------------------------------------------------------------------------
     * Reset Password Input
     *------------------------------------------------------------------------------------
     */
    public static function inputResetPassword(): array
    {
        return [
            'password' => request()->getPost('password'),
            'cpassword' => request()->getPost('cpassword'),
            'token' => inputPost('token')
        ];
    }











    /*
     !------------------------------------------------------------------------------------
     ! Admin Only
     !------------------------------------------------------------------------------------
     */

    public static function admin_inputAddDeductValues(): array
    {
        // no need attributes, coz its simple
        return [
            'user_id' => inputPost('user_id'),
            'wallet' => inputPost('wallet'),
            'type' => inputPost('type'),
            'amount' => inputPost('amount'),
            'remarks' => inputPost('remarks'),
            'is_earning' => inputPost('is_earning')
        ];
    }

    public static function admin_inputUpdateDepositStatusValues(): array
    {
        // no need attributes, coz its simple
        return [
            'status' => inputPost('status'),
            'remarks' => inputPost('remarks', null_if_empty: true),
            'credit_to_wallet' => inputPost('credit_to_wallet', null_if_empty: true)
        ];
    }

    public static function admin_inputUpdateWithdrawalStatusValues(): array
    {
        // no need attributes, coz its simple
        return [
            'status' => inputPost('status'),
            'utr' => inputPost('utr', true),
            'remarks' => inputPost('remarks', null_if_empty: true),
        ];
    }

    public static function inputAdminTopupValues_attribs(): array
    {
        return [
            'user_id' => label('user_id'),
            'amount' => 'Amount',
        ];
    }
    public static function inputAdminTopupValues(): array
    {
        return [
            'user_id' => inputPost('user_id'),
            'amount' => inputPost('amount')
        ];
    }
}
