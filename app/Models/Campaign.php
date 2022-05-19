<?php

namespace App\Models;
use App\Utils\MySoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{   
    use MySoftDeletes;
    protected $table = 'campaign';
    protected $fillable = [
        'id',
        'name',
        'status',
        'used_amount',
        'start_date',
        'end_date',
        'title',
        'budget',
        'description',
        'url',
        'bid_amount',
        'deleted_flag',
        'updated_at',
        'created_at',
        'created_by',
        'updated_by',
    ];
}