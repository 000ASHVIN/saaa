<?php

namespace App\Handesk;

class UserSettings extends BaseModel
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
