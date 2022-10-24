<?php

namespace App;

use App\Solution;
use App\SolutionSubFolder;
use Illuminate\Database\Eloquent\Model;

class SolutionFolder extends Model
{
    protected $guarded = [];
    protected $table = 'solution_folders';

    public function solutionsubfolder()
    {
        return $this->hasMany(SolutionSubFolder::class);
    }
    public function solution()
    {
        return $this->hasMany(Solution::class,'solution_folder_id','folder_id');
    }
    public function getCount()
    {
        $count = 0;
        if($this->solutionsubfolder->count())
        {
            foreach($this->solutionsubfolder as $folder)
            {
                if($folder->categories)
                {
                    $count = $count + $folder->categories->faqs->count();
                }
            }
        }
        return $count;
    }
}
