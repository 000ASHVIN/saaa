<?php

namespace App;

use App\Users\Cpd;
use Illuminate\Database\Eloquent\Model;
use Knp\Snappy\Pdf;

class Certificate extends Model
{
    protected $guarded = ['id', 'source', 'created_at', 'updated_at'];
    protected $appends = ['source'];

    public function cpd()
    {
        return $this->belongsTo(Cpd::class);
    }

    public function getSourceWithAttribute($sourceWith)
    {
        return json_decode($sourceWith);
    }

    public function setSourceWithAttribute($sourceWith)
    {
        $this->attributes['source_with'] = json_encode($sourceWith);
    }

    public function getSourceAttribute()
    {
        return app($this->source_model)->with($this->source_with)->findOrFail($this->source_id);
    }
}
