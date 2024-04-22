<?php

namespace DDDCore\Console\Markers;

use Illuminate\Filesystem\Filesystem;

class GenerateCommand extends MakerCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'gen:generate {table} {path} {--d|del}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generate project files';


    protected Filesystem $filesystem;


    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        parent::__construct();
    }

    public function handle(): void
    {

    }
}
