<?php

namespace App\Controllers\UserDashboard\Wallet;

use App\Twebsol\Plans;
use Config\Services;
use App\Models\WalletModel;
use App\Services\WalletService;
use App\Controllers\ParentController;
use App\Models\WalletTransactionModel;

class WalletTransactions extends ParentController
{
    private string $wallet;
    private array $vd = [];
    private int $pageLength = 15;
    private array $pageLengths = [15, 30, 50, 100];
    private WalletModel $walletModel;
    public function __construct()
    {
        $this->walletModel = new WalletModel;
    }


    private function trackTopup()
    {
        $topupModel = new \App\Models\TopupModel;

        if (
            !($topupIdPk = inputPost('data_id'))
            or !is_numeric($topupIdPk)
            or !($topup = $topupModel->getTopupFromTopupIdPk($topupIdPk))
            or ($topup->topup_by !== user('id'))
        ) {
            return ajax_404_response();
        }

        $view = view('user_dashboard/wallet/_track_topup', ['topup' => &$topup]);

        return resJson(['success' => true, 'html' => &$view]);
    }

    private function trackSli() // Track Sponsor Level Income
    {
        $userIncomeModel = new \App\Models\UserIncomeModel;

        if (
            !($sli_id = inputPost('data_id'))
            or !is_numeric($sli_id)
            or !($sli = $userIncomeModel->getSponsorLevelIncomeRecord(sli_id_pk: $sli_id))
            or ($sli->user_id !== user('id'))
        ) {
            return ajax_404_response();
        }

        $view = view('user_dashboard/wallet/_track_sli', ['sli' => &$sli]);

        return resJson(['success' => true, 'html' => &$view]);
    }

    private function trackSrli() // Track Sponsor ROI Level Income
    {
        $userIncomeModel = new \App\Models\UserIncomeModel;

        if (
            !($srli_id = inputPost('data_id'))
            or !is_numeric($srli_id)
            or !($srli = $userIncomeModel->getSponsorRoiLevelIncomeRecord(srli_id_pk: $srli_id))
            or ($srli->user_id !== user('id'))
        ) {
            return ajax_404_response();
        }

        $view = view('user_dashboard/wallet/_track_srli', ['srli' => &$srli]);

        return resJson(['success' => true, 'html' => &$view]);
    }

    private function trackWithdrawal()
    {
        $withdrawalModel = new \App\Models\WithdrawalModel;

        if (
            !($wd_id = inputPost('data_id'))
            or !is_numeric($wd_id)
            or !($wd = $withdrawalModel->getWithdrawalFromWithdrawalIdPk($wd_id, ['track_id', 'user_id', 'amount', 'charges', 'net_amount', 'status', 'created_at']))
            or ($wd->user_id !== user('id'))
        ) {
            return ajax_404_response();
        }

        $view = view('user_dashboard/wallet/_track_wd', ['withdrawal' => &$wd]);

        return resJson(['success' => true, 'html' => &$view]);
    }

    private function handlePost()
    {
        try {

            $action = inputPost('action');

            switch ($action) {
                case 'track_topup':
                    return $this->trackTopup();

                case 'track_sli':
                    return $this->trackSli();

                case 'track_srli':
                    return $this->trackSrli();

                case 'track_wd':
                    return $this->trackWithdrawal();
            }

            return ajax_404_response();

        } catch (\Exception $e) {

            server_error_ajax($e);
        }
    }

    private function setupTable()
    {
        $query = new WalletTransactionModel;

        $pager = Services::pager();

        $query->where('user_id', user('id'))
            ->where('wallet', $this->wallet);


        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 100) ? $plen : $this->pageLength;


        // track id search
        $track_id = inputGet('track_id');
        if ($track_id) {
            $query->like('track_id', $track_id);
            $this->vd['track_id'] = $track_id;
        }

        // wallet transaction category
        $txnCategory = inputGet('txn_category');
        
        if($txnCategory) {
            $query->where('category', $txnCategory);
        }
        

        $query->orderBy('id', 'DESC');


        $this->vd['transactions'] = $query->paginate($this->pageLength);
        $this->vd['pager'] = $pager;
    }


    private function validateWallet(string &$walletSlug)
    {
        if (
            $wallet = WalletService::searchBySlug($walletSlug) and
            (!in_array($wallet, ['lp', 'ip']))
        ) {
            $this->wallet = $wallet;
        } else
            return show_404();
    }


    public function index(string $walletSlug)
    {
        if ($this->request->is('post'))
            return $this->handlePost();

        $this->validateWallet($walletSlug);


        $title = wallet_label($this->wallet);
        $this->vd = $this->pageData($title, $title, $title);

        $walletBalance = $this->walletModel->getWalletBalanceFromUserIdPk(user('id'), $this->wallet);

        $this->vd['walletBalance'] = $walletBalance;
        $this->vd['wallet'] = $this->wallet;

        // setup table
        $this->setupTable();



        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;


        return view('user_dashboard/wallet/wallet_transactions', $this->vd); // return string
    }

}
