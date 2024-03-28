<?php

namespace App\Infrastructure\Console\Makers;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
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
     * @var array
     */
    protected array $register = [
        DTOCommand::class,
        RepositoryCommand::class,
        ServiceCommand::class,
        ValidatorCommand::class,
        ListenerCommand::class,
        ConstantCommand::class,
        ExceptionCommand::class,
        ProxyCommand::class
    ];

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

    public function handle(): void
    {
        //获取路径
        $inputPath = Str::ucfirst(strtolower($this->argument('path')));
        //获取表名
        $table = $this->argument('table');
        if ($table === Str::plural($table)) {
            $table = Str::singular($table);
        }
        $tableName = Str::studly($table);

        //定义操作参数
        $option = [
            'name'   => $tableName,
            'action' => ''
        ];
        if ($this->option('del')) {
            $option['action'] = 'del';
        }


        //模块下
        $basePath = app_path()."/Domain/";
        !$this->filesystem->exists($basePath) && $this->filesystem->makeDirectory($basePath);
        $filePath = "{$basePath}/{$inputPath}";
        !$this->filesystem->exists($filePath) && $this->filesystem->makeDirectory($filePath);


        //修改repository配置文件
        config([
            'repository.generator.basePath'          => $filePath,
            'repository.generator.rootNamespace'     => "App\\Domain\\{$inputPath}\\",
            'repository.generator.stubsOverridePath' => __dir__,
            'repository.generator.inputPath'         => $inputPath
        ]);

        $isExe = true;

        if ($option['action'] === 'del') {
            $isExe = $this->confirm('此操作删除 '.$tableName.' 表所删除的文件，您确定执行');
        }

        if ($isExe) {
            $this->runCall($option);
        }
    }

    /**
     * @return void
     */
    protected function register(): array
    {
        return [];
    }

    protected function runCall(array $option = []): void
    {
        $commandObj = [];
        $single     = 0;

        $register = array_merge($this->register, $this->register());
        foreach ($register as $item) {
            if (array_key_exists($item, $commandObj) === false) {
                $obj = new $item();
                if (method_exists($obj, 'single')) {
                    $commandObj[$item] = [$obj->getName(), $obj->single()];
                    if ($obj->single()) {
                        $single++;
                    }
                } else {
                    $commandObj[$item] = [$obj->getName(), false];
                }
            }
        }

        if (!empty($commandObj)) {
            foreach($commandObj as $command){
                if ($command[1] === true){
                    $option['residue'] = $single;
                    $single--;
                }
                $this->call($command[0], $option);
            }
        }

    }

}
