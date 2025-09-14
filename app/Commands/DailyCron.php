<?php

namespace App\Commands;

use App\Actions\Jobs\BoosterClubIncome;
use App\Actions\Jobs\DailyTopupBonus;
use App\Actions\Jobs\DirectAndBusinessBasedSalary;
use App\Models\UserModel;
use App\Services\UserIncomeService;
use CodeIgniter\CLI\BaseCommand;


class DailyCron extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'cron:daily';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'command:name [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {

        // $isWeekDay = date('N') >= 1 && date('N') <= 5;

        // if (!$isWeekDay) {
        //     echo "Can't run on weekend!" . PHP_EOL;
        //     return;
        // }


        $db = db_connect();

        $db->transBegin();

        try {

            (new UserIncomeService)->distributeInvestmentROI();

            (new UserIncomeService)->giveCompoundRoi();

            // (new DirectAndBusinessBasedSalary)->scan()->distribute(); // every 15 days

            (new DailyTopupBonus)->distribute();

            (new BoosterClubIncome)->distribute();




            $db->transCommit();

        } catch (\Exception $e) {
            $db->transRollback();
            throw $e;
        }

    }
}
