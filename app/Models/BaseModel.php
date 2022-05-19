<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;

class BaseModel extends Blueprint
{ 
  
  const DELETED_FLAG = 'deleted_flag';
  const CREATED_BY = 'created_by';
  const UPDATED_BY = 'updated_by';

    // $this->boolean('deleted_flag')->nullable();
    // $this->timestamp('created_at')->nullable();
    // $this->timestamp('updated_at')->nullable();
    // $this->integer('created_by')->nullable();
    // $this->timestamp('updated_by')->nullable();

}