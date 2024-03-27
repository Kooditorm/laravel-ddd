<?php

namespace DddCore\Supports\RedisCache;

interface IRedisCache
{

    /**
     * redis 配置名
     *
     * @return string
     */
    public function connection(): string;

    /**
     * redis 链接
     *
     * @return mixed
     */
    public function conn();


}
