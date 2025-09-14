<?php

namespace App\Controllers\UserDashboard\IncomeLogs;

use App\Controllers\ParentController;
use App\Libraries\MyLib;
use App\Models\UserIncomeModel;

class SponsorLevelIncome extends ParentController
{
    private UserIncomeModel $userIncomeModel;
    private array $vd = [];
    private int $pageLength = 15;
    private array $pageLengths = [15, 30, 50, 100];

    public function __construct()
    {
        $this->userIncomeModel = new UserIncomeModel;
    }

    private function setupTable()
    {
        // ! Performing a little bit of manual pagination, because of using builder class not model.

        $user_id_pk = user('id');

        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen >= 1 and $plen <= 100) ? $plen : $this->pageLength;

        $t = 'sponsor_level_incomes';


        $builder = $this->userIncomeModel->sponsorLevelIncomeTable()
            ->select([
                "$t.*",
                'users.user_id as level_user_user_id',
                'users.full_name as level_user_full_name',
                'wallet_transactions.track_id as transaction_track_id'
            ])
            ->join('users', "$t.level_user_id = users.id", 'left')
            ->join('wallet_transactions', "$t.transaction_id_pk = wallet_transactions.id", 'left')
            ->where("$t.user_id", $user_id_pk)
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC');

        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 100) ? $plen : $this->pageLength;


        // transaction track id search
        $track_id = inputGet('track_id');
        if ($track_id) {
            $builder->like('wallet_transactions.track_id', $track_id);
            $this->vd['track_id'] = $track_id;
        }

        $pagination = MyLib::builderPagination(builder: $builder, per_page: $this->pageLength, pager_tempalte: 'user_dashboard');

        $this->vd['logs'] = $pagination->data;
        $this->vd['pager'] = $pagination->pager;
    }

    public function index()
    {
        $title = 'Affiliate Profits';
        $this->vd = $this->pageData($title, $title, $title);

        $this->setupTable();

        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;

        return view('user_dashboard/income_logs/sponsor_level_income', $this->vd);
    }
}