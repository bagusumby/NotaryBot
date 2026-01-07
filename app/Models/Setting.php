<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'operational_days',
        'start_time',
        'end_time',
        'session_duration',
    ];

    protected $casts = [
        'operational_days' => 'array',
    ];
}
