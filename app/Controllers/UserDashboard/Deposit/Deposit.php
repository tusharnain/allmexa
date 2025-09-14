<?php

namespace App\Controllers\UserDashboard\Deposit;


use App\Controllers\ParentController;
use App\Models\DepositModel;


class Deposit extends ParentController
{
    const DEPOSIT_SESSION_DATA_KEY = 'deposit_confirm_data';
    private DepositModel $depositModel;
    private array $vd = [];

    public function __construct()
    {
        $this->depositModel = new DepositModel;
    }


    private function handlePost()
    {
        $amount = inputPost('amount');
        $mode = inputPost('mode');

        $depositAmountRange = _setting('deposit_amount_range', [100, 100000]);
        $depositAmountRange[0] = intval(_c($depositAmountRange[0]));
        $depositAmountRange[1] = intval(_c($depositAmountRange[1]));



        $condition = (bool) ($amount
            and $mode
            and is_numeric($amount)
            and is_string($mode)
            and ($modeObject = $this->depositModel->getDepositModeFromName(name: $mode))
            and (isset($modeObject->visibility) and $modeObject->visibility)
            and isset($modeObject->data)
        );

        if (!$condition)
            return show_404();

        // amount validation
        if (($amount < $depositAmountRange[0]) or ($amount > $depositAmountRange[1])) {
            return redirect()->to(route('user.home'))->with('notif', $this->userInvalidDataNotificationArray());
        }

        $sessionData = new \stdClass;
        $sessionData->user_id_pk = user('id');
        $sessionData->amount = $amount;
        $sessionData->mode = $mode;

        session()->set(self::DEPOSIT_SESSION_DATA_KEY, $sessionData);

        return redirect()->to(route('user.deposit.confirm'));
    }


    public function index()
    {
        if ($this->request->is('post'))
            return $this->handlePost();

        $title = 'Deposit';
        $this->vd = $this->pageData($title, $title, $title);

        // deposit modes
        $depositModes = $this->depositModel->depositModesTable()
            ->select(['name', 'title', 'data'])
            ->where('visibility', 1)
            ->whereIn('name', is_user_usd() ? ['usdt_trc20', 'usdt_bep20'] : ['upi_address', 'bank_account'])
            ->get()->getResultObject();

        foreach ($depositModes as &$mode) {
            $mode->data = json_decode($mode->data);
        }

        $this->vd['depositModes'] = &$depositModes;

        return view('user_dashboard/deposit/deposit', $this->vd);
    }
}
