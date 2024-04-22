<?php
namespace DDDCore\Supports;

/**
 * @class JWT
 * @package DDDCore\Supports
 */
class JWT
{
    /** @var array  用户信息 */
    protected array $user = [];

    public function user():array
    {
        return [];
    }
}
