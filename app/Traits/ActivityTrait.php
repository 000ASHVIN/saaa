<?php

namespace App\Traits;

use App\ActivityLog;
use Illuminate\Support\Facades\Request;

trait ActivityTrait
{
    protected static function boot()
    {   
        parent::boot();
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                $ActivityLog = ActivityLog::create([
                    'user_id'=> (auth()->check())?auth()->user()->id:0,
                    'model'=> get_class($model),
                    'model_id'=>$model->id,
                    'action_by'=> 'manually',
                    'action'=> $event,
                    'data'=> json_encode(request()->all()),
                    'request_url'=> request()->path()
                ]);
            });
        }
    }
}
