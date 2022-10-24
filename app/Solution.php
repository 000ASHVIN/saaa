<?php

namespace App;

use App\SolutionFolder;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    protected $table = 'solutions';
    protected $guarded = [];

    protected $casts = [
        'seo_data' => 'json',
        'tags' => 'array'
    ];
    public function solutionfolder()
    {
        return $this->belongsTo(SolutionFolder::class);
    } 
}
