<?php

namespace App\Controllers\UserDashboard\Profile;

use App\Models\UserModel;
use App\Enums\NomineeRelation;
use App\Controllers\ParentController;

class Profile extends ParentController
{
    private array $vd = [];
    private UserModel $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel;
    }


    public function updateDetailsPost()
    {
        try {

            $user = user();

            // User Update
            $userUpdate = $this->userModel->updateUser(user_id_pk: $user->id, isAdmin: false);
            if (is_array($userUpdate))
                return resJson(['success' => false, 'errors' => $userUpdate], 400);


            // User Details Update
            $userDetailsUpdate = $this->userModel->updateUserDetails(user_id_pk: $user->id, isAdmin: false);
            if (is_array($userDetailsUpdate))
                return resJson(['success' => false, 'errors' => $userDetailsUpdate], 400);

            $sessUser = session('user');
            $pfpUrl = user('profile_picture') !== $sessUser->profile_picture ? UserModel::getAvatar($sessUser) : null;

            return resJson([
                'success' => true,
                'profile_picture_url' => $pfpUrl,
                'title' => 'Profile Updated!',
                'message' => 'Changes to the profile has been saved!'
            ]);

            // session()->setFlashdata('notif', ['title' => 'Profile Updated!', 'message' => 'Your profile has been updated successfully!']);

            // return resJson(['f_redirect' => route('user.profile.profileUpdate')], 400);

        } catch (\Exception $e) {

            return server_error_ajax($e);
        }
    }

    public function index()
    {

        $title = label('user') . ' Profile';
        $this->vd = $this->pageData($title, $title, $title);

        //fetching user
        $this->vd['user'] = $this->userModel->getUserFromUserIdPk(user('id'));
        $this->vd['ud'] = $this->userModel->getUserDetailsFromUserIdPk(user('id')); //getting user details from user_details table

        $this->vd['nomineeRelations'] = NomineeRelation::RELATIONS;

        return view('user_dashboard/profile/profile', $this->vd);
    }
}
