<?php
// Directly Joined Users List
namespace App\Controllers\UserDashboard\Team;


use App\Models\UserModel;
use App\Controllers\ParentController;
use App\Models\UserIncomeModel;
use App\Twebsol\Plans;
use CodeIgniter\Model;

class LevelDownline extends ParentController
{
    private array $vd = [];

    private UserModel $userModel;
    private UserIncomeModel $userIncomeModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
        $this->userIncomeModel = new UserIncomeModel;
    }

    public function getLevelSelectView()
    {
        $levelTeam = $this->userModel->getLevelTeam(user('id'), upto_level: Plans::TEAM_UPTO_LEVEL);
        $selectArray = [];
        $usersLabel = label('users');
        foreach ($levelTeam as $level) {
            $selectArray["Level $level->level [ Total $level->totalUsers $usersLabel, $level->totalActiveUsers Active $usersLabel ]"] = $level->level;
        }
        return resJson([
            'success' => true,
            'html' => view('user_dashboard/team/_level_downline_select', ['selectArray' => $selectArray])
        ]);
    }

    public function updateLevel()
    {
        $level = inputPost('level');
        $user_id_pk = user('id');

        $user_query = function (Model &$query, object &$sponsor) {
            $u = 'users';
            $wt = 'wallets';
            return $query->select([
                "$u.id",
                "$u.user_id",
                "$u.full_name",
                "$u.sponsor_id",
                "$u.email",
                "$u.status",
                "$u.created_at",
                "$u.activated_at",
                'sponsor.user_id as sponsor_user_id',
                'sponsor.full_name as sponsor_full_name',
                "COALESCE($wt.investment, 0) as total_investment",
            ])->where("$u.sponsor_id", $sponsor->id)
                ->join("$u as sponsor", "$u.sponsor_id = sponsor.id", 'left')
                ->join($wt, "$wt.user_id = $u.id", 'left')
                ->orderBy('created_at', 'DESC')->orderBy("id", 'DESC');
        };

        $levelUsers = $this->userModel->getTotalUsersFromLevel($user_id_pk, level: $level, user_query: $user_query);

        $levelInvestment = 0;

        foreach ($levelUsers as &$user) {
            $user->f_created_at = f_date($user->created_at);
            $user->f_activated_at = $user->activated_at ? f_date($user->activated_at) : null;

            $levelInvestment += $user->total_investment;

            $user->total_investment = f_amount(_c($user->total_investment), isUser: true);

            unset($user->id, $user->sponsor_id); // removing sensitive data
        }

        return resJson([
            'success' => true,
            'level' => $level,
            'data' => $levelUsers,
            'levelInvestment' => $levelInvestment,
            'flevelInvestment' => f_amount(_c($levelInvestment))
        ]);
    }


    private function handlePost()
    {
        try {
            session()->close();

            $action = inputPost('action');
            switch ($action) {
                case 'level_select_view':
                    return $this->getLevelSelectView();
                case 'update_level':
                    return $this->updateLevel();
            }
        } catch (\Exception $e) {
            server_error_ajax($e);
        } finally {
            session()->start();
        }
    }


    public function index()
    {
        if ($this->request->is('post'))
            return $this->handlePost();

        $title = 'Level Downline';
        $this->vd = $this->pageData($title, $title, $title);

        $user_id_pk = user('id');

        $hasDirectUser = $this->userModel->hasDirectUser(user_id_pk: $user_id_pk);

        $this->vd['hasDirectUser'] = &$hasDirectUser;

        return view('user_dashboard/team/level_downline', $this->vd);
    }
}
