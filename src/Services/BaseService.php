<?php

namespace DDDCore\Services;

use DDDCore\DTO\BaseDTO;
use DDDCore\Repositories\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * @class BaseService
 * @package DDDCore\Services
 */
class BaseService implements BaseServiceInterface
{
    /** @var BaseRepository|null $repository */
    protected ?BaseRepository $repository = null;

    /** @var ?BaseDTO|null $dto */
    protected ?BaseDTO $dto = null;

    /** @var string|null $primaryKey */
    protected ?string $primaryKey = null;


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
    }


}
