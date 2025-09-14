<?php
// Directly Joined Users List
namespace App\Controllers\UserDashboard\Team;


use App\Models\UserModel;
use App\Models\WalletModel;
use App\Controllers\ParentController;
use App\Twebsol\Plans;

class TreeView extends ParentController
{
    private array $vd = [];
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
    }


    public function getReferrals(string $userIdPk)
    {

        $users = $this->userModel->getDirectUsersFromUserIdPk($userIdPk);

        $userObjectArray = [];

        foreach ($users as &$user) {

            $userObjectArray[] = [
                'id' => (int) $user->id,
                'parentId' => (int) $userIdPk,
                'user_id' => $user->user_id,
                'name' => $user->full_name,
                'image' => UserModel::getAvatar($user),
                'status' => (int) $user->status,
                'children_count' => user_model()->getDirectReferralsCountFromUserIdPk($user->id)
            ];

        }

        return response()->setJSON($userObjectArray);

    }

    public function getUserModal(int $userIdPk)
    {
        $walletModel = new WalletModel();

        $user = $this->userModel->find($userIdPk);
        $totalInvestment = $walletModel->getUserTotalInvestment($userIdPk);

        $totalTeamBusiness = $this->userModel->getTeamInvestment($userIdPk, 99999999);
        $powerLegBusiness = $this->userModel->getPowerLegBusiness($userIdPk);
        $otherLegBusiness = $totalTeamBusiness - $powerLegBusiness;

        $html = view('user_dashboard/team/_user_tree_modal.php', compact(
            'user',
            'totalInvestment',
            'totalTeamBusiness',
            'powerLegBusiness',
            'otherLegBusiness'
        ));

        return response()->setJSON([
            'html' => $html
        ]);
    }


    public function index()
    {
        $user = user();

        if ($parentId = inputGet('parent_id')) {
            return $this->getReferrals($parentId);
        }

        if ($modalUserId = inputGet('modal_user_id')) {
            return $this->getUserModal($modalUserId);
        }

        if ($searchUserId = inputGet('search_user_id')) {
            $user = $this->userModel->where("user_id", $searchUserId)->first();

            if (!$user) {
                $errorMessage = 'Invalid User Id!';
            }

            if ($user && !$this->userModel->isUserFromTeam(user('id'), $user->id)) {
                $user = null;
                $errorMessage = 'User not from your team!';
            }

        }



        $title = 'Tree View';
        $this->vd = $this->pageData($title, $title, $title);

        if ($user) {
            $this->vd['userObject'] = [
                'id' => (int) $user->id,
                'parentId' => null,
                'user_id' => $user->user_id,
                'name' => $user->full_name,
                'image' => UserModel::getAvatar($user),
                'status' => (int) $user->status,
                'children_count' => user_model()->getDirectReferralsCountFromUserIdPk($user->id)
            ];
        }

        $this->vd['user'] = $user;
        $this->vd['errorMessage'] = $errorMessage ?? null;

        return view('user_dashboard/team/tree_view', $this->vd);
    }
}
