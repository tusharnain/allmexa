<?php

namespace App\Controllers\UserDashboard\P2PTransfer;

use App\Models\WalletModel;
use App\Services\UserService;
use App\Controllers\ParentController;


class P2PTransfer extends ParentController
{
    private array $vd = [];
    private WalletModel $walletModel;

    public function __construct()
    {
        $this->walletModel = new WalletModel;
    }

    private function makeAmountTransfer()
    {
        try {

            // Tpin Verification // if errorArray is true, then validate error with is_array, else with is_string
            if (
                $tpinValidation = UserService::validateRequestTpin(errorArray: true, errorTitle: "Transfer Failed!")
                and is_array($tpinValidation)
            ) {
                return resJson($tpinValidation, 400);
            }

            $res = $this->walletModel->makeP2PTransfer(user_id_pk: user('id'));


            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);

            $memData = memory('p2p_transfer_data');
            $amount = f_amount($memData['amount']);
            $receiverName = $memData['receiver_full_name'];

            $message = "The amount of $amount is successfully transferred to $receiverName's account.";

            $walletBalance = $this->walletModel->getWalletBalanceFromUserIdPk(user_id_pk: user('id'), wallet_field: 'fund');

            return resJson([
                'success' => true,
                'title' => "Transfer Complete!",
                'message' => $message,
                'walletBalance' => &$walletBalance,
                'fWalletBalance' => f_amount($walletBalance)
            ]);

        } catch (\Exception $e) {

            return server_error_ajax($e);

        }
    }

    private function handlePost()
    {
        $action = inputPost('action');

        switch ($action) {

            case 'make_amount_transfer':
                return $this->makeAmountTransfer();

        }

        return ajax_404_response();
    }

    public function index()
    {
        if ($this->request->is('post'))
            return $this->handlePost();


        $title = 'P2P Transfer';
        $this->vd = $this->pageData($title, $title, $title);


        $walletBalance = $this->walletModel->getWalletBalanceFromUserIdPk(user('id'), 'fund');

        $this->vd['walletBalance'] = &$walletBalance;

        return view('user_dashboard/p2p_transfer/p2p_transfer', $this->vd); // return string
    }
}
