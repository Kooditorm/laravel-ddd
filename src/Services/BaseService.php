<?php

namespace DDDCore\Services;

use DDDCore\Constants\ErrorCodeConstant;
use DDDCore\DTO\BaseDTO;
use DDDCore\Exceptions\BaseException;
use DDDCore\Repositories\BaseRepository;
use DDDCore\Traits\ScopeTrait;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * @class BaseService
 * @package DDDCore\Services
 */
class BaseService implements BaseServiceInterface
{
    use ScopeTrait;

    /** @var BaseRepository|null $repository */
    protected ?BaseRepository $repository = null;

    /** @var ?BaseDTO|null $dto */
    protected ?BaseDTO $dto = null;

    /** @var string|null $primaryKey */
    protected ?string $primaryKey = null;

    /**
     * 用户信息
     *
     * @var array
     */
    protected array $user = [];

    /**
     * 表名
     *
     * @var string
     */
    protected string $table;

    /**
     * is Tree
     * @var bool
     */
    protected bool $isTree = false;


    /**
     * @param  BaseRepository|null  $repository
     * @param  BaseDTO|null  $dto
     * @throws RepositoryException
     */
    public function __construct(?BaseRepository $repository, ?BaseDTO $dto)
    {
        $this->repository = $repository;
        $this->dto        = $dto;
        $this->primaryKey = $this->repository->makeModel()->getKeyName();
        $this->user       = $this->repository->user();

    }

    /**
     * Build query
     *
     * @return mixed
     */
    protected function builder()
    {
        return $this->repository->where([]);
    }


    /**
     * @inheritDoc
     *
     * @return mixed
     */
    public function index()
    {
        $query = $this->builder();

        if (method_exists($this, 'filter')) {
            $query = $this->filter($query);
        }

        if ($this->user['is_super']) {
            $query = $this->userScope($query);
        }

        if ($this->isTree) {
            $paginate = $query->get();
            if (!empty($paginate) && method_exists($this, 'transform')) {
                $paginate = $this->transform($paginate);
            }
        } else {
            $paginate = $query->paginate($this->dto['limit'] ?? null);
            if (!empty($paginate) && method_exists($this, 'transform')) {
                $paginate->setItems = $this->transform($paginate->items());
            }
        }

        return $paginate;
    }

    /**
     * @inheritDoc
     *
     * @return array
     * @throws BaseException
     */
    public function info(): array
    {
        return $this->getInfo();
    }

    /**
     * @inheritDoc
     *
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function create()
    {
        return $this->repository->create($this->dto->toArray());
    }

    /**
     * @inheritDoc
     *
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function update()
    {
        return $this->repository->update($this->dto->toArray(), $this->dto[$this->primaryKey]);
    }

    /**
     * @inheritDoc
     *
     * @return int
     */
    public function remove(): int
    {
        return $this->repository->delete($this->dto[$this->primaryKey]);
    }

    /**
     * @param  array  $where
     * @param  array  $field
     * @return array
     * @throws BaseException
     */
    protected function getInfo(array $where = [], array $field = []): array
    {
        try {
            $query = $this->builder();

            if (!empty($where)) {
                $query->where($where);
            }

            if ($this->user['is_super'] === false) {
                $query = $this->userScope($query);
            }

            if (empty($field)) {
                $result = $query->find($this->dto[$this->primaryKey]);
            } else {
                $result = $query->find($this->dto[$this->primaryKey], $field);
            }

            if (!empty($result)) {
                return $result->toArray();
            }

            throw new BaseException(ErrorCodeConstant::NO_FOUND);
        } catch (Exception $exception) {
            throw new BaseException(ErrorCodeConstant::NO_FOUND);
        }

    }

}
