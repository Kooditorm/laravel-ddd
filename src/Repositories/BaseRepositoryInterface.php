<?php

namespace DDDCore\Repositories;

interface BaseRepositoryInterface
{

    /**
     * 获取上下级数据
     *
     * @param  array  $where
     * @param  array  $fields
     * @param  string  $childFiled
     * @param  string  $parentField
     * @return array
     */
    public function getSuperior(array $where, array $fields, string $childFiled, string $parentField): array;
}
