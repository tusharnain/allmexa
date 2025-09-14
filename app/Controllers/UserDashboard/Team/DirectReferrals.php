<?php
// Directly Joined Users List
namespace App\Controllers\UserDashboard\Team;

use Config\Services;
use App\Models\UserModel;
use App\Controllers\ParentController;
use CodeIgniter\HTTP\ResponseInterface;

class DirectReferrals extends ParentController
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
        $query = new UserModel;
        $pager = Services::pager(); // pager

        $t = 'users';
        $wt = 'wallets';

        $query->select([
            "$t.id",
            "$t.user_id",
            "$t.full_name",
            "$t.email",
            "$t.status",
            "$t.profile_picture",
            "$t.created_at",
            "$t.activated_at",
            "COALESCE($wt.investment, 0) as total_investment"
        ])
            ->join($wt, "$wt.user_id = $t.id", 'left')
            ->where('sponsor_id', user('id'));


        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 200) ? $plen : $this->pageLength;

        //search
        $search = inputGet('search');

        if ($search) {
            $query->groupStart()
                ->orLike($t . '.user_id', $search)
                ->orLike($t . '.full_name', $search)
                ->orLike($t . '.email', $search)
                ->groupEnd();

            $this->vd['search'] = $search;
        }

        // sorting
        $query->orderBy('created_at', 'DESC')->orderBy('id', 'DESC');

        $users = $query->paginate($this->pageLength);

        foreach ($users as &$user)
            $user->total_investment = $user->total_investment ? f_amount(_c($user->total_investment)) : '';

        $this->vd['users'] = &$users;
        $this->vd['pager'] = $pager;
    }


    public function index(): bool|string|ResponseInterface
    {
        $title = 'Direct Referrals';
        $this->vd = $this->pageData($title, $title, $title);

        $user_id_pk = user('id');

        $this->setupTable();
        $totalActiveReferrals = $this->userModel->getDirectActiveReferralsCountFromUserIdPk($user_id_pk);


        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;

        $this->vd['totalActiveReferrals'] = &$totalActiveReferrals;

        return view('user_dashboard/team/direct_referrals', $this->vd); // return string
    }
}
