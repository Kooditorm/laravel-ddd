<?php

namespace DDDCore\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * 基本实体
 *
 * @Class BaseEntity
 * @package DDDCore\Entities
 */
abstract class BaseEntity extends Model implements Transformable
{
    use TransformableTrait, HasFactory;

    /**
     * 定义表前缀
     *
     * @var string|null
     */
    protected ?string $prefix = null;

    /**
     * @inheritDoc
     *
     */
    public function getTable(): string
    {
        if (!is_null($this->prefix)) {
            $this->getConnection()->setTablePrefix($this->prefix);
        }
        return parent::getTable();
    }


}
