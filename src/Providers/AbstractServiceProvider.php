<?php

namespace DDDCore\Providers;

use DDDCore\Events\LogEvent;
use DDDCore\Listeners\LogListener;
use DDDCore\Middleware\LogsMiddleware;
use DDDCore\Supports\JWT;
use DDDCore\Supports\TraceLog\TraceChainUnId;
use DDDCore\Traits\EventTrait;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * @class AbstractServiceProvider
 * @package DDDCore\Providers
 */
abstract class AbstractServiceProvider extends ServiceProvider
{
    use EventTrait;

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected array $middleware = [
        LogsMiddleware::class
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected array $routeMiddleware = [];

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected array $listen = [
        LogEvent::class => [
            LogListener::class
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected array $subscribe = [];

    /**
     * Boot the service provider.
     *
     * @return void
     */
    abstract public function boot(): void;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerJwtProvider();
        $this->registerTraceChainIdProvider();
        $this->registerMiddleware();
        $this->registerEvent();
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

    protected function registerTraceChainIdProvider(): void
    {
        $this->app->singleton('TraceChainId', function () {
            return new TraceChainUnId();
        });
    }

    /**
     * Register the middleware.
     *
     * @return void
     */
    protected function registerMiddleware(): void
    {
        //注册全局中间件
        $kernel = $this->app[Kernel::class];
        foreach ($this->middleware as $middleware) {
            $kernel->pushMiddleware($middleware);
        }

        //注册路由中间件
        $router = $this->app['router'];

        $method = method_exists($router, 'aliasMiddleware') ? 'aliasMiddleware' : 'middleware';

        foreach ($this->routeMiddleware as $alias => $middleware) {
            $router->$method($alias, $middleware);
        }
    }

    /**
     * Register the event.
     *
     * @return void
     */
    protected function registerEvent(): void
    {
        $this->booting(function () {
            $events = $this->getEvents();

            foreach ($events as $event => $listeners) {
                foreach (array_unique($listeners) as $listener) {
                    Event::listen($event, $listener);
                }
            }

            foreach ($this->subscribe as $subscriber) {
                Event::subscribe($subscriber);
            }
        });
    }

}
