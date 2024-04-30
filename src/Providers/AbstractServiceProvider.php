<?php

namespace DDDCore\Providers;

use DDDCore\Facades\TraceChainId;
use DDDCore\Supports\JWT;
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
    abstract public function boot():void;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerJwtProvider();
        $this->registerTraceChainIdProvider();
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

    protected function registerTraceChainIdProvider():void
    {
        $this->app->singleton('TraceChainId', function () {
            return new TraceChainId();
        });
    }

}
