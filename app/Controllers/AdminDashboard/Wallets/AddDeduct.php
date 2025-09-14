<?php

namespace App\Controllers\AdminDashboard\Wallets;


use App\Controllers\ParentController;
use App\Models\UserModel;
use App\Models\WalletModel;
use App\Services\WalletService;

class AddDeduct extends ParentController
{
    private UserModel $userModel;
    private WalletModel $walletModel;
    private array $vd;

    public function __construct()
    {
        $this->userModel = new userModel;
        $this->walletModel = new WalletModel;
    }

    private function searchUserIdPost()
    {

        try {

            $user_id = inputPost('user_id');

            $userIdLabel = label('user_id');

            if (!$user_id or !is_string($user_id) or empty($user_id))
                return resJson(['success' => false, 'errors' => ['error' => "$userIdLabel is required."]], 400);

            if (!($user = $this->userModel->getUserFromUserId($user_id, ['id', 'user_id', 'full_name'])))
                return resJson(['success' => false, 'errors' => ['error' => "$userIdLabel is invalid."]], 400);

            $wallets = $this->walletModel->getAllWalletsFromUserIdPk($user->id);

            $formView = view('admin_dashboard/wallets/__add_deduct_form', [
                'user' => $user,
                'wallets' => $wallets
            ]);

            return resJson([
                'success' => true,
                'user' => $user,
                'wallets' => $wallets,
                'view' => \App\Libraries\MyLib::minifyHtmlCssJs($formView)
            ]);

        } catch (\Exception $e) {

            return server_error_ajax($e);

        }
    }


    private function addDeductSubmitPost()
    {
        try {

            $res = $this->walletModel->admin_addDeduct();

            // if $res is array, it means its validation error
            if (is_array($res)) {
                return resJson(['success' => false, 'errors' => $res], 400);
            }

            // Making message
            $data = memory('admin_add_deduct_data');
            $amount = f_amount($data['amount']);
            $user_id_pk = $data['user_id_pk'];
            $fullName = $data['full_name'];
            $type = $data['type'] == 'credit' ? 'credited to' : 'debited from';
            $wallet_index = $data['wallet'];
            $wallet_label = wallet_label(WalletService::WALLETS[$wallet_index]);

            $msg = "The amount of $amount has been $type $fullName's $wallet_label";


            // his wallet balance
            $wallets = $this->walletModel->getAllWalletsFromUserIdPk($user_id_pk);

            return resJson([
                'success' => true,
                'title' => 'Transaction Complete!',
                'message' => $msg,
                'wallet' => $wallet_index,
                'wallet_select' => view('admin_dashboard/wallets/mini/_add_deduct_wallet_select', ['wallets' => $wallets])
            ]);

        } catch (\Exception $e) {
            return server_error_ajax($e);
        }
    }

    private function handlePost()
    {
        $action = inputPost('action');

        switch ($action) {
            case 'search_user':
                return $this->searchUserIdPost();

            case 'add_deduct':
                return $this->addDeductSubmitPost();
        }

        return ajax_404_response();
    }




    public function index()
    {
        if ($this->request->is('post'))
            return $this->handlePost();

        $title = 'Add/Deduct Wallet';
        $this->vd = $this->pageData($title, $title, $title);

        return view('admin_dashboard/wallets/add-deduct', $this->vd);
    }

}
