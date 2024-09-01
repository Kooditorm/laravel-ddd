<?php

namespace DDDCore\VO;

use DDDCore\Supports\Tree;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JsonException;

/**
 * @class BaseVo
 * @package DDDCore\VO
 */
abstract class BaseVo implements Jsonable
{

    /**
     * 默认输出Field
     * @var array
     */
    protected array $baseFields = ['id', 'parent_id', 'created_at', 'updated_at'];

    /**
     * 默认过滤字段
     * @var array|string[]
     */
    protected array $baseFilterFields = ['created_by', 'updated_by', 'deleted_by', 'deleted_at'];

    /**
     * 是否树状数据
     * @var bool
     */
    protected bool $isTree = false;

    /**
     * 父级key
     *
     * @var string
     */
    protected string $parent_key = 'parent_id';

    /**
     * 子集key
     *
     * @var string
     */
    protected string $child_key = 'id';

    /**
     * 树状游标
     *
     * @var int
     */
    protected int $tree_cursor = 0;


    /**
     * 是否验证
     *
     * @var bool
     */
    protected bool $auth = true;

    /**
     * 是否分页列表
     * @var bool
     */
    protected bool $isPage = false;

    /**
     * 当前页数
     * @var int
     */
    private int $page = 0;

    /**
     * 每页数量
     * @var int
     */
    private int $limit = 0;

    /**
     * 总数量
     * @var int
     */
    private int $total = 0;

    /**
     * 总页数
     * @var int
     */
    private int $lastPage = 0;

    /**
     * 返回数据
     *
     * @var array
     */
    private array $data = [];

    /**
     * 获取输出转换Field
     * @return mixed
     */
    abstract public function getFields(): array;

    /**
     * 获取过滤字段列表
     * @return mixed
     */
    abstract public function getFilterFields(): array;

    /**
     * 返回处理后结构
     *
     * @param $result
     * @return array
     * @throws JsonException
     */
    public function toResult($result): array
    {
        if ($result instanceof LengthAwarePaginator) {
            $this->isPage   = true;
            $this->page     = $result->currentPage();
            $this->limit    = $result->perPage();
            $this->total    = $result->total();
            $this->lastPage = $result->lastPage();
            $result         = $result->items();
        }
        if (is_array($result) || $result instanceof Collection || $result instanceof LengthAwarePaginator) {
            $this->data = $this->transform($result);
        }

        return empty($this->data) ? $result : $this->toArray();
    }

    /**
     * 转json
     *
     * @param  int  $options
     * @return false|string
     * @throws JsonException
     */
    public function toJson($options = 0)
    {
        if ($this->isPage === true) {
            $result = [
                'page'      => $this->page,
                'per_page'  => $this->limit,
                'total'     => $this->total,
                'last_page' => $this->lastPage,
                'data'      => $this->data
            ];
            $result = json_encode($result, JSON_THROW_ON_ERROR | $options);
        } else {
            $result = json_encode($this->data, JSON_THROW_ON_ERROR | $options);
        }
        return $result;
    }

    /**
     * 转数组
     *
     * @throws JsonException
     */
    public function toArray()
    {
        $result = json_decode($this->toJson(), true, 512, JSON_THROW_ON_ERROR);
        if ($this->isTree) {
            $result = Tree::parse($result, $this->tree_cursor, $this->parent_key, $this->child_key);
        }
        return $result;
    }

    /**
     * 转换
     *
     * @param $data
     * @return array
     */
    public function transform($data): array
    {
        if (!empty($data)) {
            $transform = [];
            collect($data)->map(function ($value, $key) use (&$transform) {
                if (is_array($value) || is_object($value)) {
                    $coverVal = [];
                    collect($value)->map(function ($v, $k) use (&$coverVal) {
                        $coverVal = array_merge($coverVal, $this->getTransform($k, $v));
                    });
                    $transform[$key] = $coverVal;
                } else {
                    $transform = $this->getTransform($key, $value, $transform);
                }
            });

            $data = collect($transform)->toArray();
        }

        return $data;
    }

    /**
     * @param $key
     * @param $value
     * @param  array  $transform
     * @return array
     */
    private function getTransform($key, $value, array $transform = []): array
    {
        $filterFields = array_merge($this->baseFilterFields, $this->getFilterFields() ?? []);
        $fields       = array_merge($this->baseFields, $this->getFields() ?? []);
        if (empty($this->getFields()) || in_array($key, $fields, true)) {
            $transform[$key] = $value;
        }
        if (!empty($filterFields) && in_array($key, $filterFields, true)) {
            unset($transform[$key]);
        }

        if (isset($transform[$key]) && method_exists($this, 'get'.Str::studly($key))) {
            $transform[$key] = $this->{'get'.Str::studly($key)}($value);
        }

        return $transform;
    }


    /**
     * @param $name
     * @return mixed
     */
    public function __isset($name)
    {
        return $this->__get($name);
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->$key;
    }

}
