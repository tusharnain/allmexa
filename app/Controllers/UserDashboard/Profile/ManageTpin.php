<?php

namespace App\Controllers\UserDashboard\Profile;

use App\Models\UserModel;
use App\Controllers\ParentController;
use App\Services\UserService;
use CodeIgniter\HTTP\ResponseInterface;

class ManageTpin extends ParentController
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
            'text' => label('tpin') . ' last updated at ' . f_date($time) . '.',
            'icon' => 'fa-solid fa-circle-exclamation'
        ]);
    }

    private function changeTpinPost()
    {
        try {

            if ($pValidation = UserService::validateRequestPassword() and is_string($pValidation)) {
                return resJson(['success' => false, 'errors' => ['error' => $pValidation]], 400);
            }

            $hasTpin = $this->userModel->hasTpin(user('id'));

            $res = $this->userModel->changeTpin(user('id'), hasTpin: $hasTpin, isAdmin: false);

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);

            $tpinLabel = label('tpin');

            // last password change alert view
            $lastTpinChanged = memory(user('id') . '_last_tpin_change_at');
            $alertView = $lastTpinChanged ? $this->getLastUpdateAlertHtml($lastTpinChanged) : null;

            return resJson([
                'success' => true,
                'lastTpinAlert' => $alertView,
                'title' => $hasTpin ? "$tpinLabel Updated!" : "$tpinLabel Created!",
                'message' => "Your $tpinLabel has been " . ($hasTpin ? 'changed!' : 'created!')
            ]);

        } catch (\Exception $e) {
            return server_error_ajax($e);
        }
    }





    public function index(): bool|string|ResponseInterface
    {
        // handling post
        if ($this->request->is('post'))
            return $this->changeTpinPost(); // return Response


        $title = 'Manage ' . label('tpin');
        $this->vd = $this->pageData($title, $title, $title);


        // checking if user has tpin or not
        $hasTpin = $this->userModel->hasTpin(user('id'));



        // getting last password updated_at info
        if ($hasTpin) {
            $lastTpinChanged = $this->userModel->getUserDetailsFromUserIdPk(user('id'), ['last_tpin_change_at']);
            if (isset($lastTpinChanged->last_tpin_change_at))
                $lastTpinChangedAlert = $this->getLastUpdateAlertHtml($lastTpinChanged->last_tpin_change_at);
        }





        $this->vd['hasTpin'] = $hasTpin;
        $this->vd['lastTpinChangedAlert'] = $lastTpinChangedAlert ?? null;

        return view('user_dashboard/profile/manage_tpin', $this->vd); // return string
    }
}
