<?php

namespace App\Models;

use App\Enums\UserIncomeStats;
use CodeIgniter\Database\BaseBuilder;

//! This model class is not for a specific table, its for many tables
//! 1. sponsor_level_incomes
//! 2. roi_incomes
//! 3. sponsor_roi_level_incomes

class UserIncomeModel extends ParentModel
{
    private ?BaseBuilder $sponsor_level_income_table = null;
    private ?BaseBuilder $roi_incomes_table = null;
    private ?BaseBuilder $sponsor_roi_level_income_table = null;
    const SPONSOR_LEVEL_INCOMES_TABLE = 'sponsor_level_incomes';
    const ROI_INCOMES_TABLE = 'roi_incomes';
    const SPONSOR_ROI_LEVEL_INCOMES_TABLE = 'sponsor_roi_level_incomes';

    // tables
    private ?BaseBuilder $userIncomeStatsTable = null;

    public function getUserIncomeStatsTable(): BaseBuilder
    {
        return $this->userIncomeStatsTable ??= $this->db->table('user_income_stats');
    }



    public function sponsorLevelIncomeTable(): BaseBuilder
    {
        return $this->sponsor_level_income_table ??= $this->db->table(self::SPONSOR_LEVEL_INCOMES_TABLE);
    }
    public function roiIncomesTable(): BaseBuilder
    {
        return $this->roi_incomes_table ??= $this->db->table(self::ROI_INCOMES_TABLE);
    }
    public function sponsorRoiLevelIncomeTable(): BaseBuilder
    {
        return $this->sponsor_roi_level_income_table ??= $this->db->table(self::SPONSOR_ROI_LEVEL_INCOMES_TABLE);
    }



    //getters
    public function getSponsorLevelIncomeRecord(int $sli_id_pk, string|array $columns = '*'): object|null
    {
        return $this->sponsorLevelIncomeTable()->select($columns)->where('id', $sli_id_pk, true)->get()->getRowObject();
    }
    public function getSponsorRoiLevelIncomeRecord(int $srli_id_pk, string|array $columns = '*'): object|null
    {
        return $this->sponsorRoiLevelIncomeTable()->select($columns)->where('id', $srli_id_pk, true)->get()->getRowObject();
    }








    /*
     *------------------------------------------------------------------------------------
     * Sponsor Level Income
     *------------------------------------------------------------------------------------
     */
    public function saveSponsorLevelIncomeRecord(int $user_id_pk, string $amount, int $level, int $level_user_id_pk, int $topup_id_pk, int $transaction_id_pk, string|float $bv, ?int $percent = null): int
    {
        $this->sponsorLevelIncomeTable()->insert([
            'user_id' => &$user_id_pk,
            'amount' => &$amount,
            'level' => &$level,
            'level_user_id' => &$level_user_id_pk,
            'percent' => &$percent,
            'bv' => &$bv,
            'topup_id_pk' => &$topup_id_pk,
            'transaction_id_pk' => &$transaction_id_pk,
            ...$this->getTimestamps()
        ], escape: true);


        return $this->db->insertID();
    }

    /*
     *------------------------------------------------------------------------------------
     * ROI Income
     *------------------------------------------------------------------------------------
     */
    public function saveRoiIncome(int $user_id_pk, string $amount, int $roi_id_pk, int $upgradeLevel, int $transaction_id_pk): int
    {
        $this->roiIncomesTable()->insert([
            'user_id' => &$user_id_pk,
            'amount' => &$amount,
            'roi_id' => $roi_id_pk,
            'upgrade_level' => $upgradeLevel,
            'transaction_id_pk' => &$transaction_id_pk,
            ...$this->getTimestamps(type: 2)
        ], escape: true);

        return $this->db->insertID();
    }


    /*
     *------------------------------------------------------------------------------------
     * Sponsor ROI Level Income
     *------------------------------------------------------------------------------------
     */
    public function saveSponsorRoiLevelIncomeRecord(int $user_id_pk, string $amount, int $level, int $level_user_id_pk, int $transaction_id_pk, string $roi_bv, ?int $percent = null): int
    {
        $this->sponsorRoiLevelIncomeTable()->insert([
            'user_id' => &$user_id_pk,
            'amount' => &$amount,
            'level' => &$level,
            'level_user_id' => &$level_user_id_pk,
            'percent' => &$percent,
            'roi_bv' => &$roi_bv,
            'transaction_id_pk' => &$transaction_id_pk,
            ...$this->getTimestamps()
        ], escape: true);


        return $this->db->insertID();
    }




    /*
     *------------------------------------------------------------------------------------
     * User Income Stats Table Operations
     *------------------------------------------------------------------------------------
     */
    public function getUserIncomeStatsFromUserIdPk(int $user_id_pk, string|array $columns = '*'): object|null
    {
        $res = $this->getUserIncomeStatsTable()->select($columns)->where('user_id', $user_id_pk)->get()->getRowObject();

        if (!$res) {
            $res = new \stdClass;
            $statsNameArray = UserIncomeStats::getArray();
            foreach ($statsNameArray as &$statName)
                $res->$statName = 0;
        }

        return $res;
    }
    public function updateUserIncomeStat(int $user_id_pk, string $stat, float|string $increment)
    {
        // ofcourse $increment can also accept negative values to negate the stat
        $table = $this->getUserIncomeStatsTable();
        $recordExists = $table->select(['id', $stat])->where('user_id', $user_id_pk)->get()->getFirstRow();
        if ($recordExists) {
            $updatedStat = bcadd($recordExists->{$stat}, $increment, 16);
            $table->set($stat, $updatedStat)->where('id', $recordExists->id)->update();
        } else {
            $table->insert([
                'user_id' => $user_id_pk,
                $stat => $increment,
                ...$this->getTimestamps(2)
            ]);
        }
    }
    // quick get function for total investment
    public function getTotalInvestFromUserIdPk(int $user_id_pk): float|string
    {
        $stats = $this->getUserIncomeStatsFromUserIdPk(user_id_pk: $user_id_pk, columns: [UserIncomeStats::TOTAL_INVESTMENT]);
        return $stats->{UserIncomeStats::TOTAL_INVESTMENT} ?? 0;
    }




    //! For All Users 
    public function refreshAllUsersIncomeStat()
    {
        $users = user_model(static: true)->findAll();
        foreach ($users as &$user) {
        }
    }
}
