<?php

namespace App\Actions\Jobs;

use App\Enums\WalletTransactionCategory;
use App\Models\TopupModel;
use App\Models\UserModel;
use App\Models\WalletModel;

class BoosterClubIncome
{
    private $db;
    private const DIRECT_NEEDED = 4;
    public const STRUCTURE = [
        1 => 1,
        2 => 0.90,
        3 => 0.80,
        4 => 0.60,
        5 => 0.50,
        6 => 0.40,
        7 => 0.30,
        8 => 0.20,
        9 => 0.20,
        10 => 0.10,
    ];

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function handleOnDirectUserTopup(int $user_id_pk)
    {
        $checkTime = date('Y-m-d H:i:s', strtotime('-24 hours'));

        $user = model(UserModel::class)
            ->where('id', $user_id_pk)
            ->where('status', 1)
            ->where('activated_at >=', $checkTime)
            ->first();

        if ($user === null) {
            return;
        }

        $alreadyAchieved = $this->db->table('booster_club_incomes')
            ->where('user_id', $user->id)
            ->countAllResults() > 0;

        if ($alreadyAchieved) {
            return;
        }

        $directActiveCount = model(UserModel::class)->getDirectActiveReferralsCountFromUserIdPk($user->id);

        if ($directActiveCount < self::DIRECT_NEEDED) {
            return;
        }

        $this->db->table('booster_club_incomes')
            ->insert([
                'user_id' => $user->id,
                'direct_count' => $directActiveCount,
                'direct_needed' => self::DIRECT_NEEDED,
                'achieved_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

    }

    public function distribute()
    {

        $end = date('Y-m-d H:i:s');
        $start = date('Y-m-d H:i:s', strtotime('-24 hours'));

        $cto = model(TopupModel::class)->getCtoByDateRange($start, $end);

        if ($cto <= 0) {
            return;
        }

        $boosters = $this->db->table('booster_club_incomes')
            ->where('batch_id', null)
            ->where('status', 1)
            ->where('achieved_at >=', $start)
            ->where('achieved_at <=', $end)
            ->orderBy('achieved_at', 'ASC')
            ->get()
            ->getResult();

        $batchId = hash('sha256', uniqid('', true) . random_int(11111, 99999));

        foreach ($boosters as $i => $booster) {

            $rank = $i + 1;

            $percent = self::STRUCTURE[$rank] ?? null;

            if ($percent !== null) {

                $amount = a_percent_of_b($percent, $cto);


                model(WalletModel::class)->deposit(
                    user_id_pk: $booster->user_id,
                    amount: $amount,
                    wallet_field: 'income',
                    category: WalletTransactionCategory::BOOSTER_CLUB_INCOME,
                    isEarning: true,
                    details: [
                        'rank' => $rank,
                        'cto' => $cto,
                        'percent' => $percent,
                        'booster_club_income_id' => $booster->id
                    ]
                );

                addIncomeStat($booster->user_id, $amount, 'booster_club_income');

                $this->db->table('booster_club_incomes')
                    ->set([
                        'batch_id' => $batchId,
                        'rank' => $rank,
                        'income' => $amount,
                        'cto' => $cto,
                        'percent' => $percent
                    ])
                    ->update();
            } else {
                $this->db->table('booster_club_incomes')
                    ->set(['batch_id' => $batchId, 'rank' => $rank])
                    ->update();
            }


        }

    }
}