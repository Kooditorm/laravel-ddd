<?php

namespace DDDCore\Repositories;

/**
 * @interface BaseRepositoryInterface
 * @package DDDCore\Repositories
 */
interface BaseRepositoryInterface
{


    /**
     * 批量创建
     *
     * @param  array  $data
     * @return mixed
     */
    public function batchCreate(array $data);
    /**
     * 批量更新
     *
     * @param  array  $data
     * @param  string  $whenField
     * @param  string  $whereField
     * @return mixed
     */
    public function batchUpdate(array $data, string $whenField, string $whereField);

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
