<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intent extends Model
{
    protected $fillable = [
        'dialogflow_id',
        'display_name',
        'description',
        'priority',
        'is_fallback',
        'training_phrases',
        'responses',
        'events',
        'input_contexts',
        'output_contexts',
        'webhook_enabled',
        'action',
        'synced',
        'last_synced_at',
        'usage_count'
    ];

    protected $casts = [
        'training_phrases' => 'array',
        'responses' => 'array',
        'events' => 'array',
        'input_contexts' => 'array',
        'output_contexts' => 'array',
        'webhook_enabled' => 'boolean',
        'synced' => 'boolean',
        'is_fallback' => 'boolean',
        'last_synced_at' => 'datetime'
    ];
}
