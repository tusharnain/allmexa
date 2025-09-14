<?php

namespace App\Controllers\UserDashboard\Withdrawal;

use App\Models\WalletModel;
use App\Services\UserService;
use App\Models\WithdrawalModel;
use App\Controllers\ParentController;
use App\Services\WalletService;

class WithdrawNow extends ParentController
{
    private array $vd = [];
    private WithdrawalModel $withdrawalModel;
    private WalletModel $walletModel;
    private $userWalletAddress = null;
    private $bankAccount = null;

    public function __construct()
    {
        $this->withdrawalModel = new WithdrawalModel;
        $this->walletModel = new WalletModel;
        $this->userWalletAddress = $this->withdrawalModel->getUserWalletAddressDetailsFromUserIdPk(user_id_pk: user('id'));
        $this->userBankAccount = $this->withdrawalModel->getUserBankDetailsFromUserIdPk(user_id_pk: user('id'));
    }


    private function submitWithdrawalForm()
    {
        try {
            // Tpin Verification // if errorArray is true, then validate error with is_array, else with is_string
            if (!$this->userWalletAddress && !$this->userBankAccount)
                return null;
            if (
                $tpinValidation = UserService::validateRequestTpin(errorArray: true)
                and is_array($tpinValidation)
            ) {
                return resJson($tpinValidation, 400);
            }

            $user = user();

            $res = $this->withdrawalModel->makeWithdrawal($user->id);

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);

            // getting wallet balance again
            $walletBal = $this->walletModel->getWalletBalanceFromUserIdPk($user->id, WalletService::WITHDRAW_FROM_WALLET);

            return resJson([
                'success' => true,
                'title' => 'Withdrawal Success!',
                'message' => "Withdrawal has been requested. <br>  You'll be notified, once its resolved.",
                'fWalletBalance' => f_amount($walletBal)
            ]);


        } catch (\Exception $e) {

            server_error_ajax($e);

        }
    }


    private function handlePost()
    {
        $action = inputPost('action');

        switch ($action) {
            case 'wd_submit':
                return $this->submitWithdrawalForm();
        }


        return ajax_404_response();
    }



    public function index()
    {
        if ($this->request->is('post'))
            return $this->handlePost();


        $title = 'Withdraw Now';
        $this->vd = $this->pageData($title, $title, $title);


        $balance = $this->walletModel->getWalletBalanceFromUserIdPk(user('id'), WalletService::WITHDRAW_FROM_WALLET);


        $this->vd['balance'] = &$balance;
        $this->vd['walletAddress'] = $this->userWalletAddress;
        $this->vd['bankAccount'] = $this->userBankAccount;


        return view('user_dashboard/withdrawal/withdraw_now', $this->vd);

    }
}