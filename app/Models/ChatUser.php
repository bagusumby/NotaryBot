<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatUser extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'session_id',
        'last_activity'
    ];

    protected $casts = [
        'last_activity' => 'datetime'
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
