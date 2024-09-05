<?php

namespace DDDCore\Facades;

use DDDCore\Libraries\Laravel\Database\Schema\Grammars\MySqlGrammar;
use Illuminate\Support\Facades\Schema as LaravelSchema;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Schema\MySqlBuilder;

/**
 * @class Schema
 * @package DDDCore\Facades
 */
class Schema extends LaravelSchema
{
    /**
     * Get a schema builder instance for a connection.
     *
     * @param  string|null  $name
     * @return Builder
     */
    public static function connection($name): Builder
    {
        return self::getSchemaBuilder($name);
    }

    /**
     * Get a schema builder instance for the default connection.
     *
     * @return Builder
     */
    public static function getFacadeAccessor(): Builder
    {
        return self::getSchemaBuilder();
    }

    /**
     * @param  string|null  $name
     * @return MySqlBuilder
     */
    private static function getSchemaBuilder(string $name = null): MySqlBuilder
    {
        $connection = static::$app['db']->connection($name);
        if ($connection instanceof MySqlConnection) {
            $grammar = $connection->withTablePrefix(new MySqlGrammar());
            $connection->setSchemaGrammar($grammar);
        }

        return $connection->getSchemaBuilder();
    }
}
