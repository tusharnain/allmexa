<?php

namespace App\Models;

use App\Libraries\MyLib;

class IncomeStatModel extends ParentModel
{
    protected $table = 'income_stats';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'level_income', 'roi', 'roi_level_income', 'weekly_salary', 'daily_topup_bonanza', 'booster_club_income', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    private static $stats = [ // do not change the indexing, as its being used in validation service with indeces
        1 => 'level_income',
        2 => 'roi',
        3 => 'roi_level_income',
        4 => 'weekly_salary',
        5 => 'daily_topup_bonanza'
    ];


    private function getEmptyStat(): object
    {
        $stats = array();

        foreach (self::$stats as &$stat)
            $stats[$stat] = 0;

        return MyLib::getObjectFromArray($stats);
    }

    public function getStatsRecordFromUserIdPk(int $user_id_pk, array $columns = ['*']): object|null
    {
        $stat = $this->select($columns)->where('user_id', $user_id_pk)->first();
        return $stat ?? $this->getEmptyStat();
    }

    public function getAllStatFromUserIdPk(int $user_id_pk): object|null
    {
        $stat = $this->select(self::$stats)->where('user_id', $user_id_pk)->first();
        return $stat ?? $this->getEmptyStat();
    }

    public function getStatValueFromUserIdPk(int $user_id_pk, string $stat_field): string|float
    {
        $stat = $this->select($stat_field)->where('user_id', $user_id_pk)->first();
        return $stat ? $stat->{$stat_field} : 0.0;
    }

    public function add(int $user_id_pk, string|float $amount, string $stat_field)
    {
        $stat = $this->getStatsRecordFromUserIdPk($user_id_pk, ['id']);

        if (isset($stat->id)) {
            $this->where('id', $stat->id)
                ->set($stat_field, "$stat_field + $amount", false)
                ->update();
        } else {
            $this->insert([
                'user_id' => $user_id_pk,
                $stat_field => $amount
            ]);
        }
    }
}
