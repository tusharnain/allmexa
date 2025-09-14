<?php

namespace App\Services;

use App\Enums\UserTypes;
use App\Twebsol\Settings;




class ValidationRulesService
{

    /*
     *------------------------------------------------------------------------------------
     * User Registration Rules
     *------------------------------------------------------------------------------------
     */
    public static function userRegistrationRules(bool $isAdmin, bool $isFirstUser): array
    {
        $password_min_length = _setting('password_min_length');
        $userId_len_val = _setting('user_id_length_validation');
        $tpinDigits = _setting('tpin_digits', 6);

        $regCaptchaStatus = (!$isAdmin and _setting('registration_captcha')); // admin doesnt need captcha

        $countryCodes = array_keys(\App\Libraries\CountryLib::COUNTRIES);

        return [
            'captcha' => [$regCaptchaStatus ? 'required' : 'permit_empty', 'string', 'captcha[USER_REGISTRATION_C]'],
            'sponsor_id' => [$isFirstUser ? 'permit_empty' : 'required', 'string', 'alpha_numeric', "min_length[{$userId_len_val[0]}]", "max_length[{$userId_len_val[1]}]"],
            'full_name' => ['required', 'string', 'min_length[2]', 'max_length[100]', 'alpha_numeric_spaces'],
            'email' => ['required', 'string', 'max_length[250]', 'email'],
            // 'phone' => ['required', 'numeric', 'min_length[10]', 'max_length[12]'],
            'country_code' => ['required', 'in_list[' . implode(',', $countryCodes) . ']'],
            'tpin' => [...[$isAdmin ? 'permit_empty' : 'required'], 'numeric', 'no_trailing_spaces', "exact_length[$tpinDigits]"],
            'password' => ['required', 'string', 'no_trailing_spaces', "min_length[$password_min_length]"],
            'cpassword' => [$isAdmin ? 'permit_empty' : 'required', 'string', 'matches[password]'],
            'tnc' => [$isAdmin ? 'permit_empty' : 'required'],
        ];
    }

    /*
     *------------------------------------------------------------------------------------
     * User Login Rules
     *------------------------------------------------------------------------------------
     */
    public static function userLoginRules(): array
    {
        $loginCaptchaStatus = _setting('login_captcha');
        $userId_len_val = _setting('user_id_length_validation');

        $emailLoginStatus = _setting('allow_user_login_with_email', false);

        return $emailLoginStatus ?
            [
                'captcha' => [$loginCaptchaStatus ? 'required' : 'permit_empty', 'captcha[USER_LOGIN_C]'],
                'username' => ['required', 'string'],
                'password' => ['required', 'string', 'no_trailing_spaces']
            ] :
            [
                'captcha' => [$loginCaptchaStatus ? 'required' : 'permit_empty', 'captcha[USER_LOGIN_C]'],
                'user_id' => ['required', 'string', 'alpha_numeric', "min_length[{$userId_len_val[0]}]", "max_length[{$userId_len_val[1]}]"],
                'password' => ['required', 'string', 'no_trailing_spaces']
            ];
    }


