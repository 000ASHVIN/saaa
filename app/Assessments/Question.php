<?php

namespace App\Assessments;

use App\Assessment;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    const FILLABLE_FIELDS = [
        'guid',
        'description',
        'sort_order'
    ];
    protected $fillable = self::FILLABLE_FIELDS;

    static function findByGuid($guid)
    {
        return static::where('guid', $guid)->first();
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function correctOptions()
    {
        return $this->options()->correct();
    }
}
