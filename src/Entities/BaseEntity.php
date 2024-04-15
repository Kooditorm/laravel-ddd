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
}
