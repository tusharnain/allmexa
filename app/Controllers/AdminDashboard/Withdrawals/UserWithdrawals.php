<?php

namespace App\Controllers\AdminDashboard\Withdrawals;

use Config\Services;
use App\Models\WithdrawalModel;
use App\Controllers\ParentController;

class UserWithdrawals extends ParentController
{
    private WithdrawalModel $withdrawalModel;
    private array $vd;
    private array $pageLengths = [15, 30, 50, 100, 200, 500];
    private int $pageLength = 15;
    private string $status = 'all';

    public function __construct()
    {
        $this->withdrawalModel = new WithdrawalModel;
    }


    private function setupTable()
    {
        $query = new WithdrawalModel;
        $pager = Services::pager();

        $t = 'withdrawals';

        $query->select("$t.*")
            ->select('users.user_id as user_user_id')
            ->select('users.full_name as user_full_name')
            ->select('bank_accounts.bank_name as bank_name')
            ->select('bank_accounts.bank_ifsc as bank_ifsc')
            ->select('bank_accounts.account_holder_name as account_holder_name')
            ->select('bank_accounts.account_number as account_number')
            ->join("users", "$t.user_id = users.id", 'left')
             ->join("bank_accounts", "$t.user_id = bank_accounts.user_id", 'left');


        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 500) ? $plen : $this->pageLength;


        // status
        $status = inputGet('status');
        if ($status and is_string($status) and (($status === 'all') or WithdrawalModel::isValidWithdrawalStatus($status))) {
            $this->status = $status;
        }

        if ($this->status !== 'all')
            $query->where("$t.status", $this->status);


        // track id search
        $search = inputGet('search');
        if ($search) {
            $query->groupStart()
                ->orLike("users.user_id", $search)
                ->orLike("users.full_name", $search)
                ->orLike("$t.track_id", $search)
                ->groupEnd();
            $this->vd['search'] = $search;
        }

        $query
            ->orderBy('status', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC');


        $this->vd['withdrawals'] = $query->paginate($this->pageLength);
        $this->vd['pager'] = $pager;

    }



    public function index()
    {
        $userLabel = label('user');

        $title = "$userLabel Withdrawals";
        $this->vd = $this->pageData($title, $title, $title);


        $this->setupTable();


        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;
        $this->vd['status'] = $this->status;




        return view('admin_dashboard/withdrawals/user_withdrawals', $this->vd);
    }

}
