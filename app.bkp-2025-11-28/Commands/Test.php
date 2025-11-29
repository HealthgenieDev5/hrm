<?php

namespace App\Commands;

use App\Controllers\Cron\ServerCron;
use App\Libraries\AttendanceProcessor;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Test extends BaseCommand
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
    protected $name = 'longleave:process';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Test using CLI';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'longleave:process';

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


        CLI::write("Starting Test", 'yellow');

        $ServerCron = new ServerCron();
        $ServerCron->sendAbsentWithoutLeaveNotification();

        CLI::write("Done processing.", 'green');
    }
}
