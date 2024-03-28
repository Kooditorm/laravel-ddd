<?php

namespace DDDCore;

use DDDCore\Console\Kernel;
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
        $this->app->singleton(Kernel::class, Kernel::class);
    }
}
