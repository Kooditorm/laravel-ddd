<?php

namespace DDDCore\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static user()
 *
 * @class JWTAuth
 * @package DDDCore\Facades
 */
class JWTAuth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'JWTAuth';
    }
}
