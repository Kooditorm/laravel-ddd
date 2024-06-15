<?php

namespace DDDCore\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * @class RouteServiceProvider
 * @package DDDCore\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Interfaces\\Http\\Controllers';

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map():void
    {
        Route::namespace($this->namespace);
    }
}
