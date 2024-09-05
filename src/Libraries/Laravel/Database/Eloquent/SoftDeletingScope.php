<?php

namespace DDDCore\Libraries\Laravel\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope as LaravelSoftDeletingScope;

/**
 * @class SoftDeletingScope
 * @package D
 */
class SoftDeletingScope extends LaravelSoftDeletingScope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model):void
    {
        $builder->where([$model->getQualifiedDeletedByColumn() => 0]);
    }

    /**
     * @inheritDoc
     *
     * @param  Builder  $builder
     * @return void
     */
    public function extend(Builder $builder):void
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }

        $builder->onDelete(function (Builder $builder) {
            return $builder->update([
                $this->getDeletedAtColumn($builder) => $builder->getModel()->freshTimestampString(),
                $this->getDeletedByColumn($builder) => $builder->getModel()->getDeletedByUser()
            ]);
        });
    }

    /**
     * @inheritDoc
     *
     * @param  Builder  $builder
     * @return void
     */
    protected function addRestore(Builder $builder):void
    {
        $builder->macro('restore', function (Builder $builder) {
            $builder->withTrashed();

            return $builder->update([
                $builder->getModel()->getDeletedAtColumn() => null,
                $builder->getModel()->getDeletedByColumn() => 0
            ]);
        });
    }

    /**
     * @inheritDoc
     *
     * @param  Builder  $builder
     * @return void
     */
    public function addOnlyTrashed(Builder $builder)
    {
        $builder->macro('onlyTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where($model->getQualifiedDeletedByColumn(), '>', 0);

            return $builder;
        });
    }


    public function getDeletedByColumn(Builder $builder)
    {
        if (count((array) $builder->getQuery()->joins) > 0) {
            return $builder->getModel()->getQualifiedDeletedByColumn();
        }

        return $builder->getModel()->getDeletedByColumn();
    }
}
