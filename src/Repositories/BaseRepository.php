<?php

namespace DDDCore\Repositories;

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
}
