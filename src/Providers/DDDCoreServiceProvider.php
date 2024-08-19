<?php

namespace DDDCore\Providers;

use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @class DDDCoreServiceProvider
 * @package DDDCore\Providers
 */
class DDDCoreServiceProvider extends AbstractServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $confPath =  dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'config';
        $path = [
            $confPath.DIRECTORY_SEPARATOR.'listen.php' => config_path('listen.php'),
        ];
        $this->publishes($path, 'config');
        foreach ($path as $k => $p) {
            [$p, $suffix] = explode('.', basename($p));
            $this->mergeConfigFrom($k, $p);
        }

        $this->DBListen();
    }

    /**
     * Listening for executing SQL statements
     *
     * @return void
     */
    private function DBListen(): void
    {
        DB::listen(static function ($query) {
            $sql      = $query->sql;
            $bindings = $query->bindings;
            $time     = $query->time;
            foreach ($bindings as $k => $binding) {
                if ($binding instanceof DateTime) {
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
