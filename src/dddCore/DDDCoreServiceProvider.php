<?php

namespace DDDCore;

use DddCore\Console\Commands\CronTabCommand;
use Illuminate\Contracts\Support\DeferrableProvider;
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
        $this->app->singleton('command.ddd-core.crontab', function () {
            return new CronTabCommand();
        });
        $this->commands(
            'command.ddd-core.crontab'
        );
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['command.ddd-core.crontab'];
    }
}
