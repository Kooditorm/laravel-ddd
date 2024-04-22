<?php

namespace DDDCore\Console\Commands;

use Illuminate\Support\Facades\Log;

class GenerateCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'gen:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generate project files';

    public function handle(): void
    {
        Log::info('The execution time of command');
    }
}
