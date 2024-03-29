<?php

namespace DddCore\Console\Makers;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MakerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:ant {table} {path} {--d|del}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make file for ant project';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected Filesystem $filesystem;


    /**
     * MakerCommand constructor.
     */
    public function __construct()
    {

        $this->signature   = 'make:'.env('APP_NAME').' {table} {path}  {--d|del}';
        $this->description = 'make file for '.env('APP_NAME').' project';
        $this->filesystem  = new Filesystem();
        parent::__construct();
    }

    public function handle():void
    {
        //获取路径
        Log::info('执行crontab命令'.date('Y-m-d H:i:s'));
    }
}
