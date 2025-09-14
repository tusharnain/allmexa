<?php

namespace App\Controllers\UserAuth;

use App\Models\UserModel;
use App\Services\Captcha;
use App\Libraries\UserLib;
use App\Services\UserService;
use App\Services\InputService;
use App\Controllers\ParentController;



class Register extends ParentController
{
    private ?object $refer = null;
    private UserModel $userModel;
    private string $captchaKey = 'USER_REGISTRATION_C';
    private int $captchaSize = 4;

    public function __construct()
    {
        $this->userModel = new UserModel;
    }

    public function captchaImage(bool $base64 = false)
    {
        $captcha = new Captcha($this->captchaKey);
        $captcha->build();
        $captcha->backgroundColor = "255,151,29";
        $captcha->textColor = "255,255,255";
        $captcha->gap = 15;
        $captcha->angle = 30;
        $captcha->stringSize = $this->captchaSize;
        $captcha->width = 165;
        $captcha->stringType = 2;

        if ($base64)
            return $captcha->getBase64();

        $captcha->imageAndExit();
    }


    public function registerPost() // Ajax
    {
        try {
            $inputs = InputService::inputRegistrationValues();

            $regResult = $this->userModel->register($inputs, forOtp: true);


            // if $regResult is array, it means its validation error
            if (is_array($regResult) && !isset($regResult['success'])) {
                return resJson(['success' => false, 'errors' => $regResult], 400);
            }

            $userData = $regResult;

            unset($userData['success']);

            $otp = mt_rand(111111, 999999);

            session()->set('reg_otp_' . $userData['email'], [
                'otp' => $otp,
                'expire_at' => strtotime('+1 hour'),
                'plain_password' => $inputs['password'],
                'plain_tpin' => $inputs['tpin']
            ]);

            UserService::sendOtp($userData['email'], $userData['full_name'], $otp);

            $otpUrl = route('confirmOtp') . '?payload=' . urlencode(simple_encrypt_array($userData));

            return resJson(['success' => true, 'otp_url' => $otpUrl], 201);


            /// old code

            // $user = [
            //     ...$inputs,
            //     'user_id' => $regResult->userId,
            //     'joining_date' => $regResult->joiningDate
            // ];

            // $user['textContent'] = UserLib::getAfterRegistrationText($user);
            // $user['textFileName'] = UserLib::getAfterRegistrationTextFileName($user);
            // $user['imageFileName'] = UserLib::getAfterRegistrationImageFileName($user);




            // $rendered = view('user_auth/__post_registration', $user);


            // sending credentials on email if enabled
            // if (_setting('email_login_credentials_after_registration')) {
            //     UserService::emailLoginCredentials($user['email'], $user['user_id'], $user['full_name'], $user['password'], $user['tpin']);
            // }

            // $data = ['success' => true, 'html' => $rendered];

            // if (_setting('registration_captcha'))
            //     $data['captchaBase64'] = $this->captchaImage(true);

            // return resJson($data, 201);

        } catch (\Exception $e) {

            return server_error_ajax($e);
        }
    }

    public function index()
    {
        return view('user_auth/register', [
            'page_title' => 'Registration',
            'captchaSize' => $this->captchaSize,
            'refer' => $this->refer
        ]);
    }


    public function referral(string $refer_user_id)
    {
        if (!($user = $this->userModel->getUserFromUserId($refer_user_id, ['full_name'])) or !isset($user->full_name))
            return show_404();


        $this->refer = new \stdClass();
        $this->refer->user_id = $refer_user_id;
        $this->refer->name = $user->full_name;

        return $this->index();
    }

    public function otpPage()
    {
        $payload = inputGet('payload');

        if (!$payload || empty(trim($payload))) {
            return redirect('login');
        }

        $userData = simple_decrypt_array($payload);

        $user = user_model()->select('id')->where('email', $userData['email'])->first();

        if (isset($user->id)) {
            return redirect('login');
        }

        $otpData = isset($userData['email']) ? session()->get('reg_otp_' . ($userData['email'])) : null;

        if (!$otpData || !isset($otpData['otp'])) {
            return redirect('login');
        }

        if ($this->request->is('post')) {

            $inputOtp = inputPost('otp');
            $masterOtp = _setting('master_register_otp');
            $otp = $otpData['otp'];

            if ($masterOtp && ($masterOtp == $inputOtp)) {
            } else if ($otp != $inputOtp) {
                session()->setFlashdata('__error', 'Incorrect OTP');
                return response()->redirect(route('confirmOtp') . '?payload=' . urlencode($payload));
            }

            $userData['user_id'] = UserLib::generateNewUserId();
            user_model()->insert($userData);

            // session()->setFlashdata('register_done')

            if (_setting('email_login_credentials_after_registration')) {
                UserService::emailLoginCredentials(
                    $userData['email'],
                    $userData['user_id'],
                    $userData['full_name'],
                    $otpData['plain_password'],
                    $otpData['plain_tpin']
                );
            }

            // $rendered = view('user_auth/__post_registration', [
            //     ...$userData,
            //     'password' => $otpData['plain_password'],
            //     'tpin' => $otpData['plain_tpin'],
            // ]);

            $postRegData = [
                ...$userData,
                'password' => $otpData['plain_password'],
                'tpin' => $otpData['plain_tpin'],
            ];

            session()->setFlashdata('post_reg_data', $postRegData);

            return redirect('login');
        }

        return view('user_auth/registration_otp', [
            'page_title' => 'Confirm Email',
            'url_payload' => $payload
        ]);
    }

}

