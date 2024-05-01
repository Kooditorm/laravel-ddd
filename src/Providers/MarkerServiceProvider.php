<?php

namespace DDDCore\Providers;

use DDDCore\Console\Makers\GenerateCommand;
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
        'Gen'      => 'command.marker.gen',
        'Generate' => 'command.marker.generate',
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
    protected function registerCommands(array $commands): void
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
    public function registerGenCommand(): void
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
    public function registerGenerateCommand(): void
    {
        $this->app->singleton('command.marker.generate', function ($app) {
            return new GenerateCommand($app['files'], 'generate');
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
