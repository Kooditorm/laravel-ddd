<?php

namespace DDDCore\Libraries\Laravel\Database\Eloquent;

use Exception;
use Illuminate\Database\Eloquent\SoftDeletes as LaravelSoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * @trait SoftDeletes
 * @package DDDCore\Libraries\Laravel\Database\Eloquent
 */
trait SoftDeletes
{
    use LaravelSoftDeletes;

    public static function bootSoftDeletes(): void
    {
        static::addGlobalScope(new SoftDeletingScope);
    }

    public function getQualifiedDeletedByColumn(): string
    {
        return $this->qualifyColumn($this->getDeletedByColumn());
    }

    public function getDeletedByColumn(): string
    {
        return defined('static::DELETED_BY') ? static::DELETED_BY : 'deleted_by';
    }

    public function runSoftDelete(): void
    {
        $query = $this->setKeysForSaveQuery($this->newModelQuery());

        $time = $this->freshTimestamp();

        $columns                              = [$this->getDeletedAtColumn() => $this->fromDateTime($time)];
        $columns[$this->getDeletedByColumn()] = $this->getDeletedByUser();

        $this->{$this->getDeletedAtColumn()} = $time;
        $this->{$this->getDeletedByColumn()} = $this->getDeletedByUser();

        if ($this->timestamps && !is_null($this->getUpdatedAtColumn())) {
            $this->{$this->getUpdatedAtColumn()} = $time;

            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        $query->update($columns);

        $this->syncOriginalAttributes(array_keys($columns));

    }

    /**
     * @inheritDoc
     */
    public function restore(): bool
    {
        // If the restoring event does not return false, we will proceed with this
        // restore operation. Otherwise, we bail out so the developer will stop
        // the restore totally. We will clear the deleted timestamp and save.
        if ($this->fireModelEvent('restoring') === false) {
            return false;
        }

        $this->{$this->getDeletedAtColumn()} = null;
        $this->{$this->getDeletedByColumn()} = 0;

        // Once we have saved the model, we will fire the "restored" event so this
        // developer will do anything they need to after a restore operation is
        // totally finished. Then we will return the result of the save call.
        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('restored', false);

        return $result;
    }

    /**
     * Get the user who deleted the model.
     * @return int
     */
    public function getDeletedByUser(): int
    {
        $userId = -1;

        try {
            $user = Auth::user();
            if ($user !== null) {
                $userId = $user->id ?? -1;
            }

            return $userId;
        } catch (Exception $e) {
            return -1;
        }
    }

}
