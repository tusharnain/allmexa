<?php

namespace App\Controllers\AdminDashboard\Wallets;

use App\Enums\WalletTransactionCategory as TxnCat;
use Config\Services;
use App\Controllers\ParentController;
use App\Models\WalletTransactionModel;

class AdminHistory extends ParentController
{

    private array $vd;

    private array $pageLengths = [15, 30, 50, 100];
    private int $pageLength = 15;

    private function setupTable()
    {
        $query = new WalletTransactionModel();
        $pager = Services::pager();

        $t = 'wallet_transactions';

        $query->select("$t.*")
            ->select('users.user_id as user_user_id')
            ->select('users.full_name as user_full_name')
            ->join("users", "$t.user_id = users.id", 'left')
            ->where('category', TxnCat::ADMIN);


        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 100) ? $plen : $this->pageLength;


        // wallet validation
        $wallet = inputGet('wallet') ?? '';
        if ($wallet !== '') {
            $query->where('wallet', $wallet);
            $this->vd['wallet'] = $wallet;
        }

        // transaction type validation
        $type = inputGet('type') ?? '';
        if ($type !== '') {
            $query->where('type', $type);
            $this->vd['type'] = $type;
        }


        // from amount validation
        $fromAmount = inputGet('from_amount', null_if_empty: true);
        if ($fromAmount and is_numeric($fromAmount)) {
            $query->where('amount >=', $fromAmount);
            $this->vd['fromAmount'] = $fromAmount;
        }
        // to amount validation
        $toAmount = inputGet('to_amount', null_if_empty: true);
        if ($toAmount and is_numeric($toAmount)) {
            $query->where('amount <=', $toAmount);
            $this->vd['toAmount'] = $toAmount;
        }


        // from created_at validation
        $fromDate = inputGet('from_date', null_if_empty: true);
        if ($fromDate) {
            $query->where("$t.created_at >=", "$fromDate 00:00:00");
            $this->vd['fromDate'] = $fromDate;
        }
        // to created_at validation
        $toDate = inputGet('to_date', null_if_empty: true);
        if ($toDate) {
            $query->where("$t.created_at <=", "$toDate 23:59:59");
            $this->vd['toDate'] = $toDate;
        }


        // search validation
        $search = inputGet('search');
        if ($search) {
            $query->groupStart()
                ->orLike("users.user_id", $search)
                ->orLike("users.full_name", $search)
                ->orLike("track_id", $search)
                ->groupEnd();
            $this->vd['search'] = $search;
        }


        $query
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC');


        $this->vd['transactions'] = $query->paginate($this->pageLength);
        $this->vd['pager'] = $pager;
    }


    public function index()
    {
        $title = 'Admin Transaction History';
        $this->vd = $this->pageData($title, $title, $title);


        $this->setupTable();


        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;


        return view('admin_dashboard/wallets/admin_history', $this->vd);
    }

}
