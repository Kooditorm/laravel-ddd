<?php

namespace App\Infrastructure\Supports;

use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Support\Facades\Log;
use JsonException;

class Annotation
{

    protected App $app;

    protected array $bind = [];

    protected array $service = [];

    protected string $cacheKeyPrefix = 'annotation:';

    protected bool $isCache = false;

    /**
     * 环境
     *
     * @var array|string[]
     */
    protected array $environment = [
        'prod',
        'dev',
        'gray'
    ];


    protected array $retrieval = [
        'Services'     => 'Impl',
        'Repositories' => 'Eloquent'
    ];


    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function load(): void
    {
        $this->register();
    }

    public function flushed(): void
    {
        $retrieval = array_keys($this->retrieval);
        array_map(function ($ret) {
            $cacheKey = $this->getCacheKey($ret);
            $this->app['redis']->del($cacheKey);
        }, $retrieval);
        $this->register();
    }

    public function register(): void
    {
        $this->work();
    }

    public function subscribe(): array
    {
        $rtn    = [];
        $path   = domain_path();
        $domain = dirs($path, true);
        if (!empty($domain)) {
            foreach ($domain as $item) {
                $dirs  = $item.DIRECTORY_SEPARATOR.'Listeners';
                $files = files($dirs);
                if (!empty($files)) {
                    foreach ($files as $file) {
                        $subject = extract_namespace($dirs.DIRECTORY_SEPARATOR.$file).DIRECTORY_SEPARATOR.basename($file, '.php');
                        $rtn[]   = str_replace('/', '\\', $subject);
                    }
                }
            }
        }

        return $rtn;
    }

    protected function work(): void
    {
        $workPath = $this->getWorkPath();
        $dirsPath = dirs($workPath, true);
        if (!empty($dirsPath)) {
            array_map(function ($v) {
                $this->namespace($v);
            }, $dirsPath);
        }

        $this->bind();
    }


    protected function getWorkPath(): string
    {
        return domain_path();
    }

    protected function getName(string $path): string
    {
        $name = explode(DIRECTORY_SEPARATOR, $path);
        return strtolower($name[count($name) - 1]);
    }


    protected function namespace(string $path): void
    {
        $sn        = $this->getName($path);
        $retrieval = array_keys($this->retrieval);
        array_map(function ($ret) use ($path, $sn) {
            $this->cache($ret, $sn);
            if ($this->isCache === false) {
                $annotation = [];
                $filePath   = $path.DIRECTORY_SEPARATOR.$ret;
                $files      = files($filePath);
                foreach ($files as $f) {
                    $namespace = extract_namespace($filePath.DIRECTORY_SEPARATOR.$f);
                    $service   = str_replace('/', '\\', $namespace.DIRECTORY_SEPARATOR.basename($f, '.php'));
                    $impl      = [$namespace, $this->retrieval[$ret], basename($f, '.php').$this->retrieval[$ret]];
                    $implement = implode(DIRECTORY_SEPARATOR, $impl);
                    $implement = str_replace('/', '\\', $implement);

                    $annotation[$service] = $implement;
                }
                $this->cache($ret, $sn, $annotation);
            }
        }, $retrieval);
    }


    protected function bind(): void
    {
        array_walk($this->bind, function ($v, $k) {
            $this->app->singleton($k, $v);
        });
    }


    protected function cache(string $key, string $field, array $data = null): void
    {
        if (!empty($key)) {
            $cacheKey = $this->getCacheKey($key);
            try {
                $this->isCache = false;
                if (empty($data)) {
                    $dataStr       = $this->app['redis']->hget($cacheKey, $field);
                    $data          = json_decode($dataStr, true, 512, JSON_THROW_ON_ERROR);
                    $this->isCache = in_array(env('APP_ENV'), $this->environment, true);
                } else {
                    $this->app['redis']->hset($cacheKey, $field, json_encode($data, JSON_THROW_ON_ERROR));
                }
            } catch (JsonException $e) {
                Log::info('缓存异常，异常码:'.$e->getCode().', 异常信息：'.$e->getMessage());
            }

            if (!empty($data)) {
                foreach ($data as $k => $v) {
                    $this->bind[$k] = $v;
                }
            }
        }
    }

    protected function getCacheKey(string $key): string
    {
        return $this->cacheKeyPrefix.$key;
    }


}
