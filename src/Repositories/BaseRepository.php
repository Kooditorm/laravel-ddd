<?php

namespace DDDCore\Repositories;

use DDDCore\Facades\JWTAuth;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use JsonException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository as RootRepository;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * @class BaseRepository
 * @package DDDCore\Repositories
 */
abstract class BaseRepository extends RootRepository implements BaseRepositoryInterface
{
    /**
     * The name of the "created at" column.
     *
     * @var string|null
     */
    public const CREATED_BY = 'created_by';

    /**
     * The name of the "updated by" column.
     *
     * @var string|null
     */
    public const UPDATED_BY = 'updated_by';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected string $primaryKey;

    /**
     * Current user information
     *
     * @var array
     */
    protected array $user = [];

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->user       = $this->user();
        $this->primaryKey = $this->model->getKeyName();
    }

    public function alias(string $alias): self
    {
        $table = $this->model->getTable();
        $table .= ' as '.$alias;
        $this->model->setTable($table);
        return $this;
    }

    /**
     * Boot up the repository, pushing criteria
     * @throws RepositoryException
     */
    public function boot(): void
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @inheritdoc
     *
     * @param  array  $attributes
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function create(array $attributes)
    {
        if (!isset($attributes[self::CREATED_BY])) {
            $attributes[self::CREATED_BY] = $this->user[$this->primaryKey] ?? 0;
        }
        if (!isset($attributes[self::UPDATED_BY])) {
            $attributes[self::UPDATED_BY] = $this->user[$this->primaryKey] ?? 0;
        }
        return parent::create($attributes);
    }


    /**
     * @inheritdoc
     *
     * @param  array  $data
     * @return mixed
     * @throws RepositoryException
     */
    public function batchCreate(array $data)
    {
        $data = collect($data)->map(function ($datum) {
            $datum[Model::CREATED_AT] = $this->model->freshTimestampString();
            $datum[Model::UPDATED_AT] = $this->model->freshTimestampString();
            $datum[self::CREATED_BY]  = $this->user[$this->primaryKey] ?? 0;
            $datum[self::UPDATED_BY]  = $this->user[$this->primaryKey] ?? 0;
            return $datum;
        })->toArray();
        return $this->model->insert($data);
    }


    /**
     * @inheritdoc
     *
     * @param  array  $attributes
     * @param $id
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function update(array $attributes, $id)
    {
        if (!isset($attributes[self::UPDATED_BY])) {
            $attributes[self::UPDATED_BY] = $this->user[$this->primaryKey] ?? 0;
        }
        return parent::update($attributes, $id);
    }


    /**
     * @inheritdoc
     *
     * @param  array  $data
     * @param  string  $whenField
     * @param  string  $whereField
     * @return int
     */
    public function batchUpdate(array $data, string $whenField = 'id', string $whereField = 'id'): int
    {
        $table = $this->model->getTable();

        $collect = collect($data)->map(function ($datum) {
            $datum[Model::UPDATED_AT] = $this->model->freshTimestampString();
            $datum[self::UPDATED_BY]  = $this->user[$this->primaryKey] ?? 0;
            return $datum;
        });

        if ($collect->pluck($whenField)->isEmpty() || $collect->pluck($whereField)->isEmpty()) {
            throw new InvalidArgumentException('argument 1 don\'t have field '.$whenField);
        }

        $when = [];

        foreach ($collect->all() as $sets) {
            $whenValue = $sets[$whereField];
            foreach ($sets as $filed => $set) {
                if ($filed === $whenField) {
                    continue;
                }
                if (is_null($set)) {
                    $set = 'null';
                }
                $when[$filed][] = "when {$whenField} = '{$whenValue}' then '{$set}'";
            }
        }

        $build = DB::table($table)->whereIn($whereField, $collect->pluck($whereField));

        foreach ($when as &$item) {
            $item = DB::raw("case ".implode(' ', $item).' end ');
        }

        return $build->update($when);
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function getSuperior(array $where, array $fields = [], string $childFiled = 'id', string $parentField = 'parent_id'): array
    {
        $rtn = [];

        if (!empty($where)) {
            $field = array_merge([$childFiled, $parentField], $fields);

            $field = collect($field)->map(function ($item) {
                return 'tab.'.$item;
            })->toArray();

            $field = implode(',', $field);

            $whereArr = [];
            foreach ($where as $k => $v) {
                if (is_string($k)) {
                    $whereArr[] = 'tab.'.$k.' = '.$v;
                } elseif (is_numeric($k)) {
                    $whereArr[] = 'tab.'.$v[0].' '.$v[1].' '.$v[2];
                }
            }

            $whereStr = implode(' and ', $whereArr);
            $table    = $this->model->getTable();
            $mTable   = '_sup, '.$table.' as tab';
            $tabSql   = $this->model->setTable($table.' as tab')->whereRaw($whereStr)->select($field)->toSql();
            $_supSql  = $this->model->setTable(DB::raw($mTable))->whereRaw('tab.'.$childFiled.' = _sup.'.$parentField)->select($field)->toSql();
            $sql      = "with recursive _sup as ($tabSql union all $_supSql) select * from _sup";
            $res      = DB::select($sql);

            if (!empty($res)) {
                $rtn = json_decode(json_encode($res, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
            }
        }

        return $rtn;
    }

    /**
     * 获取用户信息
     *
     * @return array
     */
    public function user(): array
    {
        try {
            if (empty($this->user)) {
                $this->user = JWTAuth::user();
            }
        } catch (\Exception $exception) {
            $this->user = [];
        }

        return $this->user;
    }
}
