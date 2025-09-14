<?php

namespace App\Controllers\UserDashboard\Topup;

use App\Models\TopupModel;
use App\Twebsol\Plans;
use App\Models\WalletModel;
use App\Services\UserService;
use App\Controllers\ParentController;

class Compound extends ParentController
{
    private array $vd = [];
    private TopupModel $topupModel;
    private WalletModel $walletModel;
    public function __construct()
    {
        $this->walletModel = new WalletModel;
        $this->topupModel = new TopupModel;
    }

    private function submitTopup()
    {
        try {

            // Tpin Verification // if errorArray is true, then validate error with is_array, else with is_string
            if (
                $tpinValidation = UserService::validateRequestTpin(errorArray: true)
                and is_array($tpinValidation)
            ) {
                return resJson($tpinValidation, 400);
            }


            $userIdPk = user('id');

            $res = $this->topupModel->topupUser(topup_by_user_id_pk: $userIdPk);

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);

            $walletBalance = $this->walletModel->getWalletBalanceFromUserIdPk(user_id_pk: $userIdPk, wallet_field: 'fund');

            return resJson([
                'success' => true,
                'title' => 'Topup Successful!',
                'message' => 'Topup has been done!',
                'walletBalance' => &$walletBalance,
                'fWalletBalance' => f_amount($walletBalance, isUser: true)
            ]);

        } catch (\Exception $e) {
            return server_error_ajax($e);
        }

    }


    public function handlePost()
    {
        $action = inputPost('action');

        switch ($action) {

            case 'submit_topup':
                return $this->submitTopup();
        }

        return ajax_404_response();
    }

    public function index()
    {

        if ($this->request->is('post'))
            return $this->handlePost();

        $title = 'Compound Invest';
        $this->vd = $this->pageData($title, $title, $title);

        $walletBalance = $this->walletModel->getWalletBalanceFromUserIdPk(user('id'), 'fund');

        $this->vd['walletBalance'] = &$walletBalance;

        return view('user_dashboard/topup/compound', $this->vd); // return string
    }
}
