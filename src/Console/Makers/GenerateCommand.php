<?php

namespace DDDCore\Console\Makers;

use Illuminate\Filesystem\Filesystem;

class GenerateCommand extends MakerCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'gen {table} {path} {--d|del}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generate project files';


    protected Filesystem $filesystem;


    public function __construct(Filesystem $filesystem, ?string $name = null)
    {
        $this->filesystem = $filesystem;
        if (!empty($name)) {
            $this->signature   = 'gen:'.$name.' {table} {path}  {--d|del}';
        }
        parent::__construct();
    }

    public function handle(): void
    {

    }
}
