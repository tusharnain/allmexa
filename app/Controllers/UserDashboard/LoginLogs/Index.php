<?php
// Directly Joined Users List
namespace App\Controllers\UserDashboard\LoginLogs;


use App\Libraries\MyLib;
use App\Models\UserModel;
use App\Controllers\ParentController;


class Index extends ParentController
{
    private array $vd = [];
    private UserModel $userModel;
    private array $pageLengths = [15, 30, 50, 100];
    private int $pageLength = 15;
    public function __construct()
    {
        $this->userModel = new UserModel;
    }

    private function setupTable()
    {
        // ! Performing a little bit of manual pagination, because of using builder class not model.

        $user_id_pk = user('id');

        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen >= 1 and $plen <= 100) ? $plen : $this->pageLength;


        $builder = $this->userModel->loginLogsTable()
            ->select()
            ->where("user_id", $user_id_pk)
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC');


        // track id search
        $search = inputGet('search');
        if ($search) {
            $builder->groupStart()
                ->orLike("os", $search)
                ->orLike("browser", $search)
                ->orLike("ip_address", $search)
                ->groupEnd();
            $this->vd['search'] = $search;
        }

        $pagination = MyLib::builderPagination(builder: $builder, per_page: $this->pageLength, pager_tempalte: 'user_dashboard');

        $this->vd['logs'] = $pagination->data;
        $this->vd['pager'] = $pagination->pager;
    }


    public function index()
    {
        $title = 'Login Logs';
        $this->vd = $this->pageData($title, $title, $title);

        $user_id_pk = user('id');

        $totalSuccessLogins = $this->userModel->getUserTotalSuccessLoginsFromUserIdPk($user_id_pk, status: UserModel::LoginLogStatus_SUCCESS);
        $totalFailedLogins = $this->userModel->getUserTotalSuccessLoginsFromUserIdPk($user_id_pk, status: UserModel::LoginLogStatus_FAIL);

        $this->setupTable();


        $this->vd['totalSuccessLogins'] = &$totalSuccessLogins;
        $this->vd['totalFailedLogins'] = &$totalFailedLogins;

        return view('user_dashboard/login_logs/index', $this->vd);
    }
}
