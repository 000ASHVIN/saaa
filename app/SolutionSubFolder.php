<?php

namespace App;

use App\Blog\Category;
use App\SolutionFolder;
use Illuminate\Database\Eloquent\Model;

class SolutionSubFolder extends Model
{
    protected $guarded = [];
    protected $table = 'solution_sub_folders';

    public function solutionfolder()
    {
        return $this->belongsTo(SolutionFolder::class);
    } 
    public function categories()
    {   
        return $this->hasOne(Category::class,'id','category');
    }
}
