<?php

namespace DDDCore\Providers;

use DDDCore\Libraries\Laravel\Database\Migrations\MigrationCreator;
use Illuminate\Database\MigrationServiceProvider as LaravelMigrationServiceProvider;

/**
 * @class MigrationServiceProvider
 * @package DDDCore\Providers
 */
class MigrationServiceProvider extends LaravelMigrationServiceProvider
{
    /**
     * Register the migration creator.
     *
     * @return void
     */
    protected function registerCreator(): void
    {
        $this->app->singleton('migration.creator', function ($app) {
            return new MigrationCreator($app['files'], $app->basePath('stubs'));
        });
    }
}
