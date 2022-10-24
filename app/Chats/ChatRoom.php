<?php

namespace App\Chats;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $table = 'chat_room';
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
