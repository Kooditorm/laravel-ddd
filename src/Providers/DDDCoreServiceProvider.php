<?php

namespace DDDCore\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\String\s;

class DDDCoreServiceProvider extends AbstractServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->DBListen();
    }


    private function DBListen(): void
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
