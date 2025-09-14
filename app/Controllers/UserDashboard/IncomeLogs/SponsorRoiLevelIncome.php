<?php

namespace App\Controllers\UserDashboard\IncomeLogs;

use App\Controllers\ParentController;
use App\Enums\WalletTransactionCategory;
use App\Models\WalletTransactionModel;
use App\Libraries\MyLib;
use App\Models\RoiModel;
use Config\Services;

class SponsorRoiLevelIncome extends ParentController
{
    private RoiModel $roiModel;
    private array $vd = [];
    private int $pageLength = 15;
    private array $pageLengths = [15, 30, 50, 100];

    public function __construct()
    {
        $this->roiModel = new RoiModel;
    }



    private function setupRoiIncomeTable()
    {
        // ! Performing a little bit of manual pagination, because of using builder class not model.

        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen >= 1 and $plen <= 100) ? $plen : $this->pageLength;
        $pager = Services::pager();

        $user = user();

        $wtModel = new WalletTransactionModel;

        $query = $wtModel
            ->select()
            ->where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('category', WalletTransactionCategory::SPONSOR_ROI_LEVEL_INCOME)
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC');

        // track id search
        $track_id = inputGet('track_id');
        if ($track_id) {
            $query->like('track_id', $track_id);
            $this->vd['track_id'] = $track_id;
        }

        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 100) ? $plen : $this->pageLength;


        $this->vd['incomes'] = $query->paginate($this->pageLength);

        $this->vd['pager'] = $pager;
    }

    public function index()
    {
        $user_id_pk = user('id');

        $title = "ROI Level Incomes";
        $this->vd = $this->pageData($title, $title, $title);


        $this->setupRoiIncomeTable();

        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;

        return view('user_dashboard/income_logs/sponsor_roi_income_table', $this->vd);
    }

}