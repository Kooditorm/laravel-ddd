<?php

namespace App\Infrastructure\Supports\RedisCache;

class RedisCache extends BaseRedisCache
{
    public function connection(): string
    {
        return $this->connection;
    }
}
