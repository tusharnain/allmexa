<?php

namespace App\Models;


class UserRewardsModel extends ParentModel
{
    protected $table = 'user_rewards';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'reward_id', 'active_salary', 'salary_freq'];


    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';



    public function hasUserAlreadyAchieved(int $user_id_pk, int $reward_id): bool
    {
        return !!($this->select('id')->where(['user_id' => $user_id_pk, 'reward_id' => $reward_id])->first()->id ?? false);
    }

    public function getAllUserRewards(int $user_id_pk, string|array $columns = '*'): array
    {
        return $this->select($columns)->where('user_id', $user_id_pk)->get()->getResult();
    }

    public function giveReward(int $user_id_pk, int $rewardId)
    {
        $data = [
            'user_id' => $user_id_pk,
            'reward_id' => $rewardId,
            'active_salary' => 1
        ];

        $insertResult = $this->insert($data);

        // deactivate active_salary for rest of rewards
        $this->where('user_id', $user_id_pk)
            ->where('reward_id !=', $rewardId)
            ->set('active_salary', 0)
            ->update();

        return $insertResult;
    }
}