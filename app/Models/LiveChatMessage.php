<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['session_id', 'sender', 'message'];

    public function session()
    {
        return $this->belongsTo(LiveChatSession::class, 'session_id', 'session_id');
    }
}
