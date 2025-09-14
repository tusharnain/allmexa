<?php

namespace App\Controllers\UserAuth;

use App\Models\UserModel;
use App\Services\Captcha;
use App\Controllers\ParentController;
use App\Services\UserService;


class LoginLogout extends ParentController
{
    private UserModel $userModel;
    private string $captchaKey = 'USER_LOGIN_C';
    private int $captchaSize = 4;

    const WRONG_PASSWORD_LOGIN_ATTEMPT_SESSION_KEY = 'wrong_password_login_attempt';

    public function __construct()
    {
        $this->userModel = new UserModel;
    }

    public function loginCaptchaImage(bool $base64 = false)
    {

        $captcha = new Captcha($this->captchaKey);
        $captcha->build();
        $captcha->backgroundColor = "108,95,252,20";
        $captcha->textColor = "255,255,255";
        $captcha->stringType = 2;
        $captcha->gap = 10;
        $captcha->angle = 30;
        $captcha->stringSize = $this->captchaSize;
        $captcha->width = 120;

        if ($base64)
            return $captcha->getBase64();

        $captcha->imageAndExit();
    }



    public function logoutPost()
    {
        UserService::removeUserSession(user('id'));
        UserService::loginSessionData(remove: true);
        UserService::removeCookiesOnUserLogout();

        return response()->redirect(route('login'));
    }


    private function saveLoginLog(int $user_id_pk, bool $remember = false, string $status = UserModel::LoginLogStatus_SUCCESS, string $message = null)
    {
        $ua = $this->request->getUserAgent();

        $userAgentString = $ua->getAgentString();

        if (strlen($userAgentString) > 255)
            $userAgentString = substr($userAgentString, 0, 255);

        $ip = $this->request->getIPAddress();

        if ($ip === '::1')
            $ip = '127.0.0.1';

        $this->userModel->saveLoginLog(
            user_id_pk: $user_id_pk,
            ip_address: $ip,
            os: $ua->getPlatform(),
            browser: $ua->getBrowser(),
            user_agent: $userAgentString,
            remember_login: $remember,
            status: $status,
            message: $message
        );
    }


    private function wrongPassword(int $user_id_pk)
    {
        $wrongAttempt = session()->get(self::WRONG_PASSWORD_LOGIN_ATTEMPT_SESSION_KEY) ?? 1;

        if (
            $wrongAttempt
            and $wrongAttempt > 4
            and ($wrongAttempt % 5 === 0)
        ) {

            $this->saveLoginLog(
                $user_id_pk,
                remember: false,
                status: UserModel::LoginLogStatus_FAIL,
                message: "Login Failed : $wrongAttempt wrong password login attempts!",
            );

        }

        session()->set(self::WRONG_PASSWORD_LOGIN_ATTEMPT_SESSION_KEY, $wrongAttempt + 1);
    }

    public function loginPost()
    {
        try {
            $login = $this->userModel->login();

            // if $login is array, it means its validation error
            if (is_array($login)) {

                if (isset($login['wrong_password']))
                    $this->wrongPassword(user_id_pk: $login['wrong_password']);

                return resJson(['success' => false, 'errors' => $login], 400);
            }

            session()->remove(self::WRONG_PASSWORD_LOGIN_ATTEMPT_SESSION_KEY);

            // else $login is $user object
            $user = &$login;

            $remember = request()->getPost('rememberMe') ? true : false;

            UserService::loginSessionData(user: $user);

            UserService::saveUserSession(user_id_pk: $user->id, remember: $remember);

            // Saving Login Log
            $this->saveLoginLog($user->id, remember: $remember);

            return resJson([
                'success' => true,
                'redirectTo' => route('user.home'),
            ]);

        } catch (\Exception $e) {

            return server_error_ajax($e);
        }
    }


    public function login()
    {
        $postRegData = session()->getFlashdata('post_reg_data') ?? null;

        if ($postRegData) {
            $postRegHtml = view('user_auth/__post_registration', $postRegData);
        }


        return view('user_auth/login', [
            'page_title' => 'Login',
            'captchaSize' => $this->captchaSize,
            'postRegHtml' => $postRegHtml ?? null
        ]);
    }
}
