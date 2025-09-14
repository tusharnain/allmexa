<?php

namespace App\Controllers\AdminDashboard\Users;

use App\Libraries\UserLib;
use App\Models\UserModel;
use App\Services\UserService;
use App\Services\InputService;
use App\Controllers\ParentController;

class AddNewUser extends ParentController
{
    private UserModel $userModel;
    private array $vd;
    private bool $isUsersTableEmpty;

    public function __construct()
    {
        $this->userModel = new UserModel;

        $this->isUsersTableEmpty = $this->userModel->isTableEmpty();

        $this->vd['email_creds'] = _setting('email_login_credentials_after_registration', false);
    }



    private function registerPost() //Ajax
    {
        try {

            $inputs = InputService::inputRegistrationValues();

            $regResult = $this->userModel->register($inputs, isAdmin: true, isFirstUser: $this->isUsersTableEmpty);


            // if $regResult is array, it means its validation error
            if (is_array($regResult)) {
                return resJson(['success' => false, 'errors' => $regResult], 400);
            }

            // else $regResult is object

            $user = [
                ...$inputs,
                'user_id' => $regResult->userId,
                'joining_date' => $regResult->joiningDate
            ];

            // $user['textContent'] = UserLib::getAfterRegistrationText($user);
            // $user['textFileName'] = UserLib::getAfterRegistrationTextFileName($user);
            // $user['imageFileName'] = UserLib::getAfterRegistrationImageFileName($user);


            $rendered = view('admin_dashboard/users/__post_registration', $user);

            if (
                isset($this->vd['email_creds'])
                and $this->vd['email_creds']
                and request()->getPost('email_login_creds')
            ) {
                UserService::emailLoginCredentials($user['email'], $user['user_id'], $user['full_name'], $user['password']);
            }

            return resJson(['success' => true, 'html' => $rendered], 201);


        } catch (\Exception $e) {

            return server_error_ajax($e);
        }

    }


    private function handlePost()
    {
        $action = inputPost('action');

        switch ($action) {
            case 'register_post':
                return $this->registerPost();
        }

        return ajax_404_response();
    }



    public function index()
    {
        if ($this->request->is('post'))
            return $this->handlePost();


        $user_label = label('user');

        if ($this->isUsersTableEmpty) {

            $sponsorIdLabel = label('sponsor_id');

            $this->vd['noUsers'] = "There are 0 users in the database. For starting registrations, you have to register the first user.<br/>Since no users exists, the $sponsorIdLabel field will be not available for now.";

        } else {
            $this->vd['noUsers'] = null;
        }



        $pageData = $this->pageData("Add new $user_label", "Register new $user_label", 'New Registration');

        return view('admin_dashboard/users/add_new_user', [
            ...$this->vd,
            ...$pageData
        ]);
    }
}
