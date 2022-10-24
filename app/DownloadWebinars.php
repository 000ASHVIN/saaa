<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DownloadWebinars extends Model
{
    protected $table = 'downloaded_webinars';
    protected $fillable = [
        'user_id',
        'video_id',
        'webinar_title'
    ];
}
