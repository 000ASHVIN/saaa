<?php

namespace App\Assessments;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ActivityTrait;
use App\ActivityLog;
use App\Assessment;
use App\AppEvents\Ticket;
use App\Video;

class Attempt extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                $assessment = $model->assessment; 
                $ActivityLog = ActivityLog::create([
                    'user_id'=> (auth()->check())?auth()->user()->id:0,
                    'model'=> get_class($assessment),
                    'model_id'=>$assessment->id,
                    'action_by'=> 'manually',
                    'action'=> $event,
                    'data'=> json_encode(request()->all()),
                    'request_url'=> request()->path()
                ]);
            });
        }

        static::updated(function ($attempt) {    
            $attempt->updateTickets();
            $attempt->updateVideos();
        });
    }
    
    public function setUser($user)
    {
        return $this->user()->associate($user);
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function setAssessment($assessment)
    {
        return $this->assessment()->associate($assessment);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function addAnswer($answer)
    {
        return $this->answers()->save($answer);
    }

    public function updateTickets() {
        $attempt = $this;
        if($attempt->passed) {

            $assessment = $attempt->assessment;
            $user = $attempt->user;
            if($assessment) {

                $events = $assessment->events;
                $event_ids = $events->pluck('id');
                $tickets = Ticket::where('user_id', $user->id)
                    ->whereIn('event_id', $event_ids)
                    ->get();
                foreach($tickets as $ticket) {
                    $ticket->calculateEventComplete();
                }

            }

        }
    }

    public function updateVideos() {
        $attempt = $this;
        if($attempt->passed) {

            $assessment = $attempt->assessment;
            $user = $attempt->user;
            if($assessment) {

                $videos = $assessment->videos;
                $video_ids = $videos->pluck('id');
                $videos = Video::whereIn('id', $video_ids)
                    ->get();
                foreach($videos as $video) {
                    $video->calculateWebinarComplete($user);
                }

            }

        }
    }

}
