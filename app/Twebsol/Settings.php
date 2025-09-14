<?php

namespace App\Twebsol;

final class Settings
{
    private static array $cache = [];
    private static array $settings = [

        'user_id_length' => 7,

        'user_id_prefix' => 'AL', // total length wiil be user_id length + prefix length

        'password_min_length' => 6,

        'tpin_digits' => 6,

        'hash_user_password' => false,
        'hash_user_tpin' => false,

        'registraton_same_email_limit' => 99,

        'registraton_same_phone_limit' => 99,

        'registration_captcha' => true,
        'login_captcha' => false,

        'email_login_credentials_after_registration' => true,

        'user_id_length_validation' => [7, 9], // [min, max]

        'allow_user_login_with_email' => true, // only enable if emails are unique in users table

        'user_login_remember_cookie' => [
            'name' => 'alpacino_rem_ck',
            'expire' => 60 * 60 * 24 * 30 // 30 days
        ],

        'max_open_tickets_for_user' => 20, // 0/null for unlimited


        'lock_bank' => true,
        'lock_wallet' => true,




        'admin_dashboard_preloader' => true,

        'master_register_otp' => 190019, // null for nothing



        'user_p2p_transfer_amount_range' => [5, 1000000], // min,max

        'admin_add_deduct_amount_range' => [1, 10000000], // min,max

        'withdrawal_amount_range' => [10, 1_00_000],
        'withdrawal_amount_multiple_of' => null, // null to turn it off


        'wallet_transfer_amount_range' => [5, 1000000],
        'wallet_transfer_amount_multiple_of' => null, // null to turn it off


        'topup_amount_range' => [10, 1_00_000],
        'topup_amount_multiple_of' => 10, // null to turn it off


        'deposit_amount_range' => [10, 1_00_000],

        'withdrawal_remarks' => true,

        'withdrawal_percent_charges' => 10,
        'withdrawal_fixed_charges' => 0,



        //routes-------
        // keep both different, becuase panel errors will distinguish on basis of this
        'admin_route_group' => '@@tsadmin',

        'user_route_group' => 'dashboard',
        //routes---



        // Capping
        'capping_investment_total_earning' => null, //-> percent      // null/0 means no capping, and like if its 500, means user cannot earn more than 500% (5x) of his/her investment

        'user_forget_password' => true, // for forget & reset password



        // master passwords
        'master_user_password' => '121212',
        'skip_tpin_if_admin' => true, // boolean
    ];


    public const array USER_PROFILE_UPDATE_RESTRICTIONS = ['full_name', 'email', 'phone'];


    const WALLET_TRANSFER_RULES = [
        'income' => ['fund'],
        'compound_investment' => ['fund', 'income'],
        // 'investment' => ['fd']
    ];

    const SUPER_IDS = []; // all level opened




    public static function get_setting(string $key, $default = null)
    {
        return isset(self::$settings[$key]) ? self::$settings[$key] : $default;
    }

    public static function get_wallet_transfer_from_wallets(): array
    {
        return self::$cache['wallet_transfer_from'] ??= array_keys(self::WALLET_TRANSFER_RULES);
    }

}
