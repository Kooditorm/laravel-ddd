<?php

namespace DDDCore\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(app_path('Infrastructure/Console/Makers'));
        $this->load(app_path('Infrastructure/Console/Commands'));
    }
}
