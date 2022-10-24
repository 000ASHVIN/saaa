<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class SuspendNotification extends Model
{
    protected $table = 'suspended_notification';
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
