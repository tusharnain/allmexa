<?php

namespace App\Services;

use App\Constants\Constants;


class UserService
{

    public static function validateRequestPassword(): string|bool
    {
        // if string is returned, its an error. otherwise validated 
        if (!($password = request()->getPost('password')) or empty($password))
            return 'Account Password is required.';

        if (!user_model()->verifyPassword(user(), $password, fromDatabase: true))
            return 'Account Password is incorrect.';
        return true;
    }
    public static function validateRequestTpin(bool $errorArray = false, ?string $errorTitle = null): string|array|bool
    {

        // if string is returned, its an error. otherwise validated 
        $tpinLabel = label('tpin');
        if (!($tpin = request()->getPost('tpin')) or empty($tpin))
            $err = "$tpinLabel is required.";
        else if (!user_model()->verifyTpin(user(), $tpin, fromDatabase: true))
            $err = "$tpinLabel is incorrect.";

        if (isset($err)) {

            if ($errorArray)
                return [
                    'success' => false,
                    'errors' => [
                        'error' => $err,
                        'errorTitle' => $errorTitle,
                        //
                        'validationErrors' => ['tpin' => $err],
                    ]
                ];

            return $err;
        }

        return true;
    }
    public static function makePassword(string $passwordString): array
    {
        $is_hashed = _setting('hash_user_password', true);

        return [
            'password' => $is_hashed ? hash_password($passwordString) : $passwordString,
            'is_password_hashed' => $is_hashed
        ];
    }
    public static function makeTpin(string $tpinString): array
    {
        $is_hashed = _setting('hash_user_tpin', true);
        return [
            'tpin' => $is_hashed ? hash_password($tpinString) : $tpinString,
            'is_tpin_hashed' => $is_hashed
        ];
    }
    public static function emailLoginCredentials(string $emailTo = '', int|string $user_id = '', string $full_name = '', string $password = '', string $tpin = '', bool $test = false): bool
    {
        if ($test) {
            $viewData = ['test' => true];
        } else {
            $viewData = ['user_id' => $user_id, 'full_name' => $full_name, 'password' => $password, 'tpin' => $tpin];
        }

        $view = view('email/post_registration', $viewData);

        $subject = "Login Credentials | " . data('company_name_in_emails');

        return send_email($emailTo, $subject, $view);
    }

    public static function sendOtp(string $email, string $fullName, int $otp): bool
    {
        $view = view('email/registration_otp', compact('fullName', 'otp'));

        $subject = "Confirm your email | " . data('company_name_in_emails');

        return send_email($email, $subject, $view);
    }

    public static function loginSessionData(object $user = null, bool $remove = false, bool $isAdmin = false): object|null
    {
        if ($remove) {

            $sessUser = user();
            if ($sessUser) {
                session()->remove('user');

                if ($sessUser->adminLogin)
                    return null;

                session()->remove(Constants::USER_SESSION_ID_KEY); // ehh, just in case
            }

        } else {

            if (!$user)
                throw new \Exception("user is null when setting on session.");

            if ($isAdmin)
                $user->adminLogin = true;
            else
                $user->adminLogin = false;

            session()->set('user', $user);
        }

        return $user;
    }

    public static function generateUserToken(int $user_id_pk, string $hashAlgo = 'sha256'): string
    {

        $randomValue = bin2hex(random_bytes(16));
        $timestamp = time();
        $combinedValue = $user_id_pk . $timestamp . $randomValue;

        // Hash the combined value
        return hash($hashAlgo, $combinedValue);
    }



    /*
     *------------------------------------------------------------------------------------
     * REMEMBER ME
     *------------------------------------------------------------------------------------
     */
    public static function saveUserSession(int $user_id_pk, bool $remember = false)
    {
        if ($remember) {

            load_helper_if_not_function('cookie', 'set_cookie');
            $ck = _setting('user_login_remember_cookie');
            $rememberToken = UserService::generateUserToken($user_id_pk);
            set_cookie($ck['name'], $rememberToken, $ck['expire']);
        }

        $user_session_id = hash('sha1', $user_id_pk . time());

        session()->set(Constants::USER_SESSION_ID_KEY, $user_session_id); // not an actual session id,but self generated hash, because the actuall session id is beign regenrated after a few secodns.// strong security you know.
        user_model()->saveUserSession($user_id_pk, $user_session_id, $rememberToken ?? null, $ck['expire'] ?? null);
    }

    //either return user object or null
    public static function checkLoginRememberCookie(bool $updateSessionAndCookie = false): object|null
    {

        $ck = _setting('user_login_remember_cookie');

        $rememberToken = trim(prefixed_cookie($ck['name']));

        if ($rememberToken and !empty($rememberToken)) {

            $user = user_model()->verifyRememberToken($rememberToken);

            if ($user) {

                if ($updateSessionAndCookie) {

                    self::saveUserSession($user->id, remember: true); // will update the session and remember expire

                }
                return $user;
            }

        }

        return null;
    }

    public static function removeUserSession(int $user_id_pk, bool $onlyRemoveCookie = false)
    {
        if (user('adminLogin'))
            return;

        load_helper_if_not_function('cookie', 'set_cookie');

        $ck = _setting('user_login_remember_cookie');

        set_cookie($ck['name'], '', 0);

        if ($onlyRemoveCookie) {

            return;
        }
        session()->remove(Constants::USER_SESSION_ID_KEY);
        user_model()->removeUserSession($user_id_pk);
    }

    public static function removeCookiesOnUserLogout()
    {
        load_helper_if_not_function('cookie', 'delete_cookie');
        delete_cookie('dashgrt');
    }
}