<?php

namespace DDDCore;

use DddCore\Console\Commands\CronTabCommand;
use DDDCore\Console\Kernel;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class DDDCoreServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        Log::info('DDDCoreServiceProvider register');
        $this->app->singleton('command.command:crontab' , function (){
            return new CronTabCommand();
        });
        $this->commands('command.command:crontab');
    }
}
