<?php

namespace App\Controllers\UserDashboard\WithdrawalModes;

use App\Models\WithdrawalModel;
use Razorpay\IFSC\IFSC;
use Razorpay\IFSC\Client;
use App\Services\UserService;
use App\Controllers\ParentController;

class Index extends ParentController
{
    private bool $bankLock, $walletLock;
    private array $vd = [];
    private WithdrawalModel $withdrawalModel;

    public function __construct()
    {
        $this->withdrawalModel = new WithdrawalModel;

        $this->bankLock = _setting('lock_bank', false);
        $this->walletLock = _setting('lock_wallet', false);
    }

    private function saveBankAccount()
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

            $bank = session()->get("selected_bank:$user_id_pk");

            if (!$bank or !is_object($bank) or isset($bank->locked) and $bank->locked) {

                session()->setFlashdata('notif', ['type' => 'danger', 'title' => 'Invalid Request!', 'message' => 'You made an invalid request.']);

                return resJson(['f_redirect' => route('user.home')], 400);
            }

            $res = $this->withdrawalModel->saveBankAccountDetails($user_id_pk, bank: $bank, lock: $this->bankLock);

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);


            if ($updateTimestamp = memory('bank_updated_at_timestamp')) {
                $updatedAt = f_date($updateTimestamp);
            }


            return resJson([
                'success' => true,
                'title' => 'Bank Account Saved!',
                'message' => 'Your bank account details has been saved successfully.',
                'lock' => $this->bankLock,
                'updated_message' => $updatedAt ? "Bank Details last updated on $updatedAt." : null
            ]);

        } catch (\Exception $e) {

            server_error_ajax($e);

        }
    }


    public function saveWallet()
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

            $res = $this->withdrawalModel->saveWalletDetails($user_id_pk, lock: $this->walletLock);
            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);

            $walletDetails = $this->withdrawalModel->getUserWalletDetailsFromUserIdPk($user_id_pk);

            return resJson([
                'status' => true,
                'title' => 'Wallet Updated!',
                'message' => 'Your wallet details has been updated!',
                'updated_form_view' => view(
                    'user_dashboard/withdrawal_modes/_wallet_address_form',
                    [
                        'wallet' => $walletDetails
                    ]
                )
            ]);

        } catch (\Exception $e) {

            server_error_ajax($e);

        }
    }

    private function searchIFSC()
    {
        try {

            $ifscCode = inputPost('ifsc_code');

            if (!$ifscCode or !is_string($ifscCode) or empty($ifscCode)) {
                $_err = ['error' => 'IFSC Code is required!'];
            }

            if (!isset($_err) and !IFSC::validate($ifscCode)) {
                $_err = [
                    'errorTitle' => 'Invalid IFSC Code',
                    'error' => 'The IFSC Code you submitted is either invalid or does not exists.'
                ];
            }

            if (isset($_err)) {
                return resJson(['success' => false, 'errors' => $_err], 400);
            }

            $user_id_pk = user('id');

            $_bank = (new Client)->lookupIFSC($ifscCode);

            $bank = new \stdClass;

            $bank->bank = escape($_bank->bank);
            $bank->code = escape($_bank->code);
            $bank->branch = escape($_bank->branch);

            // setting entire $bank to session is giving fatal error
            session()->set("selected_bank:$user_id_pk", $bank);

            return resJson([
                'success' => true,
                'view' => view('user_dashboard/withdrawal_modes/_bank_account_form', [
                    'bank' => &$bank
                ])
            ]);

        } catch (\Exception $e) {

            server_error_ajax($e);

        }
    }

    private function handlePost()
    {
        $action = inputPost('action');

        switch ($action) {

            case 'search_ifsc':
                return $this->searchIFSC();

            case 'bank_account':
                return $this->saveBankAccount();

            case 'wallet_address':
                return $this->saveWallet();

        }

        return ajax_404_response();
    }


    // wallet address page
    private function walletAddressPage()
    {
        if (!is_user_usd()) {
            return show_404();
        }

        $title = 'Wallet Address';
        $this->vd = $this->pageData($title, $title, $title);

        $user_id_pk = user('id');

        $walletDetails = $this->withdrawalModel->getUserWalletDetailsFromUserIdPk($user_id_pk);


        $this->vd['wallet'] = &$walletDetails;

        return view('user_dashboard/withdrawal_modes/wallet_address', $this->vd); // return string
    }

    // bank account page
    private function bankAccountPage()
    {
        if (is_user_usd()) {
            return show_404();
        }

        $title = 'Bank Account';
        $this->vd = $this->pageData($title, $title, $title);

        $user_id_pk = user('id');

        // getting user bank details
        $bankDetails = $this->withdrawalModel->getUserBankDetailsFromUserIdPk($user_id_pk);

        $bank = null;
        if ($bankDetails) {
            $bank = new \stdClass;
            $bank->code = $bankDetails->bank_ifsc;
            $bank->bank = $bankDetails->bank_name;
            $bank->branch = $bankDetails->bank_branch;

            $bank->locked = (bool) $bankDetails->locked;

            if (!$bank->locked)
                session()->set("selected_bank:$user_id_pk", $bank);

            $bank->accHolderName = escape($bankDetails->account_holder_name);
            $bank->accountNumber = escape($bankDetails->account_number);
            $bank->updated_at = f_date($bankDetails->updated_at);
        }


        $this->vd['bank'] = &$bank;

        return view('user_dashboard/withdrawal_modes/bank_account', $this->vd); // return string
    }


    public function index(string $mode)
    {

        // no need to validate the $mode in case of POST, because POST will do things on based of $actions
        if ($this->request->is('POST'))
            return $this->handlePost();


        switch ($mode) {

            // case 'bank-account':
            //     return $this->bankAccountPage();

            case 'wallet-address':
                return $this->walletAddressPage();

        }


        return $this->request->isAJAX() ? ajax_404_response() : show_404();
    }
}