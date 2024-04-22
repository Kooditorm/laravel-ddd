<?php

namespace DDDCore\Repositories;

use Illuminate\Support\Facades\DB;
use JsonException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository as RootRepository;
use Prettus\Repository\Exceptions\RepositoryException;

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
}
