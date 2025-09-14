<?php

namespace App\Controllers\AdminDashboard\Deposits;


use App\Models\DepositModel;
use Razorpay\IFSC\Client;
use App\Controllers\ParentController;

class DepositModes extends ParentController
{
    private DepositModel $depositModel;
    private array $vd;

    public function __construct()
    {
        $this->depositModel = new DepositModel;
    }

    function saveBankAccount()
    {
        $inputs = [
            'account_number' => inputPost('account_number'),
            'ifsc' => inputPost('ifsc'),
            'receipt_upload' => inputPost('bank_receipt_upload') ? 1 : 0,
            'allow_remarks' => inputPost('bank_allow_remarks') ? 1 : 0,
            'visibility' => inputPost('bank_visibility') ? 1 : 0
        ];
        if (
            $validationErrors = validate($inputs, [
                'account_number' => ['required', 'numeric', 'min_length[9]', 'max_length[25]'],
                'ifsc' => ['required', 'alpha_numeric', 'exact_length[11]', 'ifsc'],
            ])
        ) {
            return resJson(['errors' => ['validationErrors' => $validationErrors]], 400);
        }

        $ifscCode = $inputs['ifsc'];

        $client = new Client;
        $bank = $client->lookupIFSC($ifscCode);
        $visibility = $inputs['visibility'];
        unset($inputs['visibility']);
        $data = [
            ...$inputs,
            'bank_name' => $bank->bank,
            'bank_branch' => $bank->branch,
        ];
        $this->depositModel->saveDepositMode(name: 'bank_account', title: 'Bank Account', visibility: $visibility, data: $data);
        return resJson(['success' => true, 'message' => 'Bank Account details updated!']);
    }

    function saveUsdtTrc20()
    {
        $inputs = [
            'wallet_address' => inputPost('usdt_trc20_wallet_address'),
            'allow_remarks' => inputPost('usdt_trc20_allow_remarks') ? 1 : 0,
            'visibility' => inputPost('usdt_trc20_visibility') ? 1 : 0
        ];
        if (
            $validationErrors = validate($inputs, [
                'wallet_address' => ['required', 'string', 'alpha_numeric', 'min_length[20]', 'max_length[50]']
            ])
        ) {
            return resJson(['errors' => ['validationErrors' => $validationErrors]], 400);
        }

        $visibility = $inputs['visibility'];
        unset($inputs['visibility']);
        $data = $inputs;

        $this->depositModel->saveDepositMode(name: 'usdt_trc20', title: 'USDT (TRC20)', visibility: $visibility, data: $data);

        return resJson(['success' => true, 'message' => 'USDT(TRC20) details updated!']);
    }

    function saveUsdtBep20()
    {
        $inputs = [
            'wallet_address' => inputPost('usdt_bep20_wallet_address'),
            'allow_remarks' => inputPost('usdt_bep20_allow_remarks') ? 1 : 0,
            'visibility' => inputPost('usdt_bep20_visibility') ? 1 : 0
        ];
        if (
            $validationErrors = validate($inputs, [
                'wallet_address' => ['required', 'string', 'alpha_numeric', 'min_length[20]', 'max_length[50]']
            ])
        ) {
            return resJson(['errors' => ['validationErrors' => $validationErrors]], 400);
        }
        $visibility = $inputs['visibility'];
        unset($inputs['visibility']);
        $data = $inputs;

        $this->depositModel->saveDepositMode(name: 'usdt_bep20', title: 'USDT (BEP20)', visibility: $visibility, data: $data);

        return resJson(['success' => true, 'message' => 'USDT(BEP20) details updated!']);
    }

    function saveUpi()
    {
        $inputs = [
            'address' => inputPost('upi_address'),
            'receipt_upload' => inputPost('upi_receipt_upload') ? 1 : 0,
            'allow_remarks' => inputPost('upi_allow_remarks') ? 1 : 0,
            'visibility' => inputPost('upi_visibility') ? 1 : 0
        ];

        if (
            $validationErrors = validate($inputs, [
                'address' => ['required', 'string', 'regex_match[[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}]']
            ], [
                'upi_address' => ['regex_match' => 'Invalid UPI Address']
            ])
        ) {
            return resJson(['errors' => ['validationErrors' => $validationErrors]], 400);
        }
        $visibility = $inputs['visibility'];
        unset($inputs['visibility']);
        $data = $inputs;

        $this->depositModel->saveDepositMode(name: 'upi_address', title: 'UPI Address', visibility: $visibility, data: $data);

        return resJson(['success' => true, 'message' => 'UPI Address details updated!']);
    }

    function saveMobileNumber()
    {
        $inputs = [
            'number' => inputPost('mobile_number'),
            'receipt_upload' => inputPost('mobile_receipt_upload') ? 1 : 0,
            'allow_remarks' => inputPost('mobile_allow_remarks') ? 1 : 0,
            'visibility' => inputPost('mobile_visibility') ? 1 : 0
        ];

        if (
            $validationErrors = validate($inputs, [
                'number' => ['required', 'string', 'min_length[10]', 'max_length[14]']
            ])
        ) {
            return resJson(['errors' => ['validationErrors' => $validationErrors]], 400);
        }
        $visibility = $inputs['visibility'];
        unset($inputs['visibility']);
        $data = $inputs;

        $this->depositModel->saveDepositMode(name: 'mobile_number', title: 'Mobile Number', visibility: $visibility, data: $data);

        return resJson(['success' => true, 'message' => 'Mobile Number details updated!']);
    }


    private function handlePost()
    {
        try {

            session()->close();

            $action = inputPost('action');

            switch ($action) {

                case 'bank_account':
                    return $this->saveBankAccount();

                case 'usdt_trc20':
                    return $this->saveUsdtTrc20();

                case 'usdt_bep20':
                    return $this->saveUsdtBep20();

                case 'upi':
                    return $this->saveUpi();

                case 'mobile_number':
                    return $this->saveMobileNumber();

            }

            return ajax_404_response();


        } catch (\Exception $e) {

            server_error_ajax($e);

        } finally {

            session()->start();

        }
    }



    public function index()
    {
        if ($this->request->is('POST'))
            return $this->handlePost();


        $title = 'Deposit Modes';
        $this->vd = $this->pageData($title, $title, $title);



        // fetching all modes
        $depositModes = $this->depositModel->getAllDepositModes();
        foreach ($depositModes as $mode) {
            $mode->data = json_decode($mode->data);
            $this->vd[$mode->name] = $mode;
        }


        return view('admin_dashboard/deposits/deposit_modes', $this->vd);
    }

}
