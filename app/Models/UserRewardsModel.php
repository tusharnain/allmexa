<?php

namespace App\Models;


class UserRewardsModel extends ParentModel
{
    protected $table = 'user_rewards';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'reward_id', 'reward_type', 'details'];


    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';


    public function getAllUserRewards(int $user_id_pk, string $rewardType, string|array $columns = '*'): array
    {
        return $this->select($columns)
            ->where('user_id', $user_id_pk)
            ->where('reward_type', $rewardType)
            ->get()
            ->getResult();
    }


    public function hasUserAlreadyAchieved(int $user_id_pk, string $type, int $reward_id): bool
    {
        return !!($this->select('id')->where([
            'user_id' => $user_id_pk,
            'reward_type' => $type,
            'reward_id' => $reward_id
        ])
            ->first()->id ?? false);
    }
}