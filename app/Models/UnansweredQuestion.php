<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnansweredQuestion extends Model
{
    protected $fillable = [
        'chat_user_id',
        'session_id',
        'question',
        'bot_response',
        'is_solved',
        'solved_by_intent_id',
        'solved_at'
    ];

    protected $casts = [
        'is_solved' => 'boolean',
        'solved_at' => 'datetime'
    ];

    public function chatUser()
    {
        return $this->belongsTo(ChatUser::class);
    }

    public function solvedByIntent()
    {
        return $this->belongsTo(Intent::class, 'solved_by_intent_id');
    }
}
