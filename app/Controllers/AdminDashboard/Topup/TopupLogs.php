<?php

namespace App\Controllers\AdminDashboard\Topup;

use App\Controllers\ParentController;
use App\Models\TopupModel;
use Config\Services;

class TopupLogs extends ParentController
{
    private array $vd = [];
    private TopupModel $topupModel;
    private array $pageLengths = [15, 30, 50, 100];
    private int $pageLength = 15;


    private function setupTable()
    {
        $query = new TopupModel;
        $pager = Services::pager();

        $t = 'topups';

        $query->select("$t.*")
            ->select('users.user_id as user_user_id')
            ->select('users.full_name as user_full_name')
            ->select('topup_by_user.user_id as topup_by_user_user_id')
            ->select('topup_by_user.full_name as topup_by_user_full_name')
            ->join("users", "$t.user_id = users.id", 'left')
            ->join("users as topup_by_user", "$t.topup_by = topup_by_user.id", 'left');


        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 100) ? $plen : $this->pageLength;


        // track id search
        $search = inputGet('search');
        if ($search) {
            $query->groupStart()
                ->orLike("$t.track_id", $search)
                ->orLike("$t.amount", $search)
                ->orLike("users.user_id", $search)
                ->orLike("users.full_name", $search)
                ->orLike("topup_by_user.user_id", $search)
                ->orLike("topup_by_user.full_name", $search)
                ->groupEnd();
            $this->vd['search'] = $search;
        }

        // plan filter
        $plan = inputGet('plan');
        if ($plan and is_string($plan)) {
            $query->where('plan', $plan);
            $this->vd['plan'] = $plan;
        }

        // topup type filter
        $topupType = inputGet('topup_type');
        if ($topupType and is_string($topupType)) {
            $query->where('topup_type', $topupType);
            $this->vd['topupType'] = $topupType;
        }


        // by admin filter
        $byAdmin = inputGet('by_admin');
        if ($byAdmin) {
            $query->where('topup_by', null);
            $this->vd['byAdmin'] = $byAdmin;
        }

        $query
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC');


        $this->vd['topups'] = $query->paginate($this->pageLength);
        $this->vd['pager'] = $pager;
    }



    public function index()
    {
        $title = "Topup Logs";
        $this->vd = $this->pageData($title, $title, $title);

        $this->setupTable();

        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;

        return view('admin_dashboard/topup/topup_logs', $this->vd);
    }

}