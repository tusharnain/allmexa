<?php

namespace App\Controllers\AdminDashboard\Users;


use App\Controllers\ParentController;
use App\Models\UserModel;

class UsersList extends ParentController
{
    private array $vd = [];
    private string $type;
    private array $pageLengths = [15, 30, 50, 100, 200];
    private int $pageLength = 15;
    protected UserModel $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
    }



    private function validateType(string &$type): array
    {
        if (!in_array($type, ['all', 'active', 'inactive'])) {
            show_404();
            return [];
        }

        $this->type = $type;

        $title = ucfirst($this->type) . ' ' . label('users');

        $this->vd['type'] = $type;
        return $this->pageData($title, $title, $title);
    }

    private function setTableData()
    {
        $pager = \Config\Services::pager();
        $t = 'users';
        $st = 'user_income_stats';
        $wt = 'wallets';

        $query = $this->userModel
            ->select([
                "$t.*",
                "COALESCE($wt.investment, 0) as total_investment",
                "COALESCE($st.total_earning, 0) as total_earning",
                "COALESCE($st.total_pending_withdrawal, 0) as total_pending_withdrawal",
                "COALESCE($st.total_complete_withdrawal, 0) as total_complete_withdrawal",
                "COALESCE($wt.fund, 0) as fund_wallet",
                "COALESCE($wt.income, 0) as income_wallet",
            ])
            ->select('sponsors.user_id as sponsor_id')
            ->select('sponsors.full_name as sponsor_full_name')
            ->join('users sponsors', "$t.sponsor_id = sponsors.id", 'left')
            ->join($st, "$st.user_id = $t.id", 'left')
            ->join($wt, "$wt.user_id = $t.id", 'left');

        //type
        if ($this->type === 'active')
            $query->where("$t.status", 1);
        else if ($this->type === 'inactive')
            $query->where("$t.status", 0);




        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 200) ? $plen : $this->pageLength;

        //search
        $search = inputGet('search');

        if ($search) {
            $query->groupStart()
                ->orLike("$t.user_id", $search)
                ->orLike("$t.full_name", $search)
                ->orLike("$t.email", $search)
                ->orLike("$t.phone", $search)
                ->groupEnd();

            $this->vd['search'] = $search;
        }


        // sorting
        $query->orderBy('created_at', 'DESC')->orderBy("$t.id", 'DESC');

        $this->vd['users'] = $query->paginate($this->pageLength);




        $this->vd['pager'] = $pager;
    }


    public function index(string $type): string
    {
        $this->vd = $this->validateType($type);

        $this->setTableData();


        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;

        return view('admin_dashboard/users/users_list', $this->vd);
    }
}
