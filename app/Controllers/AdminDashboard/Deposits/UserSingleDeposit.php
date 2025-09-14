<?php

namespace App\Controllers\AdminDashboard\Deposits;

use App\Models\DepositModel;
use App\Controllers\ParentController;

class UserSingleDeposit extends ParentController
{
    private ?object $deposit;
    private ?object $user;
    private DepositModel $depositModel;
    private array $vd;

    public function __construct()
    {
        $this->depositModel = new DepositModel;
    }

    public function handlePost()
    {

        try {
            //if deposit is not pending
            if ($this->deposit->status !== DepositModel::DEPOSIT_STATUS_PENDING) {
                return resJson(['f_redirect' => route('admin.deposits.userSingleDeposit', $this->deposit->track_id)], 400);
            }

            $res = $this->depositModel->updateDepositStatus(depositId: $this->deposit->id);

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);


            $info = memory('admin_deposit_status_update_info');

            $statusAlertView = view('admin_dashboard/deposits/__status_alert', [
                'deposit_status' => $info['status']
            ]);


            return resJson([
                'success' => true,
                'message' => 'Deposit status has been updated!',
                'info' => $info,
                'html' => [
                    'status_alert' => $statusAlertView
                ]
            ]);

        } catch (\Exception $e) {

            return server_error_ajax($e);

        }

    }


    public function index(string $depositTrackId)
    {
        // Track Id Validation
        $this->deposit = $this->depositModel->getDepositFromTrackId(track_id: $depositTrackId);
        $this->user = $this->deposit ? get_user(user_id: $this->deposit->user_id, columns: ['user_id', 'full_name'], is_user_id_pk: true) : null;

        if (!$this->deposit or !$this->user) {
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


        $title = "$userLabel Deposit";
        $this->vd = $this->pageData($title, $title, $title);


        $this->vd['deposit'] = $this->deposit;
        $this->vd['user'] = $this->user;

        return view('admin_dashboard/deposits/user_single_deposit', $this->vd);
    }

}
