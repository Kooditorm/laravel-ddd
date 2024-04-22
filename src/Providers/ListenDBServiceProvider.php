<?php

namespace DDDCore\Providers;

use DDDCore\Console\Commands\GenerateCommand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ListenDBServiceProvider extends AbstractServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerGenerateCommand();
        $this->commands('command.gen:generate');
    }


    protected function registerGenerateCommand(): void
    {
        $this->app->singleton('command.gen:generate', function () {
            return new GenerateCommand();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {

        DB::listen(static function ($query) {
            $sql      = $query->sql;
            $bindings = $query->bindings;
            $time     = $query->time;
            foreach ($bindings as $k => $binding) {
                if ($binding instanceof \DateTime) {
                    $bindings[$k] = $binding->format('Y-m-d H:i:s');
                } elseif (is_string($binding)) {
                    $bindings[$k] = "'$binding'";
                }
            }
            if (!empty($bindings)) {
                $sql = str_replace(array('%', '?'), array('%%', '%s'), $sql);
                $sql = vsprintf($sql, $bindings);
            }

            Log::info("SQL>>".$sql, ['bindings' => $bindings, 'time' => $time]);
        });
    }
}