    /*
     *------------------------------------------------------------------------------------
     * User Profile Rules (user_details table)
     *------------------------------------------------------------------------------------
     */
    public static function userProfileRules(bool $isAdmin = false): array
    {

        $nomineeRelations = \App\Enums\NomineeRelation::getRelationString();

        $rules = [
            // 'date_of_birth' => ['permit_empty', 'valid_date'],
            // 'gender' => ['permit_empty', 'string', 'in_list[male,female,other]'],
            // 'father_name' => ['permit_empty', 'string', 'min_length[2]', 'max_length[100]', 'alpha_numeric_spaces'],
            // 'mother_name' => ['permit_empty', 'string', 'min_length[2]', 'max_length[100]', 'alpha_numeric_spaces'],
            // 'employment_status' => ['permit_empty', 'string', 'in_list[employed,unemployed,student]'],
            // 'occupation' => ['permit_empty', 'string', 'max_length[220]'],
            // 'postal_code' => ['permit_empty', 'numeric', 'min_length[4]', 'max_length[20]'],
            // 'state' => ['permit_empty', 'string', 'max_length[220]'],
            // 'city' => ['permit_empty', 'string', 'max_length[220]'],
            // 'address' => ['permit_empty', 'string', 'max_length[1000]'],
            // 'nominee' => ['permit_empty', 'string', 'max_length[220]'],
            // 'nominee_aadhar' => ['permit_empty', 'numeric', "exact_length[12]"],
            // 'nominee_relation' => ['permit_empty', 'string', "in_list[$nomineeRelations]"],
            // 'nominee_phone' => ['permit_empty', 'string', 'min_length[10]', 'max_length[12]'],
            // 'nominee_email' => ['permit_empty', 'string', 'max_length[250]', 'email'],
            // 'nominee_address' => ['permit_empty', 'string', 'max_length[1000]']
        ];

        // User Restrictions
        if (!$isAdmin) {

            $restrictedInputs = Settings::USER_PROFILE_UPDATE_RESTRICTIONS;
            foreach ($restrictedInputs as &$input)
                unset($rules[$input]);
        }

        return $rules;
    }


    /*
     *------------------------------------------------------------------------------------
     * User Profile Rules (users table)
     *------------------------------------------------------------------------------------
     */
    public static function userDataRules(bool $isAdmin = false): array
    {
        $pfp = 'profile_picture';
        $rules = [
            'full_name' => ['required', 'string', 'min_length[2]', 'max_length[100]', 'alpha_numeric_spaces'],
            'email' => ['required', 'string', 'max_length[250]', 'email'],
            'phone' => ['required', 'numeric', 'min_length[10]', 'max_length[12]'],
            $pfp => "permit_empty|max_size[$pfp,150]|mime_in[$pfp,image/jpg,image/jpeg,image/png]"
        ];

        // User Restrictions
        if (!$isAdmin) {
            $restrictedInputs = Settings::USER_PROFILE_UPDATE_RESTRICTIONS;
            foreach ($restrictedInputs as &$input)
                unset($rules[$input]);
        }

        return $rules;
    }


    /*
     *------------------------------------------------------------------------------------
     * User Change Password Rules
     *------------------------------------------------------------------------------------
     */
    public static function userChangePasswordRules(bool $isAdmin = false): array
    {
        $password_min_length = _setting('password_min_length');

        $rules = [
            'cpassword' => ['required', 'string', 'no_trailing_spaces'],
            'npassword' => ['required', 'string', "min_length[$password_min_length]"],
            'cnpassword' => ['required', 'string', "min_length[$password_min_length]", 'matches[npassword]'],
        ];

        if ($isAdmin)
            unset($rules['cpassword']);

        return $rules;
    }


    /*
     *------------------------------------------------------------------------------------
     * User Change TPIN Rules
     *------------------------------------------------------------------------------------
     */
    public static function userChangeTpinRules(bool $hasTpin, bool $isAdmin = false): array
    {
        $tpinDigits = _setting('tpin_digits', 6);

        $rules = [
            'ctpin' => ['required', 'numeric', "exact_length[$tpinDigits]", 'no_trailing_spaces'],
            'ntpin' => ['required', 'numeric', "exact_length[$tpinDigits]"],
            'cntpin' => ['required', 'numeric', "exact_length[$tpinDigits]", 'matches[ntpin]'],
        ];

        if (!$hasTpin or $isAdmin)
            unset($rules['ctpin']);

        return $rules;
    }


    /*
     *------------------------------------------------------------------------------------
     * Topup Rules
     *------------------------------------------------------------------------------------
     */
    public static function userTopupRules(): array
    {
        $userId_len_val = _setting('user_id_length_validation');
        $topup_range = _setting('topup_amount_range');
        $multipleOf = intval(_c(_setting('topup_amount_multiple_of')));
        $topupRangeStart = intval(_c($topup_range[0]));
        $topupRangeEnd = intval(_c($topup_range[1]));

        // $amountRule = ['required', 'numeric', "in_list[100,500,1000,2500]"];

        $amountRule = ['required', 'numeric', "greater_than_equal_to[{$topupRangeStart}]", "less_than_equal_to[{$topupRangeEnd}]"];

        if ($multipleOf)
            array_push($amountRule, "multiple_of[$multipleOf]");

        return [
            'user_id' => ['required', 'string', 'alpha_numeric', "min_length[{$userId_len_val[0]}]", "max_length[{$userId_len_val[1]}]"],
            'amount' => $amountRule,
            'type' => ['permit_empty', 'in_list[investment,compound]']
        ];
    }


