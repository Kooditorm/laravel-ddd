<?php

namespace DDDCore\Console\Commands;


use Illuminate\Console\Command;

/**
 * @class CrontabCommand
 * @package DDDCore\Console\Commands
 */
class CrontabCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'command:crontab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'System scheduled task management';



    public function handle(): void
    {
        while (true){
            $this->call("schedule:run");
            sleep(58);
        }
    }

}
