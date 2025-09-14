<?php

namespace App\Controllers\Api\PublicApi;

use App\Controllers\BaseController;

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

        $userId = inputPost('user_id');

        if (!$userId or !is_string($userId))
            return resJson(['success' => false, 'status' => 1], 400);

        $username = user_model()->getUserFullNameFromUserId($userId);

        if (!$username)
            return resJson(['success' => false, 'status' => 2], 400);


        return resJson(['success' => true, 'status' => 3, 'username' => $username]);
    }

}