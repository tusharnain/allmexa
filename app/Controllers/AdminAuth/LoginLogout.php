<?php

namespace App\Controllers\AdminAuth;


use App\Controllers\ParentController;
use App\Models\AdminModel;

class LoginLogout extends ParentController
{
    private AdminModel $adminModel;
    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }


    private function loginSessionData(\stdClass $admin = null, bool $remove = false)
    {
        if ($remove) {

            session()->remove('admin');

        } else {

            if (!$admin)
                throw new \Exception("admin is null when setting on session.");


            $admin->loginId = mt_rand(11111, 99999);

            session()->set('admin', $admin);
        }
    }


    public function loginPost()  // Ajax API
    {
        $loginResult = $this->adminModel->login();

        // if $loginResult is array, it means its validation error
        if (is_array($loginResult)) {
            return resJson(['success' => false, 'errors' => $loginResult], 400);
        }

        // else $loginResult is $admin object
        $admin = $loginResult;


        $this->loginSessionData(admin: $admin);

        return resJson(['success' => true, 'redirectTo' => route('admin.users.userKyc')]);
    }



    public function logoutPost()
    {

        $this->loginSessionData(remove: true);

        return response()->redirect(route('admin.login'));
    }




    public function login(): string
    {
        return view('admin_auth/login', [
            'page_title' => 'Admin Login'
        ]);
    }
}
