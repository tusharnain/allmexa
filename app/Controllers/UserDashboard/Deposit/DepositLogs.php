<?php

namespace App\Controllers\UserDashboard\Deposit;

use Config\Services;
use App\Models\DepositModel;
use App\Controllers\ParentController;



class DepositLogs extends ParentController
{
    private DepositModel $depositModel;
    private array $vd = [];
    private int $pageLength = 15;
    private array $pageLengths = [15, 30, 50, 100];
    private string $status = 'all';

    public function __construct()
    {
        $this->depositModel = new DepositModel;
    }


    private function handlePost()
    {
        $action = inputPost('action');

        switch ($action) {

            case 'get_user_deposit':
                return $this->getUserDeposit();

        }

        return ajax_404_response();
    }


    private function getUserDeposit()
    {
        $deposit_id_pk = inputPost('deposit_id');
        $user_id_pk = user('id');

        if (
            !$deposit_id_pk or
            !is_numeric($deposit_id_pk) or
            !($deposit = $this->depositModel->getDepositFromDepositIdPkAndUserIdPk($deposit_id_pk, $user_id_pk))
        ) {
            return ajax_404_response();
        }

        $view = view('user_dashboard/deposit/__deposit_modal_content', [
            'deposit' => &$deposit,
            'deposit_model' => &$this->depositModel
        ]);

        return resJson(['success' => true, 'view' => &$view, 'track_id' => $deposit->track_id]);
    }

    private function setupTable()
    {
        $query = new DepositModel;
        $pager = Services::pager();

        $user_id_pk = user('id');

        $query->select(['id', 'track_id', 'deposit_mode_id', 'amount', 'utr', 'status', 'created_at', 'admin_resolution_at'])
            ->where('user_id', $user_id_pk);


        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 100) ? $plen : $this->pageLength;



        // status
        $status = inputGet('status');
        if ($status and is_string($status) and (($status === 'all') or DepositModel::isValidDepositStatus($status))) {
            $this->status = $status;
        }

        if ($this->status !== 'all')
            $query->where("status", $this->status);



        // track id search
        $search = inputGet('search');
        if ($search) {
            $query->groupStart()
                ->orLike("track_id", $search)
                ->orLike("utr", $search)
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
        if ($this->request->is('post'))
            return $this->handlePost();


        $title = 'Deposit Logs';
        $this->vd = $this->pageData($title, $title);

        $user_id_pk = user('id');

        $totalPendingDepositSum = $this->depositModel->getTotalDepositSumFromUserIdPk($user_id_pk, DepositModel::DEPOSIT_STATUS_PENDING);
        $totalCompleteDepositSum = $this->depositModel->getTotalDepositSumFromUserIdPk($user_id_pk, DepositModel::DEPOSIT_STATUS_COMPLETE);


        $this->setupTable();


        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;

        $this->vd['totalPendingDepositSum'] = $totalPendingDepositSum;
        $this->vd['totalCompleteDepositSum'] = $totalCompleteDepositSum;

        $this->vd['status'] = $this->status;

        //model
        $this->vd['deposit_model'] = $this->depositModel;


        return view('user_dashboard/deposit/deposit_logs', $this->vd);
    }
}
