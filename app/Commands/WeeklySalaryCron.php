<?php

namespace App\Commands;

use App\Actions\Jobs\DirectAndBusinessBasedSalary;
use App\Services\UserIncomeService;
use CodeIgniter\CLI\BaseCommand;


class WeeklySalaryCron extends BaseCommand
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
    protected $name = 'cron:weekly-salary';

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

        $db = db_connect();

        $db->transBegin();

        try {

            (new DirectAndBusinessBasedSalary)->distribute(); // every 15 days

            $db->transCommit();

        } catch (\Exception $e) {
            $db->transRollback();
            throw $e;
        }

    }
}
