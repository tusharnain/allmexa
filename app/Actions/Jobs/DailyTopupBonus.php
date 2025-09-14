<?php

namespace App\Actions\Jobs;

use App\Enums\WalletTransactionCategory;
use App\Models\TopupModel;
use App\Models\UserModel;
use App\Models\WalletModel;

class DailyTopupBonus
{
    private $db;
    private const MINIMUM_INVESTED = 100;

    public const CTO_STRUCTURE = [
        0.80,
        0.40,
        0.20,
        0.10
    ];

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function distribute()
    {
        $date = date('Y-m-d');

        $dateRecords = $this->db->table('daily_topup_bonanza_incomes')
            ->where('date', $date)
            ->countAllResults();

        if ($dateRecords > 0) {
            return;
        }


        $end = date('Y-m-d H:i:s');
        $start = date('Y-m-d H:i:s', strtotime('-24 hours'));

        $cto = model(TopupModel::class)->getCtoByDateRange($start, $end);

        if ($cto <= 0) {
            return;
        }


        $users = model(UserModel::class)
            ->where('status', 1) // active users only
            ->where('activated_at >=', $start)
            ->where('activated_at <=', $end)
            ->orderBy('activated_at', 'asc')
            ->findAll();

        if (count($users) <= 0) {
            return;
        }

        $rewarded = 0;

        $limit = count(self::CTO_STRUCTURE);

        foreach ($users as $user) {

            if ($rewarded >= $limit) {
                break;
            }

            $alreadyHave = $this->db->table('daily_topup_bonanza_incomes')
                ->where('user_id', $user->id)
                ->countAllResults() > 0;

            if (!$alreadyHave) {

                $investment = model(WalletModel::class)->getUserTotalInvestment($user->id);

                if ($investment >= self::MINIMUM_INVESTED) {

                    $percent = self::CTO_STRUCTURE[$rewarded];

                    $amount = a_percent_of_b($percent, $cto);

                    model(WalletModel::class)->deposit(
                        user_id_pk: $user->id,
                        amount: $amount,
                        wallet_field: 'income',
                        category: WalletTransactionCategory::DAILY_TOPUP_BONANZA,
                        isEarning: true,
                        details: [
                            'position' => $rewarded + 1,
                            'investment' => $investment,
                            'percent' => $percent,
                            'cto' => $cto
                        ],
                    );

                    addIncomeStat($user->id, $amount, 'daily_topup_bonanza');

                    $this->db->table('daily_topup_bonanza_incomes')
                        ->insert([
                            'user_id' => $user->id,
                            'rank' => $rewarded + 1,
                            'date' => $date,
                            'income' => $amount,
                            'cto' => $cto,
                            'percent' => $percent,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                    $rewarded++;
                }

            }
        }

    }
}
