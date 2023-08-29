<?php

namespace App\Infrastructure\Supports\RedisCache;


use App\Infrastructure\Constants\CacheConstant;
use Illuminate\Support\Facades\Redis;
use RedisException;

/**
 * @class BaseRedisCache
 * @package App\Infrastructure\Supports\RedisCache
 */
abstract class BaseRedisCache implements IRedisCache
{

    public \Redis $client;

    /**
     * redis 配置名
     *
     * @var string
     */
    protected string $connection = 'default';

    /**
     * @inheritdoc
     *
     * @return string
     */
    abstract public function connection(): string;

    /**
     * 过期时间
     *
     * @var int
     */
    protected int $expire = 0;

    public function __construct()
    {
        $this->conn();
    }

    /**
     * @return mixed|\Redis
     */
    public function conn()
    {
        if (empty($this->client)) {
            $connection   = empty($this->connection()) ? $this->connection : $this->connection();
            $this->client = Redis::connection($connection)->client();
        }
        return $this->client;
    }

    /**
     * @return \Redis
     */
    public function client(): \Redis
    {
        return $this->client;
    }


    /**
     * @param  string  $key
     * @return false|mixed|\Redis|string
     * @throws RedisException
     */
    public function get(string $key)
    {
        return $this->client->get($key);
    }

    /**
     * @param  string  $key
     * @param  string  $value
     * @param  int  $expire
     * @return bool
     * @throws RedisException
     */
    public function setex(string $key, string $value, int $expire = 0): bool
    {
        $this->expire($expire);

        if (empty($this->expire)) {
            return $this->client->set($key, $value);
        }

        return $this->client->setex($key, $this->expire, $value);

    }

    /**
     * @param  string  $key
     * @param  string  $value
     * @param  int  $expire
     * @return bool
     * @throws RedisException
     */
    public function setnx(string $key, string $value, int $expire = 0): bool
    {
        $this->expire($expire);
        if (empty($this->expire)) {
            return $this->client->setnx($key, $value);
        }

        return $this->client->set($key, $value, ['nx', 'ex' => $this->expire]);

    }

    /**
     * @param  string  $key
     * @param  string  $field
     * @return false|\Redis|string
     * @throws RedisException
     */
    public function hGet(string $key, string $field = '')
    {
        if (empty($field)) {
            return $this->client->hGetAll($key);
        }

        return $this->client->hGet($key, $field);
    }

    /**
     * @param  string  $key
     * @param  string  $filed
     * @param  string  $value
     * @return bool|int|\Redis
     * @throws RedisException
     */
    public function hSet(string $key, string $filed, string $value)
    {
        return $this->client->hSet($key, $filed, $value);
    }


    /**
     * 设置过期时间
     *
     * @param  int  $expire
     * @param  int  $unit  0-秒 1-分 2-小时 3-天
     * @return $this
     */
    public function expire(int $expire, int $unit = 0): RedisCache
    {
        if (empty($this->expire) || !empty($expire)) {
            $unit = min($unit, 3);
            switch ($unit) {
                case 3:
                    $expire = (int)bcmul($expire, CacheConstant::EXPIRE_DAY);
                    break;
                case 2:
                    $expire = (int)bcmul($expire, CacheConstant::EXPIRE_HOUR);
                    break;
                case 1:
                    $expire = (int)bcmul($expire, CacheConstant::EXPIRE_MINUTE);
                    break;
            }
            $this->expire = $expire;
        }
        return $this;
    }

    public function __call($name, $arguments)
    {
        return $this->client->$name(...$arguments);
    }
}
