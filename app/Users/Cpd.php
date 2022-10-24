<?php

namespace App\Users;

use App\Activities\RecordsActivity;
use App\AppEvents\Ticket;
use App\Certificate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ActivityTrait;
use App\Video;

/**
 * Class Cpd
 * @package App\Users
 */
class Cpd extends Model
{
    use SoftDeletes;
    use RecordsActivity,ActivityTrait;

    /**
     * @var string
     */
    protected $table = 'cpds';
    /**
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * @var array
     */
    protected $dates = ['date'];

    protected $appends = ['dateFormatted'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function hasCertificate()
    {
        return $this->certificate()->exists();
    }

    public function setCertificate($certificate)
    {
        return $this->certificate()->save($certificate);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    public function getDateFormattedAttribute()
    {
        return date_format($this->date, 'm/d/Y');
    }

    public function event()
    {
        if ($this->ticket && $this->ticket->event){
            return $this->ticket->event;
        }
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::updated(function ($cpd) {    
            $cpd->updateTickets();
            $cpd->updateVideos();
        });
        static::created(function ($cpd) {    
            $cpd->updateTickets();
            $cpd->updateVideos();
        });
    }

    public function updateTickets() {
        if($this->ticket) {
            $this->ticket->calculateEventComplete();
        }
    }

    public function updateVideos() {
        if($this->video && $this->user) {
            $this->video->calculateWebinarComplete($this->user);
        }
    }
}
