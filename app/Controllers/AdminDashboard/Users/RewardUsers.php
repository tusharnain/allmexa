<?php

namespace App\Controllers\AdminDashboard\Users;

use App\Models\UserModel;
use App\Models\UserRewardsModel;
use App\Controllers\ParentController;
use Carbon\Carbon;

class RewardUsers extends ParentController
{
    private UserModel $userModel;
    private UserRewardsModel $userRewardsModel;
    private array $vd;

    public function __construct()
    {
        $this->userModel = new UserModel;
        $this->userRewardsModel = new UserRewardsModel;
    }



    public function index()
    {
        $user_label = label('users');
    
        $this->vd = $this->pageData("$user_label Rewards", "$user_label Rewards", "$user_label Rewards");
    
        $userRewards = $this->userRewardsModel
            ->select('user_rewards.*, users.full_name as user_full_name, users.user_id as user_user_id')
            ->join('users', 'users.id = user_rewards.user_id', 'left')
            ->findAll();

        foreach ($userRewards as $reward) {
            $date = new \DateTime($reward->created_at);
            $reward->formatted_created_at = $date->format('jS M Y, h:i A');
        }

        $this->vd['userRewards'] = $userRewards;
    
        return view('admin_dashboard/users/reward_users', $this->vd);
    }
    
}