    /*
     *------------------------------------------------------------------------------------
     * P2P Transfer Rules
     *------------------------------------------------------------------------------------
     */
    public static function p2pTransferRules(): array
    {
        $userId_len_val = _setting('user_id_length_validation');
        $amountRange = _setting('user_p2p_transfer_amount_range');

        return [
            'user_id' => ['required', 'string', 'alpha_numeric', "min_length[{$userId_len_val[0]}]", "max_length[{$userId_len_val[1]}]"],
            'amount' => ['required', 'numeric', "greater_than_equal_to[$amountRange[0]]", "less_than_equal_to[$amountRange[1]]"],
            'remarks' => ['permit_empty', 'string', 'max_length[250]']
        ];
    }

    /*
     *------------------------------------------------------------------------------------
     * Support Ticket Rules
     *------------------------------------------------------------------------------------
     */
    public static function userTicketRules(): array
    {
        return [
            'subject' => ['required', 'string', 'min_length[5]', 'max_length[150]'],
            'message' => ['required', 'string', 'min_length[10]', 'max_length[2000]']
        ];
    }

    /*
     *------------------------------------------------------------------------------------
     * Deposit Rules
     *------------------------------------------------------------------------------------
     */
    public static function userDepositRules(bool $rcFile = false, bool $remarks = false): array
    {
        $rcFileName = 'receipt_file';

        $arr['utr'] = ['required', 'string', 'min_length[5]', 'max_length[100]'];


        $arr[$rcFileName] = ($rcFile ? "uploaded[$rcFileName]" : 'permit_empty') . "|max_size[$rcFileName,250]|mime_in[$rcFileName,image/jpg,image/jpeg,image/png]";

        if ($remarks)
            $arr['remarks'] = ['permit_empty', 'string', 'max_length[250]'];

        return $arr;
    }

    /*
     *------------------------------------------------------------------------------------
     * Bank Details Rules
     *------------------------------------------------------------------------------------
     */
    public static function userBankDetailsRules(): array
    {
        return [
            'account_holder_name' => ['required', 'string', 'min_length[2]', 'max_length[150]', 'alpha_numeric_spaces'],
            'account_number' => ['required', 'numeric', 'min_length[9]', 'max_length[25]']
        ];
    }
    /*
     *------------------------------------------------------------------------------------
     * Wallet Details Rules
     *------------------------------------------------------------------------------------
     */
    public static function userWalletDetailsRules(): array
    {
        return [
            'trc20_address' => ['required', 'string', 'alpha_numeric', 'max_length[64]',],
            // 'bep20_address' => ['required', 'string', 'max_length[64]']
        ];
    }

    /*
     *------------------------------------------------------------------------------------
     * Withdrawal Data Rules
     *------------------------------------------------------------------------------------
     */
    public static function userWithdrawalRules(): array
    {
        $range = _setting('withdrawal_amount_range', [500, 1000]);
        $multipleOf = _setting('withdrawal_amount_multiple_of', default: null);

        // no need to validate the remarks here, because if its disable, it wont pass through the input service

        $amountRules = ['required', 'numeric', "greater_than_equal_to[$range[0]]", "less_than_equal_to[$range[1]]"];

        if ($multipleOf)
            array_push($amountRules, "multiple_of[$multipleOf]");

        return ['amount' => $amountRules];
    }


