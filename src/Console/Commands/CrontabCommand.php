<?php

namespace DddCore\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CrontabCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:crontab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '系统定时任务管理';


    public function handle(): void
    {
        while (true){
            Log::info('执行crontab命令'.date('Y-m-d H:i:s'));
            $this->call("schedule:run");
            sleep(58);
        }
    }

}
