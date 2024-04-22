<?php

namespace DDDCore\Providers;

use DDDCore\Supports\JWT;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * @class AbstractServiceProvider
 * @package DDDCore\Providers
 */
abstract class AbstractServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return void
     */
    abstract public function boot();

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerJwtProvider();
    }

    /**
     * Register the JWT provider.
     */
    protected function registerJwtProvider(): void
    {
        $this->app->singleton('JWTAuth', function () {
            return new JWT();
        });
    }

}
