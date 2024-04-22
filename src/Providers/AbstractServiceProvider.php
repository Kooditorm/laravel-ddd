<?php

namespace DDDCore\Providers;

use Illuminate\Support\ServiceProvider;

abstract class AbstractServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return void
     */
    abstract public function boot();
}
