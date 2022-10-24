<?php

namespace App;

use App\Blog\Category;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use App\Traits\SEOTrait;

class FaqQuestion extends Model implements SluggableInterface
{
    use SearchableTrait,SluggableTrait,SEOTrait;

    protected $table = 'faq_questions';
    protected $fillable = ['faq_tag_id', 'question', 'answer','date'];

    protected $Seoname = 'question';

    protected $searchable = [
        'columns' => [
            'question' => 10,
            'answer' => 8,
        ]
    ];

    protected $sluggable = [
        'build_from' => 'question',
        'save_to' => 'slug',
        'max_length'=>'200'
    ];

    public function faq_tags()
    {
        return $this->belongsToMany(FaqTag::class,'tag_faq_question', 'faq_question_id','tag_id');

    }

    public function tags()
    {
        return $this->belongsTo(FaqTag::class, 'faq_tag_id');
   }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
   }

    public function sub_categories()
    {
        return $this->belongsToMany(SubCategory::class);
   }

    public function getCategoriesListAttribute()
    {
        return $this->categories->pluck('id')->toArray();
   }

    public function getSubCategoriesListAttribute()
    {
        return $this->sub_categories->pluck('id')->toArray();
    }

    // Get top level categories for the FAQ
    public function getTopLevelCategory() {

        $topCategories = collect();
        foreach($this->categories as $cat) {
            $this->collectTopLevelCategories($cat, $topCategories);
        }
        return $topCategories->unique();
    }

    // Recursive function to collect top level categories
    public function collectTopLevelCategories($cat, &$topCategories) {
        if($cat->parent_id) {
            $parentCategory = $cat->parentCategory();
            if($parentCategory) {
                $this->collectTopLevelCategories($parentCategory, $topCategories);   
            }
        }
        else {
            $topCategories->push($cat);
        }
    }

     // For dynamic meta title
     public function checkMetaTitle()
     {
         $meta_title = '';
         if($this->getMetaTitle()!="") {
             $meta_title = $this->getMetaTitle();
         }
         else {
             $meta_title = $this->attributes['question']  .' | FAQ Questions | '. config('app.name');
         }
         return $meta_title;
     }
}
