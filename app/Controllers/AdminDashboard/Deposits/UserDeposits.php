<?php

namespace App\Controllers\AdminDashboard\Deposits;

use Config\Services;
use App\Models\DepositModel;
use App\Controllers\ParentController;

class UserDeposits extends ParentController
{
    private DepositModel $depositModel;
    private array $vd;
    private array $pageLengths = [15, 30, 50, 100, 200];
    private int $pageLength = 15;
    private string $status = 'all';

    public function __construct()
    {
        $this->depositModel = new DepositModel;
    }


    private function setupTable()
    {
        $query = new DepositModel;
        $pager = Services::pager();

        $t = 'deposits';

        $query->select("$t.*")
            ->select('users.user_id as user_user_id')
            ->select('users.full_name as user_full_name')
            ->join("users", "$t.user_id = users.id", 'left');


        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 100) ? $plen : $this->pageLength;


        // status
        $status = inputGet('status');
        if ($status and is_string($status) and (($status === 'all') or DepositModel::isValidDepositStatus($status))) {
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
                ->orLike("$t.utr", $search)
                ->groupEnd();
            $this->vd['search'] = $search;
        }

        $query
            ->orderBy('status', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC');


        $this->vd['deposits'] = $query->paginate($this->pageLength);
        $this->vd['pager'] = $pager;
    }



    public function index()
    {
        $userLabel = label('user');

        $title = "$userLabel Deposits";
        $this->vd = $this->pageData($title, $title, $title);


        $this->setupTable();


        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;
        $this->vd['status'] = $this->status;

        //model
        $this->vd['deposit_model'] = $this->depositModel;


        return view('admin_dashboard/deposits/user_deposits', $this->vd);
    }

}
