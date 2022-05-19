<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

trait MySoftDeletes
{
  use SoftDeletes;
  /**
   * Boot the soft deleting trait for a model.
   *
   * @return void
   */
  public static function bootSoftDeletes()
  {
    // dd("bootSoftDelete");
    static::addGlobalScope(new MySoftDeletingScope);
  }

  /**
   * Get the name of the "deleted at" column.
   *
   * @return string
   */
  public function getDeletedAtColumn()
  {
    // dd("defind column delete");
    return defined('static::DELETED_FLAG') ? static::DELETED_FLAG : 'deleted_flag';
  }

  // protected function runSoftDelete()
  // {
  //   $query = $this->setKeysForSaveQuery($this->newModelQuery());

  //   $columns[$this->getDeletedFlagColumn()] = true;
  //   $query->update($columns);
  // }

  // /**
  //  * Restore a soft-deleted model instance.
  //  *
  //  * @return bool|null
  //  */

  // public function restore()
  // {
  //   // If the restoring event does not return false, we will proceed with this
  //   // restore operation. Otherwise, we bail out so the developer will stop
  //   // the restore totally. We will clear the deleted timestamp and save.

  //   if ($this->fireModelEvent('restoring') === false) {
  //     return false;
  //   }

  //   $this->{$this->getDeletedAtColumn()} = false;

  //   // Once we have saved the model, we will fire the "restored" event so this
  //   // developer will do anything they need to after a restore operation is
  //   // totally finished. Then we will return the result of the save call.
  //   $this->exists = true;

  //   $result = $this->save();

  //   $this->fireModelEvent('restored', false);

  //   return $result;
  // }
}