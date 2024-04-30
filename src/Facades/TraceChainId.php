<?php

namespace DDDCore\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static getTraceId()
 * @method static getSpanId()
 * @method static getParentSpanId()
 *
 * @class TraceChainId
 * @package DDDCore\Facades
 */
class TraceChainId extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return 'TraceChainId';
    }
}
