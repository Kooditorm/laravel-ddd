<?php

namespace DDDCore\Console\Makers;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;


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

    }
}
