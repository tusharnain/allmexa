<?php

namespace App\Controllers\UserDashboard\Withdrawal;

use Config\Services;
use App\Models\WithdrawalModel;
use App\Controllers\ParentController;

class WithdrawalLogs extends ParentController
{
    private array $vd = [];
    private WithdrawalModel $withdrawalModel;
    private int $pageLength = 15;
    private array $pageLengths = [15, 30, 50, 100];

    public function __construct()
    {
        $this->withdrawalModel = new WithdrawalModel;

    }

    private function handlePost()
    {
        $action = inputPost('action');

        switch ($action) {

            case 'get_user_withdrawal':
                return $this->getUserWithdrawal();

        }

        return ajax_404_response();
    }

    private function getUserWithdrawal()
    {
        $withdrawal_id_pk = inputPost('withdrawal_id');
        $user_id_pk = user('id');

        if (
            !$withdrawal_id_pk or
            !is_numeric($withdrawal_id_pk) or
            !($withdrawal = $this->withdrawalModel->getWithdrawalFromWithdrawalIdPkAndUserIdPk($withdrawal_id_pk, $user_id_pk))
        ) {
            return ajax_404_response();
        }

        $view = view('user_dashboard/withdrawal/__withdrawal_modal_content', [
            'withdrawal' => &$withdrawal
        ]);

        return resJson(['success' => true, 'view' => &$view, 'track_id' => $withdrawal->track_id]);
    }


    private function setupTable()
    {
        $query = new WithdrawalModel;
        $pager = Services::pager();

        $user_id_pk = user('id');

        $query
            ->select(['id', 'track_id', 'user_id', 'amount', 'charges', 'net_amount', 'status', 'created_at'])
            ->where("user_id", $user_id_pk);


        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 100) ? $plen : $this->pageLength;


        // track id search
        $search = inputGet('search');
        if ($search) {
            $query->like('track_id', $search);
            $this->vd['search'] = $search;
        }

        $query->orderBy('created_at', 'DESC')->orderBy('id', 'DESC');

        $this->vd['wds'] = $query->paginate($this->pageLength);
        $this->vd['pager'] = $pager;
    }

    public function index()
    {
        if ($this->request->is('post'))
            return $this->handlePost();

        $title = 'Payouts Logs';
        $this->vd = $this->pageData($title, $title, $title);

        $this->setupTable();

        return view('user_dashboard/withdrawal/withdrawal_logs', $this->vd);

    }
}