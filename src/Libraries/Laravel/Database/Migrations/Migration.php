<?php

namespace DDDCore\Libraries\Laravel\Database\Migrations;

use Illuminate\Database\Migrations\Migration as LaravelMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class Migration
 * @package DDDCore\Libraries\Laravel\Database\Migrations
 */
class Migration extends LaravelMigration
{


    public function createTimestamps(Blueprint $blueprint, $precision = 0): void
    {
        $blueprint->bigInteger('created_by')->default(0)->comment('创建人');
        $blueprint->bigInteger('updated_by')->default(0)->comment('更新人');
        $blueprint->bigInteger('deleted_by')->default(0)->comment('删除人');
        $blueprint->dateTime('created_at', $precision)->nullable()->comment('创建时间');
        $blueprint->dateTime('updated_at', $precision)->nullable()->comment('更新时间');
        $blueprint->dateTime('deleted_at', $precision)->nullable()->comment('删除时间');
    }
}
