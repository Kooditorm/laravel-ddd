<?php

namespace DDDCore\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ArtisanServiceProvider extends ServiceProvider implements DeferrableProvider
{
    protected array $commands = [
        'Crontab' => 'command.command.crontab'
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
}
