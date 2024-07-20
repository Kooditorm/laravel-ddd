<?php

namespace DDDCore\Console\Makers;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;


/**
 * @class GenerateCommand
 * @package DDDCore\Console\Makers
 */
class GenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen {table : The name of the table} {path : The location where the file is generated} {--d|del : Delete generated files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'automatically generate project files';

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
        DTOCommand::class
    ];


    public function __construct(Filesystem $filesystem, ?string $name = null)
    {
        $this->filesystem = $filesystem;
        if (!empty($name)) {
            $this->signature = str_replace('gen', 'gen:'.$name, $this->signature);
        }
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
            $option['action'] = 'delete';
        }

        //模块下
        $basePath = app_path("Domain");
        !$this->filesystem->exists($basePath) && $this->filesystem->makeDirectory($basePath);
        $filePath = $basePath.DIRECTORY_SEPARATOR.$inputPath;
        !$this->filesystem->exists($filePath) && $this->filesystem->makeDirectory($filePath);


        //修改repository配置文件
        config([
            'repository.generator.basePath'          => $filePath,
            'repository.generator.rootNamespace'     => "App\\Domain\\{$inputPath}\\",
            'repository.generator.stubsOverridePath' => __dir__,
            'repository.generator.inputPath'         => $inputPath
        ]);


        $isExe = true;

        if ($option['action'] === 'delete') {
            $isExe = $this->confirm('此操作删除 '.$tableName.' 表所删除的文件，您确定执行');
        }

        if ($isExe) {
            $this->runCall($option);
        }
    }

    private function runCall(array $option = []): void
    {
        $commandObj = [];
        $single     = 0;

        $register = $this->register;

        foreach ($register as $reg) {
            if (array_key_exists($reg, $commandObj) === false) {
                $obj = new $reg();
                if (method_exists($obj, 'single')) {
                    $commandObj[$reg] = [$obj->getName(), $obj->single()];
                    if ($obj->single()) {
                        $single++;
                    }
                } else {
                    $commandObj[$reg] = [$obj->getName(), false];
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
