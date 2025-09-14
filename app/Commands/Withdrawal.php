<?php

namespace App\Commands;


use App\Models\UserModel;
use CodeIgniter\CLI\BaseCommand;


class Withdrawal extends BaseCommand
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
    protected $name = 'cron:withdraw';

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
        $userIncomeService = new \App\Services\UserIncomeService;

        $userModel = new UserModel();

        $userModel->db->transBegin();

        try {
            $userIncomeService->withdrawal();

            $userModel->db->transCommit();
        } catch (\Exception $e) {
            $userModel->db->transRollback();
            throw $e;
        }

    }
}
