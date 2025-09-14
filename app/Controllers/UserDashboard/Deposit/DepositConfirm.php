<?php

namespace App\Controllers\UserDashboard\Deposit;



use App\Models\DepositModel;
use App\Services\UserService;
use App\Controllers\ParentController;



class DepositConfirm extends ParentController
{
    private DepositModel $depositModel;
    private object $mode;
    private int $modeId;
    private string $modeName;
    private string $amount;

    const DEPOSIT_SESSION_DATA_KEY = 'deposit_confirm_data';
    private array $vd = [];

    public function __construct()
    {
        $this->depositModel = new DepositModel;
    }



    private function postHandler()
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
            $user_id = user('user_id');

            $res = $this->depositModel->makeDeposit($user_id_pk, $this->mode, $this->amount, user_id: $user_id);

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);


            session()->remove(self::DEPOSIT_SESSION_DATA_KEY);

            session()->setFlashdata('notif', ['title' => 'Deposit Request Sent!', 'message' => 'Your deposit request has been sent.']);

            return resJson(['f_redirect' => route('user.deposit.logs')], 400);

        } catch (\Exception $e) {

            return server_error_ajax($e);

        }
    }


    private function checkSessionData()
    {
        $condition = (bool) (
            ($data = session()->get(self::DEPOSIT_SESSION_DATA_KEY))
            and isset($data->amount)
            and isset($data->user_id_pk)
            and isset($data->mode)
            and ($mode = $this->depositModel->getDepositModeFromName(name: $data->mode, columns: ['id', 'name', 'title', 'visibility', 'data']))
            and (isset($mode->visibility) and $mode->visibility)
            and ($modeData = json_decode($mode->data))
            and ($data->user_id_pk == user('id'))
        );

        if (!$condition) {
            session()->remove(self::DEPOSIT_SESSION_DATA_KEY);
            return false;
        }

        $this->mode = $mode;
        $this->mode->data = $modeData; // overwritting the data of $this->mode, which is string json
        $this->amount = $data->amount;
        return true;
    }

    public function index()
    {
        if (!$this->checkSessionData()) {
            return $this->request->isAJAX() ?
                resJson(['f_redirect' => route('user.deposit.deposit')], 400) :
                redirect()->to(route('user.deposit.deposit'));
        }



        if ($this->request->is('post'))
            return $this->postHandler();


        $title = 'Deposit Confirm';
        $this->vd = $this->pageData($title, $title);

        $this->vd['mode'] = $this->mode;
        $this->vd['amount'] = $this->amount;

        return view('user_dashboard/deposit/deposit_confirm', $this->vd);
    }
}
