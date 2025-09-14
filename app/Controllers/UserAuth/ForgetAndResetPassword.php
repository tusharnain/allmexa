<?php

namespace App\Controllers\UserAuth;

use App\Enums\UserToken\UserTokenStatus;
use App\Enums\UserToken\UserTokenType;
use App\Libraries\MyLib;
use App\Libraries\UserLib;
use App\Models\UserModel;
use App\Controllers\ParentController;
use \stdClass;

class ForgetAndResetPassword extends ParentController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel;

        if (!_setting('user_forget_password', false))
            show_404();
    }

    private function validateUserId(): stdClass|array
    {
        $userId = inputPost('user_id');
        $userIdLabel = label('user_id');

        if (!$userId or !is_string($userId) or empty($userId) or !($user = get_user(user_id: $userId, columns: ['id', 'user_id', 'full_name', 'email'], is_user_id_pk: false)))
            return ['success' => false, 'errors' => ['error' => "Invalid $userIdLabel."]];
        return $user;
    }
    private function getResetPasswordUrl(string $token): string
    {
        return route('resetPassword') . "?confirm=$token";
    }


    private function handleResetPasswordPost(stdClass|null $tokenObject)
    {
        try {
            if (is_null($tokenObject))
                return resJson(['success' => 'false', 'expired' => true], 400);

            // $tokenObject must have props -> id, user_id and token string
            $res = $this->userModel->resetPassword(tokenObject: $tokenObject);

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);

            return resJson(['success' => true, 'message' => 'Password changed successfully!']);

        } catch (\Exception $e) {

            return server_error_ajax($e);

        }
    }


    public function resetPassword()
    {
        $token = inputGet('confirm') ?? inputPost('token');
        if (is_null($token) or !is_string($token) or empty($token))
            show_404();

        $tokenObject = $this->userModel->getUserTokenFromToken(token: $token, token_type: UserTokenType::PASSWORD_RESET);

        $validToken = false;

        if ($this->request->is('post'))
            return $this->handleResetPasswordPost(tokenObject: $tokenObject);

        if ($tokenObject) {
            $validToken = true;
            // extending the token
            if ($tokenObject->status !== UserTokenStatus::EXTENDED)
                $this->userModel->extendUserToken(token: $tokenObject, extend_seconds: 300);
        }

        return view('user_auth/reset_password', [
            'page_title' => 'Reset Password',
            'token' => $token,
            'validToken' => $validToken
        ]);
    }




    /*
     *------------------------------------------------------------------------------------
     * Forget Password
     *------------------------------------------------------------------------------------
     */
    private function forgetPasswordGetEmail()
    {
        if ($res = $this->validateUserId() and is_array($res))
            return resJson($res, 400);
        $user = $res; // user object

        return resJson([
            'success' => true,
            'user' => [
                'user_id' => $user->user_id,
                'email' => MyLib::obfuscateEmailAsterisk(email: $user->email)
            ]
        ]);
    }
    private function sendResetPasswordEmail()
    {
        if ($res = $this->validateUserId() and is_array($res))
            return resJson($res, 400);
        $user = $res; // user object

        // generating Reset Password Token
        $token = UserLib::generateUserToken();
        $url = $this->getResetPasswordUrl(token: $token);

        $this->userModel->saveUserToken(
            user_id_pk: $user->id,
            token_type: UserTokenType::PASSWORD_RESET,
            token: $token,
            expireIn: 1800 // 30 minutes
        );

        $view = view('email/reset_password', ['user' => $user, 'url' => $url]);
        $company = data('company_name');
        $subject = "Reset your $company account password";
        send_email(toEmail: $user->email, subject: $subject, message: $view);

        return resJson([
            'success' => true,
            'email' => MyLib::obfuscateEmailAsterisk(email: $user->email)
        ]);
    }
    private function handleForgetPasswordPost()
    {
        $action = inputPost('action');
        switch ($action) {
            case 'get_email':
                return $this->forgetPasswordGetEmail();
            case 'reset_password_email':
                return $this->sendResetPasswordEmail();
        }
        return ajax_404_response();
    }
    public function forgetPassword()
    {
        if ($this->request->is('post'))
            return $this->handleForgetPasswordPost();

        return view('user_auth/forget_password', ['page_title' => 'Forget Password']);
    }
}
