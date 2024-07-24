<?php

namespace DDDCore\Providers;

use DDDCore\Console\Makers\ConstantCommand;
use DDDCore\Console\Makers\DTOCommand;
use DDDCore\Console\Makers\ExceptionCommand;
use DDDCore\Console\Makers\GenerateCommand;
use DDDCore\Console\Makers\ListenerCommand;
use DDDCore\Console\Makers\ProxyCommand;
use DDDCore\Console\Makers\RepositoryCommand;
use DDDCore\Console\Makers\ServiceCommand;
use DDDCore\Console\Makers\ValidatorCommand;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

/**
 * @class MarkerServiceProvider
 * @package DDDCore\Providers
 */
class MarkerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * The provider class names.
     *
     * @var string[]
     */
    protected array $commands = [
        'Gen'        => 'command.marker.gen',
        'Generate'   => 'command.marker.generate',
        'DTO'        => 'command.marker.dto',
        'Repository' => 'command.marker.repository',
        'Service'    => 'command.marker.service',
        'Validator'  => 'command.marker.validator',
        'Listener'   => 'command.marker.listener',
        'Constant'   => 'command.marker.constant',
        'Exception'  => 'command.marker.exception',
        'Proxy'      => 'command.marker.proxy'

    ];


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerCommands($this->commands);
    }


    /**
     * Register commands.
     *
     * @param  array  $commands
     * @return void
     */
    private function registerCommands(array $commands): void
    {
        foreach (array_keys($commands) as $command) {
            $this->{"register{$command}Command"}();
        }

        $this->commands(array_values($commands));
    }


    /**
     * Register gen command.
     *
     * @return void
     */
    private function registerGenCommand(): void
    {
        $this->app->singleton('command.marker.gen', function ($app) {
            return new GenerateCommand($app['files']);
        });
    }

    /**
     * Register generate command.
     *
     * @return void
     */
    private function registerGenerateCommand(): void
    {
        $this->app->singleton('command.marker.generate', function ($app) {
            return new GenerateCommand($app['files'], 'generate');
        });
    }

    /**
     * Register dto command.
     *
     * @return void
     */
    private function registerDTOCommand(): void
    {
        $this->app->singleton('command.marker.dto', function () {
            return new DTOCommand();
        });
    }

    /**
     * Register repository command.
     *
     * @return void
     */
    private function registerRepositoryCommand(): void
    {
        $this->app->singleton('command.marker.repository', function () {
            return new RepositoryCommand();
        });
    }

    /**
     * Register service command.
     *
     * @return void
     */
    private function registerServiceCommand(): void
    {
        $this->app->singleton('command.marker.service', function () {
            return new ServiceCommand();
        });
    }

    /**
     * Register validator command.
     *
     * @return void
     */
    private function registerValidatorCommand(): void
    {
        $this->app->singleton('command.marker.validator', function () {
            return new ValidatorCommand();
        });
    }

    /**
     * Register listener command.
     *
     * @return void
     */
    private function registerListenerCommand(): void
    {
        $this->app->singleton('command.marker.listener', function () {
            return new ListenerCommand();
        });
    }

    /**
     * Register constant command.
     *
     * @return void
     */
    private function registerConstantCommand(): void
    {
        $this->app->singleton('command.marker.constant', function () {
            return new ConstantCommand();
        });
    }

    /**
     * Register exception command.
     *
     * @return void
     */
    private function registerExceptionCommand(): void
    {
        $this->app->singleton('command.marker.exception', function () {
            return new ExceptionCommand();
        });
    }

    /**
     * Register proxy command.
     *
     * @return void
     */
    private function registerProxyCommand(): void
    {
        $this->app->singleton('command.marker.proxy', function () {
            return new ProxyCommand();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return array_values($this->commands);
    }
}
