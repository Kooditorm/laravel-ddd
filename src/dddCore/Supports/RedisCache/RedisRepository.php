<?php

namespace App\Infrastructure\Supports\RedisCache;

use App\Infrastructure\Facades\RedisCache;
use App\Infrastructure\Repositories\BaseRepository;
use Closure;
use JsonException;
use Redis;
use RedisException;

class RedisRepository
{
    /** @var BaseRepository */
    protected BaseRepository $repository;

    /** @var bool */
    protected bool $refresh = false;

    public function setRepository(BaseRepository $repository): RedisRepository
    {
        $this->repository = $repository;
        return $this;
    }


    /**
     * @param  string  $key
     * @param  int  $expire
     * @param  Closure  $closure
     * @return false|mixed|Redis|string
     * @throws JsonException
     * @throws RedisException
     */
    public function remember(string $key, int $expire, Closure $closure)
    {
        $value = '';

        if ($this->refresh === false) {
            $value = RedisCache::get($key);
        }

        if (empty($value)) {
            $value = $closure($this->repository);
            if (!empty($value)) {
                $value = json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
                RedisCache::setex($key, $value, $expire);
            }
        }

        if (!empty($value) && is_string($value) && is_json($value)) {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }


        return $value;
    }

    /**
     * @param  string  $key
     * @param  string  $field
     * @param  Closure  $closure
     * @return mixed
     * @throws JsonException
     */
    public function hRemember(string $key, string $field, Closure $closure)
    {
        $value = '';
        if ($this->refresh === false) {
            $value = RedisCache::hGet($key, $field);
        }
        if (empty($value)) {
            $value = $closure($this->repository);
            if (!empty($value)) {
                if (is_array($value) || is_object($value)) {
                    $value = json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
                }
                RedisCache::hSet($key, $field, $value);
            }
        }

        if (!empty($value) && is_string($value) && is_json($value)) {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }

        return $value;
    }

    /**
     * @return $this
     */
    public function refresh(): RedisRepository
    {
        $this->refresh = true;
        return $this;
    }

}
