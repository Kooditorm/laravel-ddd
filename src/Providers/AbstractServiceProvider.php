<?php

namespace DDDCore\Providers;

use Illuminate\Support\ServiceProvider;
use DDDCore\Supports\JWT;

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

    }


    protected function registerJwtProvider(): void
    {
        $this->app->singleton('JWTAuth', function () {
            return new JWT();
        });
    }


}
