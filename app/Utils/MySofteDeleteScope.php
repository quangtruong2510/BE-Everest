<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use  Illuminate\Database\Eloquent\Builder;
use  Illuminate\Database\Eloquent\Model;

class MySoftDeletingScope extends SoftDeletingScope
{
  /**
   * Apply the scope to a given Eloquent query builder.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $builder
   * @param  \Illuminate\Database\Eloquent\Model  $model
   * @return void
   */
  public function apply(Builder $builder, Model $model)
  {
    $builder->whereRaw($model->getQualifiedDeletedAtColumn() . "=false");
  }

  /**
   * Extend the query builder with the needed functions.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $builder
   * @return void
   */

  public function extend(Builder $builder)
  {
    foreach ($this->extensions as $extension) {
      $this->{"add{$extension}"}($builder);
    }
    $builder->onDelete(function (Builder $builder) {
      $column = $this->getDeletedAtColumn($builder);
      return $builder->update([
        $column => true,
      ]);
    });
  }
}