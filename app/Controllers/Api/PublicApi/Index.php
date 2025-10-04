<?php

namespace App\Controllers\Api\PublicApi;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Index extends BaseController
{


    /*
     *------------------------------------------------------------------------------------
     * GET USER NAME FROM USER ID // POST
     *------------------------------------------------------------------------------------
     */
    public function getUserNameFromUserId()
    {
        // Response Status Values 
        // 1 -> User Id is empty or not a string
        // 2 -> Invalid UserId/ User not found
        // 3 -> Success, User Found
        // 4 -> Refer disabled for the user

        $purpose = inputPost('purpose', true);

        $userId = inputPost('user_id');

        if (!$userId or !is_string($userId))
            return resJson(['success' => false, 'status' => 1], 400);

        $username = user_model()->getUserFullNameFromUserId($userId);

        if (!$username)
            return resJson(['success' => false, 'status' => 2], 400);

        if ($purpose === 'refer' && ($user = $this->getUser($userId))) {
            if (!$user->status) {
                return resJson(['success' => false, 'status' => 4], 400);
            }
        }


        return resJson(['success' => true, 'status' => 3, 'username' => $username]);
    }

    private function getUser(string $userId)
    {
        return model(UserModel::class)->where('user_id', $userId)->first();
    }
}