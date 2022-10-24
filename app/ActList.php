<?php

namespace App;

use App\Traits\ActivityTrait;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class ActList extends Model
{
    use SearchableTrait,ActivityTrait;
    protected $guarded = [];
    protected $table = 'acts';

    protected $searchable = [
        'columns' => [
            'name' => 10,
        ]
    ];

    public function acts()
    {
        return $this->hasMany(Act::class, 'act_lists_id');
    }
}
