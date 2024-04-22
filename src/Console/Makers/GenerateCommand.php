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
    protected $signature = 'gen {--table=: The name of the table} {--path: The location where the file is generated} {--d|del}';

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
            $this->signature = str_replace('gen', 'gen:'.$name, $this->name);
        }
        parent::__construct();
    }

    public function handle(): void
    {

    }
}
