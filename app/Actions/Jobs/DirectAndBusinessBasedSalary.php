<?php

namespace App\Actions\Jobs;

use App\Enums\WalletTransactionCategory;
use App\Models\UserModel;
use App\Models\UserSalaryModel;
use App\Models\WalletModel;
use App\Twebsol\Plans;

class DirectAndBusinessBasedSalary
{

    private const STRUCTURE_TYPE = 'DIRECT_AND_BUSINESS_BASED_SALARY';

    private const TRANSACTION_CATEGORY = WalletTransactionCategory::WEEKLY_SALARY;

    public function scan(): self
    {

        $users = $this->users();
        $structures = Plans::DIRECT_AND_BUSINESS_BASED_SALARY_STRUCTURE;

        foreach ($users as $user) {

            $activeDirectCount = model(UserModel::class)->getDirectActivePaidUsersCount($user->id);
            $directBusiness = model(UserModel::class)->getTeamInvestment($user->id, 1);

            $activeSalary = model(UserSalaryModel::class)
                ->where('user_id', $user->id)
                ->where('structure_type', self::STRUCTURE_TYPE)
                ->where('disabled_at', null)
                ->orderBy('structure_id', 'desc')
                ->first();

            $newStructureId = null;

            foreach ($structures as $structureId => $structure) {
                $directNeeded = $structure['direct'];
                $directBusinessNeeded = $structure['direct_business'];

                if (
                    ($activeDirectCount >= $directNeeded) &&
                    ($directBusiness >= $directBusinessNeeded)
                ) {
                    $newStructureId = $structureId;
                }
            }

            if (
                $newStructureId !== null &&
                ($activeSalary === null || $newStructureId > $activeSalary->structure_id)
            ) {


                $newStructure = $structures[$newStructureId];

                $alreadyExists = model(UserSalaryModel::class)
                    ->where('user_id', $user->id)
                    ->where('structure_type', self::STRUCTURE_TYPE)
                    ->where('structure_id', $newStructureId)
                    ->countAllResults() > 0;

                if (!$alreadyExists) {
                    // disabling all salaries
                    model(UserSalaryModel::class)
                        ->where('user_id', $user->id)
                        ->where('structure_type', self::STRUCTURE_TYPE)
                        ->set(['disabled_at' => date('Y-m-d H:i:s')])
                        ->update();

                    // adding new
                    model(UserSalaryModel::class)
                        ->insert([
                            'user_id' => $user->id,
                            'structure_type' => self::STRUCTURE_TYPE,
                            'structure_id' => $newStructureId,
                            'income' => $newStructure['income'],
                            'freq' => $newStructure['freq']
                        ]);
                }
            }

        }

        return $this;
    }

    public function distribute()
    {
        $activeSalaries = model(UserSalaryModel::class)
            ->select('user_salaries.*, users.id as user_id_pk, users.user_id')
            ->join('users', 'users.id = user_salaries.user_id')
            ->where('user_salaries.structure_type', self::STRUCTURE_TYPE)
            ->where('user_salaries.disabled_at', null)
            ->where('given_times < freq')
            ->where('users.status', 1)
            ->get()
            ->getResult();

        foreach ($activeSalaries as $salary) {
            // Determine reference date: last_credited_at or created_at
            $referenceDate = $salary->last_credited_at ?? $salary->created_at;

            // Calculate days difference
            $daysPassed = (time() - strtotime($referenceDate)) / 86400;

            // Skip if less than 15 days passed
            if ($daysPassed < 15) {
                continue;
            }

            // Deposit the income
            model(WalletModel::class)->deposit(
                user_id_pk: $salary->user_id_pk,
                amount: $salary->income,
                wallet_field: 'income',
                category: self::TRANSACTION_CATEGORY,
                isEarning: true,
                details: [
                    'user_salary_id' => $salary->id
                ]
            );

            // Add income stat
            addIncomeStat($salary->user_id_pk, $salary->income, 'weekly_salary');

            // Update record
            $givenTimes = $salary->given_times + 1;

            model(UserSalaryModel::class)->update($salary->id, [
                'given_times' => $givenTimes,
                'last_credited_at' => date('Y-m-d H:i:s'),
                'disabled_at' => $givenTimes == $salary->freq ? date('Y-m-d H:i:s') : null,
            ]);
        }
    }


    private function users()
    {
        return model(UserModel::class)
            ->select([
                'users.id',
            ])
            ->get()
            ->getResult();
    }
}