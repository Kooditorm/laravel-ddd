<?php

namespace DDDCore\Libraries\Laravel\Database\Schema\Grammars;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\MySqlGrammar as LaravelMySqlGrammar;
use Illuminate\Support\Fluent;

/**
 * @class MySqlGrammar
 * @package DDDCore\Libraries\Laravel\Database\Schema\Grammars
 */
class MySqlGrammar extends LaravelMySqlGrammar
{
    /**
     * Compile a table comment command.
     *
     * @param  Blueprint  $blueprint
     * @param  Fluent  $command
     * @param  Connection  $connection
     * @return string
     */
    public function compileTableComment(Blueprint $blueprint, Fluent $command, Connection $connection): string
    {
        return "alter table {$this->wrapTable($blueprint)} comment '{$command->comment}'";
    }
}
