<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataChange extends Model
{
    protected $fillable = [
        'action',
        'data_type',
        'data_name',
        'actor_role',
        'actor_name',
        'target_table',
        'target_id',
        'before_data',
        'after_data',
        'restored_at',
    ];

    protected $casts = [
        'before_data' => 'array',
        'after_data' => 'array',
        'restored_at' => 'datetime',
    ];
}
