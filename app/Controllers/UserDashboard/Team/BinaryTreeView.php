<?php

namespace App\Controllers\UserDashboard\Team;

use App\Controllers\ParentController;
use App\Libraries\MyLib;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class BinaryTreeView extends ParentController
{
    private array $vd = [];
    private UserModel $userModel;
    private array $tree;

    public function __construct()
    {
        $this->userModel = new UserModel;
    }


    private function getUserBinaryTreeView()
    {
        try {

            $user_id_pk = inputPost('user_id'); // getting user id pk

            if ($user_id_pk and is_numeric($user_id_pk)) {

                $tree = $this->userModel->binary_getUserBinaryTreeFromUserIdPk($user_id_pk);

                if ($tree) {
                    return resJson([
                        'success' => true,
                        'view' => MyLib::minifyHtmlCssJs(view('user_dashboard/team/_binary_user_tree', ['tree' => &$tree]))
                    ]);
                }
            }

            return ajax_404_response();

        } catch (\Exception $e) {

            return server_error_ajax($e);

        }
    }


    private function getUserBinaryDetails()
    {
        try {

            $user_id_pk = inputPost('user_id'); // getting user id pk

            if ($user_id_pk and is_numeric($user_id_pk)) {

                $user = $this->userModel->getUserFromUserIdPk($user_id_pk, ['full_name']);

                if ($user) {


                    return resJson([
                        'success' => true,
                        'view' => MyLib::minifyHtmlCssJs(view('user_dashboard/team/_binary_user_details', ['user' => $user]))
                    ]);

                }
            }

            return ajax_404_response();

        } catch (\Exception $e) {

            return server_error_ajax($e);

        }
    }


    public function handlePost()
    {

        $action = inputPost('action');

        if (!$action)
            return ajax_404_response();

        switch ($action) {

            case 'get_user_binary_tree_view':
                return $this->getUserBinaryTreeView();

            case 'get_user_binary_details':
                return $this->getUserBinaryDetails();

            default:
                return ajax_404_response();
        }
    }


    public function index(): bool|string|ResponseInterface
    {
        $title = 'Binary Tree View';
        $this->vd = $this->pageData($title, $title, $title);


        $this->tree = $this->userModel->binary_getUserBinaryTreeFromUserIdPk(user('id'));


        $this->vd['tree'] = $this->tree;
        return view('user_dashboard/team/binary_tree_view', $this->vd); // return string
    }
}
