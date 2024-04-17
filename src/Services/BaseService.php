<?php

namespace DDDCore\Services;

use DDDCore\DTO\BaseDTO;

class BaseService implements BaseServiceInterface
{

    /** @var BaseDTO $dto */
    protected BaseDTO $dto;

    public function __construct(BaseDTO $dto)
    {
        $this->dto = $dto;
    }
}
