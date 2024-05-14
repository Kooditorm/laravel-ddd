<?php

namespace DDDCore\Providers;

use DDDCore\Console\Commands\CrontabCommand;
use DDDCore\Console\Commands\InitCommand;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

/**
 * @class ArtisanServiceProvider
 * @package DDDCore\Providers
 */
class ArtisanServiceProvider extends ServiceProvider implements DeferrableProvider
{
    protected array $commands = [
        'Crontab'    => 'command.crontab',
        'Init'       => 'command.init',
        'Initialize' => 'command.ddd.init'

    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        foreach (array_keys($this->commands) as $command) {
            $this->{"register{$command}Command"}();
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Register Crontab command.
     *
     * @return void
     */
    protected function registerCrontabCommand(): void
    {
        $this->app->singleton('command.crontab', function () {
            return new CrontabCommand();
        });

    }

    /**
     * Register Init command.
     *
     * @return void
     */
    protected function registerInitCommand(): void
    {
        $this->app->singleton('command.init', function ($app) {
            $config = $app['config'] ?? [];
            $dddConf = $config['ddd'] ?? [];
            return new InitCommand($dddConf);
        });
    }


    /**
     * Register Initialize command.
     *
     * @return void
     */
    protected function registerInitializeCommand(): void
    {
        $this->app->singleton('command.ddd.init', function ($app) {
            $config = $app['config'] ?? [];
            $dddConf = $config['ddd'] ?? [];
            return new InitCommand($dddConf,'init');
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
