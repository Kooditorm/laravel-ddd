<?php

namespace DDDCore\VO;

class BaseTreeVo extends BaseVo
{

    /**
     * @inheritdoc
     *
     * @var bool
     */
    protected bool $isTree = true;

    public function getFields(): array
    {
        return [];
    }

    public function getFilterFields(): array
    {
        return [];
    }
}
