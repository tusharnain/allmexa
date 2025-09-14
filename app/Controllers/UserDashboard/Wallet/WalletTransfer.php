<?php

namespace App\Controllers\UserDashboard\Wallet;

use App\Controllers\ParentController;
use App\Models\WalletModel;
use App\Services\UserService;
use App\Twebsol\Settings;

class WalletTransfer extends ParentController
{
    private WalletModel $walletModel;
    private array $vd = [];


    public function __construct()
    {
        $this->walletModel = new WalletModel;
    }


    private function transferWallet()
    {
        try {
            // Tpin Verification // if errorArray is true, then validate error with is_array, else with is_string
            if (
                $tpinValidation = UserService::validateRequestTpin(errorArray: true)
                and is_array($tpinValidation)
            ) {
                return resJson($tpinValidation, 400);
            }

            $user_id_pk = user('id');

            $res = $this->walletModel->makeWalletTransfer($user_id_pk);

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);

            $memData = memory(WalletModel::MEMORY_WALLET_TRANSFER_DATA_KEY);
            $message = sprintf(
                "%s has been transferred from %s to %s.",
                f_amount($memData['amount']),
                wallet_label($memData['from']),
                wallet_label($memData['to'])
            );

            return resJson([
                'success' => true,
                'title' => 'Wallet Transfer Successful!',
                'message' => $message,
            ]);


        } catch (\Exception $e) {

            server_error_ajax($e);

        }
    }


    private function getToWalletSelect()
    {
        $from = inputPost('from');

        if (!$from or !is_string($from) or !in_array($from, Settings::get_wallet_transfer_from_wallets()))
            return ajax_404_response();

        $toWallets = Settings::WALLET_TRANSFER_RULES[$from];
        $walletList = [];

        foreach ($toWallets as $wallet)
            $walletList[wallet_label($wallet)] = $wallet;

        return resJson([
            'success' => true,
            'html' => user_component('select', [
                'name' => 'to',
                'label' => 'To Wallet',
                'options' => $walletList,
                'empty_option' => 'Select Wallet',
                'disable_empty_option' => true,
                'select_empty_option' => true
            ])
        ]);
    }

    private function handlePost()
    {
        $action = inputPost('action');

        switch ($action) {

            case 'get_to_wallet_select':
                return $this->getToWalletSelect();

            case 'submit_wallet_transfer':
                return $this->transferWallet();

        }


        return ajax_404_response();
    }


    public function index()
    {

        if ($this->request->is('post'))
            return $this->handlePost();


        $title = 'Wallet Transfer';
        $this->vd = $this->pageData($title, $title, $title);

        function __s(array $wallets)
        {
            $arr = [];
            foreach ($wallets as $wallet)
                $arr[wallet_label($wallet)] = $wallet;
            return $arr;
        }

        $this->vd['fromWallets'] = $fromWallets = __s(wallets: Settings::get_wallet_transfer_from_wallets());


        $this->vd['toSelectHtml'] = user_component('select', [
            'name' => 'to',
            'label' => 'To Wallet',
            'empty_option' => 'Select Wallet',
            'disabled' => true
        ]);


        return view('user_dashboard/wallet/wallet_transfer', $this->vd);
    }
}
