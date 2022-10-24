<?php

namespace App\Assessments;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    const FILLABLE_FIELDS = [
        'guid',
        'description',
        'symbol',
        'is_correct'
    ];

    protected $fillable = self::FILLABLE_FIELDS;

    static function findByGuid($guid)
    {
        return static::where('guid', $guid)->first();
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

}
