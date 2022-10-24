<?php

namespace App;

use App\Blog\Category;
use Illuminate\Database\Eloquent\Model;

class FaqCategories extends Model
{
    protected $table = 'faq_categories';
    protected $fillable = ['id', 'name', 'description','type'];

    public function categories() {
        return $this->hasMany('App\Blog\Category', 'faq_category_id', 'id')->orderBy('title','asc');
    }

    public function faqs_count() {
        $arrCategory = $this->get_categories($this);
        if($arrCategory) {
            if(count($arrCategory['question_ids'])) {
                return count($arrCategory['question_ids']);
            }
        }
        return 0;
    }

    // Recursive function to get category hierarchy and questions count
    protected function get_categories($folder) {

        $arrCategory=[];
        $arrCategory['model'] = $folder;
        if(get_class($folder)=="App\FaqCategories") {
            $arrCategory['id'] = 'f_'.$folder->id;
            $arrCategory['name'] = $folder->name;
            $childCategories = $folder->categories;
        }
        else {
            $arrCategory['id'] = $folder->id;
            $arrCategory['name'] = $folder->title;
            $childCategories = $folder->categories;
        }
        $arrCategory['categories'] = [];

        // If child categories exists than call recursive function
        foreach($childCategories as $cat) {
            $recursive = $this->get_categories($cat);
            if($recursive) {
                $arrCategory['categories'][] = $recursive;
            }
        }

        if(get_class($folder)=="App\FaqCategories") {
            $arrCategory['question_ids'] = [];
            $arrCategory['slug'] = '';
            $arrCategory['slugs'] = '';
        }
        else {
            // Calculate final data for all child categories
            $arrCategory['question_ids'] = $folder->faqs->pluck('id')->toArray();
            $arrCategory['slug'] = $folder->slug;
            if(!isset($arrCategory['slugs'])) {
                $arrCategory['slugs'] = $folder->slug;
            }
        }

        foreach($arrCategory['categories'] as $singleCat)  {
            $arrCategory['slugs'] .= " ".$singleCat['slugs'];
            $arrCategory['question_ids'] = array_merge($arrCategory['question_ids'], $singleCat['question_ids']);
            $arrCategory['question_ids'] = array_unique($arrCategory['question_ids']);
        }
        
        if(count($arrCategory['question_ids'])) {
            return $arrCategory;
        }
        return null;
    }

}