    /*
     *------------------------------------------------------------------------------------
     * Wallet Transfer Rules
     *------------------------------------------------------------------------------------
     */
    public static function userWalletTransferRules(): array
    {

        $fromWallets = implode(',', Settings::get_wallet_transfer_from_wallets());
        $from = inputPost('from');
        $toWallets = implode(',', Settings::WALLET_TRANSFER_RULES[$from] ?? []);

        $range = _setting('wallet_transfer_amount_range', [10, 10000]);
        $multipleOf = _setting('wallet_transfer_amount_multiple_of', default: null);

        $amountRules = ['required', 'numeric', "greater_than_equal_to[$range[0]]", "less_than_equal_to[$range[1]]"];

        if ($multipleOf)
            array_push($amountRules, "multiple_of[$multipleOf]");

        return [
            'from' => ['required', 'string', "in_list[$fromWallets]"],
            'to' => ['required', 'string', "in_list[$toWallets]"],
            'amount' => $amountRules
        ];
    }



    /*
     *------------------------------------------------------------------------------------
     * User Reset Password Rules
     *------------------------------------------------------------------------------------
     */
    public static function userResetPasswordRules(): array
    {
        $password_min_length = _setting('password_min_length');
        return [
            'password' => ['required', 'string', 'no_trailing_spaces', "min_length[$password_min_length]"],
            'cpassword' => ['required', 'string', 'matches[password]'],
            'token' => ['required', 'string'],
        ];
    }



    /*
    !------------------------------------------------------------------------------------
    ! Admin Only
    !------------------------------------------------------------------------------------
    */

    public static function admin_addDeductRules(): array
    {
        $userId_len_val = _setting('user_id_length_validation');
        $wallets = \App\Services\WalletService::WALLETS;

        $walletIndecesString = implode(',', array_keys($wallets));
        $amountRange = _setting('admin_add_deduct_amount_range');


        return [
            'user_id' => ['required', 'string', 'alpha_numeric', "min_length[{$userId_len_val[0]}]", "max_length[{$userId_len_val[1]}]"],
            'wallet' => ['required', 'numeric', "in_list[$walletIndecesString]"],
            'type' => ['required', 'string', 'in_list[credit,debit]'],
            'amount' => ['required', 'numeric', "greater_than_equal_to[$amountRange[0]]", "less_than_equal_to[$amountRange[1]]"],
            'remarks' => ['permit_empty', 'string', 'max_length[250]']
        ];
    }

    public static function admin_inputUpdateDepositStatusRules(): array
    {
        $validStatus = [
            \App\Models\DepositModel::DEPOSIT_STATUS_REJECT,
            \App\Models\DepositModel::DEPOSIT_STATUS_COMPLETE,
        ];

        $validStatusString = implode(',', $validStatus);

        return [
            'status' => ['required', "in_list[{$validStatusString}]"],
            'remarks' => ['permit_empty', 'string', 'min_length[3]', 'max_length[250]'],
            'credit_to_wallet' => ['permit_empty']
        ];
    }


    public static function admin_inputUpdateWithdrawalStatusRules(): array
    {
        $validStatus = [
            \App\Models\WithdrawalModel::WD_STATUS_REJECT, // 0
            \App\Models\WithdrawalModel::WD_STATUS_CANCELLED, // 1
            \App\Models\WithdrawalModel::WD_STATUS_COMPLETE, // 2
        ];

        $validStatusString = implode(',', $validStatus);

        return [
            'status' => ['required', "in_list[{$validStatusString}]"],
            'utr' => ["required_if[status, {$validStatus[2]}]"],
            'remarks' => ['permit_empty', 'string', 'min_length[3]', 'max_length[250]']
        ];
    }

    /*
     *------------------------------------------------------------------------------------
     * Topup Rules
     *------------------------------------------------------------------------------------
     */
    public static function admin_topupRules(): array
    {
        $userId_len_val = _setting('user_id_length_validation');
        $topup_range = _setting('topup_amount_range');
        $multipleOf = _setting('topup_amount_multiple_of');

        $amountRule = ['required', 'numeric', "greater_than_equal_to[{$topup_range[0]}]", "less_than_equal_to[{$topup_range[1]}]"];

        if ($multipleOf)
            array_push($amountRule, "multiple_of[$multipleOf]");

        return [
            'user_id' => ['required', 'string', 'alpha_numeric', "min_length[{$userId_len_val[0]}]", "max_length[{$userId_len_val[1]}]"],
            'amount' => $amountRule
        ];
    }


}