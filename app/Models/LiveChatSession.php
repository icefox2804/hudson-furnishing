<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveChatSession extends Model
{
    use HasFactory;

    protected $fillable = ['session_id', 'username'];

    public function messages()
    {
        return $this->hasMany(LiveChatMessage::class, 'session_id', 'session_id');
    }
}
