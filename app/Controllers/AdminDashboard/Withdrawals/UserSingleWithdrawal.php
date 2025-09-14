<?php

namespace App\Controllers\AdminDashboard\Withdrawals;

use App\Models\WithdrawalModel;
use App\Controllers\ParentController;

class UserSingleWithdrawal extends ParentController
{
    private ?object $withdrawal;
    private ?object $user;
    private WithdrawalModel $withdrawalModel;
    private array $vd;

    public function __construct()
    {
        $this->withdrawalModel = new WithdrawalModel;
    }

    public function handlePost()
    {
        try {
            //if deposit is not pending
            if ($this->withdrawal->status !== WithdrawalModel::WD_STATUS_PENDING) {
                return resJson(['f_redirect' => route('admin.withdrawals.userSingleWithdrawal', $this->withdrawal->track_id)], 400);
            }

            $res = $this->withdrawalModel->updateWithdrawalStatus(withdrawalId: $this->withdrawal->id);

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);

            $info = memory('admin_withdrawal_status_update_info');

            $statusAlertView = view('admin_dashboard/withdrawals/__status_alert', [
                'withdrawal_status' => $info['status']
            ]);

            return resJson([
                'success' => true,
                'message' => 'Withdrawal status has been updated!',
                'info' => $info,
                'html' => [
                    'status_alert' => $statusAlertView
                ]
            ]);
        } catch (\Exception $e) {
            return server_error_ajax($e);
        }
    }


    public function index(string $withdrawalTrackId)
    {
        // Track Id Validation
        $this->withdrawal = $this->withdrawalModel->getWithdrawalFromTrackId($withdrawalTrackId);

        $this->user = $this->withdrawal ? get_user(user_id: $this->withdrawal->user_id, columns: ['id', 'user_id', 'full_name'], is_user_id_pk: true) : null;

        if (!$this->withdrawal or !$this->user) {
            if ($this->request->isAJAX()) {
                return ajax_404_response();
            } else {
                show_404();
                return;
            }
        }

        if ($this->request->is('post'))
            return $this->handlePost();

        $userLabel = label('user');

        $title = "$userLabel Withdrawal";
        $this->vd = $this->pageData($title, $title, $title);

        $this->vd['withdrawal'] = $this->withdrawal;
        $this->vd['user'] = $this->user;

        return view('admin_dashboard/withdrawals/user_single_withdrawal', $this->vd);
    }
}
