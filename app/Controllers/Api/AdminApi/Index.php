<?php

namespace App\Controllers\Api\AdminApi;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Index extends BaseController
{
    /*
     *------------------------------------------------------------------------------------
     * GET USER Details
     *------------------------------------------------------------------------------------
     */
    public function getUserDetails(): ResponseInterface
    {
        // accepting user_id as post inupt
        $user_id = inputPost('user_id');
        $send_view = request()->getPost('view');

        if ($user_id and is_numeric($user_id) and $user_id > 0) {

            $user = user_model()->getUserFromUserId($user_id);

            $user->profileUrl = route('admin.users.user', $user->user_id);

            if ($user) {

                $data = ['success' => true, 'user' => $user];

                if ($send_view == 1)
                    $data['view'] = view('admin_dashboard/components/user_offcanvas');

                return resJson($data);
            }

        }

        return resJson(['status' => false], 400);
    }


}