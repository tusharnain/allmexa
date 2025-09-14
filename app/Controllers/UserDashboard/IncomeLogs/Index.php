<?php

namespace App\Controllers\UserDashboard\IncomeLogs;

use App\Enums\WalletTransactionCategory as TxnCat;
use Config\Services;
use App\Models\WalletModel;
use App\Controllers\ParentController;
use App\Models\WalletTransactionModel;

class Index extends ParentController
{
    private array $vd = [];
    private int $pageLength = 15;
    private array $pageLengths = [15, 30, 50, 100];

    private function setupTable(string $category)
    {
        $query = new WalletTransactionModel;

        $pager = Services::pager();

        $query->where('user_id', user('id'))
            ->where('type', 'credit')
            ->where('category', $category);


        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 100) ? $plen : $this->pageLength;


        // track id search
        $track_id = inputGet('track_id');

        if ($track_id) {
            $query->like('track_id', $track_id);
            $this->vd['track_id'] = $track_id;
        }



        $query->orderBy('created_at', 'DESC')->orderBy('id', 'DESC');


        $this->vd['transactions'] = $query->paginate($this->pageLength);
        $this->vd['pager'] = $pager;
    }



    public function index($category)
    {

        if (!in_array($category, TxnCat::getArray())) {
            return show_404();
        }

        $title = match ($category) {
            TxnCat::ROI => 'Daily Return',
            TxnCat::SPONSOR_LEVEL_INCOME => 'Direct Income',
            TxnCat::SPONSOR_ROI_LEVEL_INCOME => 'Level Income',
            TxnCat::SALARY => 'Salary Reward',
            default => show_404()
        };


        $this->vd = $this->pageData($title, $title, $title);

        // setup table
        $this->setupTable($category);


        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;

        return view('user_dashboard/income_logs/index', $this->vd); // return string
    }

}
