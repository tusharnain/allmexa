<?php

namespace App\Controllers\AdminDashboard\Users;


use App\Controllers\ParentController;
use App\Models\UserKycModel;

class UserKyc extends ParentController
{
    private array $vd = [];
    private array $pageLengths = [15, 30, 50, 100, 200];
    private int $pageLength = 15;
    private string $status = 'all';
    protected UserKycModel $userKycModel;
    public function __construct()
    {
        $this->userKycModel = new UserKycModel();
    }



    private function setTableData()
    {
        $pager = \Config\Services::pager();
        $t = 'user_kyc';


        $query = $this->userKycModel
            ->select("$t.*")
            ->select('users.user_id as user_user_id')
            ->select('users.full_name as user_full_name')
            ->join("users", "$t.user_id = users.id", 'left');

        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 200) ? $plen : $this->pageLength;

        $status = inputGet('status');
        if ($status and is_string($status) and in_array($status, ['all', 'pending', 'rejected', 'approved'])) {
            $this->status = $status;
        }
        if ($this->status !== 'all')
            $query->where("$t.status", $this->status);

        // search
        $search = inputGet('search');
        if ($search) {
            $query->groupStart()
                ->orLike("users.user_id", $search)
                ->orLike("users.full_name", $search)
                ->groupEnd();
            $this->vd['search'] = $search;
        }


        // sorting
        $query->orderBy('created_at', 'DESC')->orderBy("$t.id", 'DESC');

        $this->vd['records'] = $query->paginate($this->pageLength);


        $this->vd['pager'] = $pager;
    }


    public function index(): string
    {

        $title = 'KYC List';
        $this->vd = $this->pageData($title, $title, $title);

        $this->setTableData();




        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;
        $this->vd['status'] = $this->status;

        return view('admin_dashboard/users/user_kyc', $this->vd);
    }


    public function handlePost($kyc)
    {
        $status = inputPost('status');
        $remarks = $status === 'rejected' ? inputPost('remarks') : null;

        $statusUpdatedAt = $status === 'rejected' ? $this->userKycModel->dbDate() : null;

        $this->userKycModel->update($kyc->id, [
            'status' => $status,
            'reject_remark' => $remarks,
            'status_updated_at' => $statusUpdatedAt
        ]);

        return response()->setJSON(['success' => true]);
    }

    public function detail(int $id)
    {
        $kyc = $this->userKycModel->find($id);

        if (!$kyc) {
            return show_404();
        }

        if ($this->request->is('post')) {
            return $this->handlePost($kyc);
        }

        $title = 'KYC Detail';
        $this->vd = $this->pageData($title, $title, $title);

        $user = user_model()->select(['id', 'user_id', 'full_name'])->find($kyc->user_id);

        $this->vd['kyc'] = $kyc;
        $this->vd['user'] = $user;


        return view('admin_dashboard/users/user_kyc_detail', $this->vd);
    }
}
