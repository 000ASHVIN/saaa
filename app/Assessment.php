<?php

namespace App;

use App\AppEvents\Event;
use App\Assessments\Attempt;
use App\Assessments\Question;
use App\Store\Product;
use Illuminate\Database\Eloquent\Model;
use App\Video;
use App\Traits\ActivityTrait;

class Assessment extends Model
{
    use ActivityTrait;
    const FILLABLE_FIELDS = [
        'guid',
        'title',
        'instructions',
        'cpd_hours',
        'pass_percentage',
        'time_limit_minutes',
        'maximum_attempts',
        'randomize_questions_order'
    ];

    protected $fillable = self::FILLABLE_FIELDS;

    static function findByGuid($guid)
    {
        return static::where('guid', $guid)->first();
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    public function attemptsForUser($user = null)
    {
        if (!$user)
            $user = auth()->user();

        return $this->attempts()->where('user_id', $user->id)->get();
    }
    public function getattemptsUser($user = null)
    {
        if (!$user)
            $user = auth()->user();

        return $this->attempts()->where('user_id', $user->id);
    }
    public function passedAttempts($user = null)
    {
        if ($user)
            return $this->attempts()->where('user_id', $user->id)->where('passed', true);

        return $this->attempts()->where('passed', true);
    }

    public function addAttempt($attempt)
    {
        return $this->attempts()->save($attempt);
    }

    public function hasBeenPassedByUser($user)
    {
        return $this->attempts()->where('user_id', $user->id)->where('passed', true)->count() > 0;
    }

    public function failedAttemptsForUser($user)
    {
        return $this->attempts()->where('user_id', $user->id)->where('passed', false);
    }

    public function addAttemptForUser($user)
    {
        $attempt = new Attempt(['user_id' => $user->id]);
        $this->addAttempt($attempt);
        return $attempt;
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function hasPassed($user = null)
    {
        if (!$user)
            $user = auth()->user();
        return $this->passedAttempts($user)->count() > 0;
    }

    public function remainingAttempts($user = null)
    {
        if (!$user)
            $user = auth()->user();
        $attempts = count($this->attemptsForUser($user));
        $maxAttempts = $this->maximum_attempts;
        return $maxAttempts - $attempts;
    }

    public function product()
    {
        return $this->belongsToMany(Product::class);
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'assessment_video');
    }

}
