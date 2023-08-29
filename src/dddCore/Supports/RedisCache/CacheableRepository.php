<?php

namespace App\Infrastructure\Supports\RedisCache;

use App\Infrastructure\Repositories\BaseRepository;
use Illuminate\Contracts\Foundation\Application;
use RuntimeException;

abstract class CacheableRepository
{
    /** @var RedisRepository|null */
    protected ?RedisRepository $cacheRepository = null;

    protected BaseRepository $repository;

    abstract public function repository();

    /**
     * @return RedisRepository
     */
    public function getCacheRepository(): RedisRepository
    {
        if (is_null($this->cacheRepository)) {
            $this->cacheRepository = app(RedisRepository::class);
            $this->cacheRepository->setRepository($this->getRepository());
        }
        return $this->cacheRepository;
    }

    /**
     * @return Application|mixed
     */
    public function getRepository()
    {
        return is_string($this->repository()) ? app($this->repository()) : $this->repository();
    }


    /**
     * 获取缓存key
     *
     * @param  string  $prefix
     * @param  string|int  $id
     * @return string
     */
    protected function getCacheKey(string $prefix, $id): string
    {
        return $prefix.":".$id;
    }

    public static function __callStatic($name, $arguments)
    {
        $name = 'get'.ucfirst($name);

        $class = static::class;
        if (method_exists($class, $name)) {
            return app($class)->$name(...$arguments);
        }

        throw new RuntimeException('The method with method name '.$name.' does not exist');
    }


}
