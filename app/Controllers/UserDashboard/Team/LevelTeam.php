<?php
// Directly Joined Users List
namespace App\Controllers\UserDashboard\Team;


use App\Models\UserModel;
use App\Controllers\ParentController;
use App\Twebsol\Plans;

class LevelTeam extends ParentController
{
    private array $vd = [];
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
    }


    private function handlePost()
    {
        try {

            session()->close();

            $levels = $this->userModel->getLevelTeam(user('id'), upto_level: Plans::TEAM_UPTO_LEVEL);

            // all level info
            $allLevels = new \stdClass;
            $allLevels->totalUsers = 0;
            $allLevels->totalActiveUsers = 0;
            $allLevels->totalInactiveUsers = 0;

            foreach ($levels as &$level) {
                $allLevels->totalUsers += $level->totalUsers;
                $allLevels->totalActiveUsers += $level->totalActiveUsers;
                $allLevels->totalInactiveUsers += $level->totalInactiveUsers;
            }

            $vd['levels'] = &$levels;
            $vd['allLevels'] = &$allLevels;

            return resJson([
                'success' => true,
                'html' => view('user_dashboard/team/_level_team_table', $vd)
            ]);
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

        $title = 'Team Level';
        $this->vd = $this->pageData($title, $title, $title);

        $user_id_pk = user('id');
        $hasDirectUser = $this->userModel->hasDirectUser(user_id_pk: $user_id_pk);


        $this->vd['hasDirectUser'] = &$hasDirectUser;

        return view('user_dashboard/team/level_team', $this->vd);
    }
}
