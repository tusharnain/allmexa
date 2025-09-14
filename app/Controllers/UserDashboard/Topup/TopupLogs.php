<?php

namespace App\Controllers\UserDashboard\Topup;

use Config\Services;
use App\Models\TopupModel;
use App\Controllers\ParentController;

class TopupLogs extends ParentController
{
    private array $vd = [];
    private TopupModel $topupModel;
    private int $pageLength = 15;
    private array $pageLengths = [15, 30, 50, 100];

    public function __construct()
    {
        $this->topupModel = new TopupModel;
    }
    private function setupTable()
    {
        $query = new TopupModel;
        $t = 'topups';
        $pager = Services::pager();

        $query
            ->select("$t.*")
            ->select([
                'users.user_id as userId',
                'users.full_name as userFullName'
            ])
            ->join('users', "$t.user_id = users.id")
            ->where("$t.topup_by", user('id'));


        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 100) ? $plen : $this->pageLength;


        // track id search
        $search = inputGet('search');
        if ($search) {
            $query->groupStart()
                ->orLike("$t.track_id", $search)
                ->orLike("users.user_id", $search)
                ->orLike("users.full_name", $search)
                ->orLike("$t.amount", $search)
                ->groupEnd();
            $this->vd['search'] = $search;
        }



        $query->orderBy('created_at', 'DESC')->orderBy('id', 'DESC');


        $this->vd['topups'] = $query->paginate($this->pageLength);
        $this->vd['pager'] = $pager;
    }

    public function index(): string
    {
        $title = 'Topup Logs';
        $this->vd = $this->pageData($title, $title, $title);

        $this->setupTable();


        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;

        return view('user_dashboard/topup/topup_logs', $this->vd); // return string
    }

}
