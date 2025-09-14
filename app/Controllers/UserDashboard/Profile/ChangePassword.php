<?php

namespace App\Controllers\UserDashboard\Profile;

use App\Models\UserModel;
use App\Controllers\ParentController;
use CodeIgniter\HTTP\ResponseInterface;

class ChangePassword extends ParentController
{
    private array $vd = [];
    private UserModel $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel;
    }


    private function getLastUpdateAlertHtml(string $time): string
    {
        return user_component('alert', [
            'text' => "Password last updated at " . f_date($time),
            'icon' => 'fa-solid fa-circle-exclamation',
        ]);
    }

    private function changePasswordPost()
    {
        try {
            $res = $this->userModel->changePassword(user('id'), isAdmin: false);

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);

            // last password change alert view
            $lastPasswordChanged = memory(user('id') . '_last_password_change_at');

            $alertView = $lastPasswordChanged ? $this->getLastUpdateAlertHtml($lastPasswordChanged) : null;

            return resJson([
                'success' => true,
                'lastPasswordAlert' => $alertView,
                'title' => "Password Changed!",
                'message' => "Your account password has been changed!"
            ]);

        } catch (\Exception $e) {

            return server_error_ajax($e);

        }
    }





    public function index(): bool|string|ResponseInterface
    {
        // handling post
        if ($this->request->is('post'))
            return $this->changePasswordPost(); // return Response


        $title = 'Change Password';
        $this->vd = $this->pageData($title, $title, $title);


        // getting last password updated_at info
        $lastPasswordChanged = $this->userModel->getUserDetailsFromUserIdPk(user('id'), ['last_password_change_at']);
        if (isset($lastPasswordChanged->last_password_change_at))
            $lastPasswordChangedAlert = $this->getLastUpdateAlertHtml($lastPasswordChanged->last_password_change_at);


        $this->vd['lastPasswordChangedAlert'] = $lastPasswordChangedAlert ?? null;

        return view('user_dashboard/profile/change_password', $this->vd); // return string
    }
}
