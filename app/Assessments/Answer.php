<?php

namespace App\Assessments;

use App\Assessment;
use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
