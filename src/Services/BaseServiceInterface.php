<?php

namespace DDDCore\Services;

/**
 * @interface BaseServiceInterface
 * @package DDDCore\Services
 */
interface BaseServiceInterface
{
    /**
     * 列表
     * @return mixed
     */
    public function index();

    /**
     * 详情
     * @return mixed
     */
    public function info();

    /**
     * 创建信息
     * @return mixed
     */
    public function create();

    /**
     * 更新信息
     * @return mixed
     */
    public function update();

    /**
     * 删除信息
     * @return mixed
     */
    public function remove();
}
