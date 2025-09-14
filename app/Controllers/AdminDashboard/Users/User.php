<?php

namespace App\Controllers\AdminDashboard\Users;


use App\Models\UserModel;
use App\Enums\NomineeRelation;
use App\Controllers\ParentController;
use App\Services\UserService;


class User extends ParentController
{
    private null|object $user;
    private array $vd = [];
    protected UserModel $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    private function validateUserId(string &$user_id, bool $isAjax = false)
    {
        $this->user = $this->userModel->getUserFromUserId($user_id);

        if (!$this->user)
            return $isAjax ? ajax_404_response() : show_404();
    }


    private function setupPageLayout()
    {
        $user_label = label('user');
        $this->vd = $this->pageData("{$this->user->user_id} | {$this->user->full_name} | {$user_label}", "{$this->user->user_id} ({$this->user->full_name}) / {$user_label} Profile", "{$user_label} #{$this->user->user_id}");
    }


    /*
     *------------------------------------------------------------------------------------
     * Login as User | Post
     *------------------------------------------------------------------------------------
     */
    private function loginAsUser()
    {
        UserService::loginSessionData(user: $this->user, isAdmin: true);
        return redirect()->to(route('user.home'));
    }


    /*
     *------------------------------------------------------------------------------------
     * Update Details | POST
     *------------------------------------------------------------------------------------
     */
    private function updateUserProfile()
    {
        try {
            // User Update
            $userUpdate = $this->userModel->updateUser(user_id_pk: $this->user->id, isAdmin: true);
            if (is_array($userUpdate))
                return resJson(['success' => false, 'errors' => $userUpdate], 400);


            // User Details Update
            $userDetailsUpdate = $this->userModel->updateUserDetails(user_id_pk: $this->user->id, isAdmin: true);
            if (is_array($userDetailsUpdate))
                return resJson(['success' => false, 'errors' => $userDetailsUpdate], 400);


            if ($newPfp = memory('admin_pfp_updated'))
                $newPfp = UserModel::getAvatarFromImageName($newPfp);

            return resJson(['success' => true, 'profile_picture_url' => $newPfp]);

        } catch (\Exception $e) {

            return server_error_ajax($e);

        }

    }


    /*
     *------------------------------------------------------------------------------------
     * Change User Password | POST
     *------------------------------------------------------------------------------------
     */
    private function changePassword()
    {
        try {

            $res = $this->userModel->changePassword($this->user->id, isAdmin: true);

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);


            return resJson(['success' => true]);

        } catch (\Exception $e) {
            return server_error_ajax($e);
        }
    }

    /*
     *------------------------------------------------------------------------------------
     * Change User TPIN | POST
     *------------------------------------------------------------------------------------
     */
    private function changeTpin()
    {
        try {
            // pretending that hasTpin is false, coz is doesnt matter, coz its admin
            $res = $this->userModel->changeTpin($this->user->id, hasTpin: false, isAdmin: false);

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);

            return resJson(['success' => true]);

        } catch (\Exception $e) {
            return server_error_ajax($e);
        }

    }
    
    public function activate()
    {
        try {

            if (!$this->user->status) {
                $this->userModel->activateUser($this->user->id);
            }

            return resJson(['success' => true]);

        } catch (\Exception $e) {
            return server_error_ajax($e);
        }
    }



    public function handlePost(string $user_id)
    {
        $action = inputPost('action');

        $this->validateUserId($user_id, isAjax: true);

        switch ($action) {
            case 'profile':
                return $this->updateUserProfile();

            case 'password':
                return $this->changePassword();

            case 'tpin':
                return $this->changeTpin();

            case 'login':
                return $this->loginAsUser();
                
            case 'activate':
                return $this->activate();
        }

        return ajax_404_response();
    }


    public function index(string $user_id)
    {
        if ($this->request->is('post'))
            return $this->handlePost($user_id);


        $this->validateUserId($user_id);

        $this->setupPageLayout(); // its position can't be changed, it must run first to store the page Layout, if not you will get errors

        $this->vd['user'] = $this->user; // getting user data from usrs table

        $this->vd['userDetails'] = $this->userModel->getUserDetailsFromUserIdPk($this->user->id); //getting user details from user_details table

        $this->vd['sponsor'] = $this->user->sponsor_id ? $this->userModel->getUserFromUserIdPk($this->user->sponsor_id) : null;


        $this->vd['nomineeRelations'] = NomineeRelation::RELATIONS;

        return view('admin_dashboard/users/user', $this->vd);
    }
}
