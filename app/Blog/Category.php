<?php

namespace App\Blog;

use App\AppEvents\Event;
use App\FaqQuestion;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use App\Video;
use App\AgentGroup;
use App\Traits\ActivityTrait;
use Carbon\Carbon;

class Category extends Model implements SluggableInterface
{
    use SluggableTrait;
    use ActivityTrait;
    
    protected $table = 'categories';
    protected $guarded = [];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class)->where('draft', false)->orderBy('id', 'Desc');
    }

    public function publishedPosts()
    {
        return $this->belongsToMany(Post::class)->where('draft', false)->where('posts.publish_date','<=',Carbon::now(env('SOUTHAFRICA_TIMEZONE_NAME')))->orderBy('id', 'Desc');
    }

    public function faqs()
    {
        return $this->belongsToMany(FaqQuestion::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function categories(){
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function childCategory()
    {
        return Category::where('parent_id',$this->id)->orderBy('title', 'asc')->get();
    }
    
    public function parentCategory()
    {
        return Category::where('id',$this->parent_id)->first();
    }
    public function parent() {
        return $this->belongsTo(Category::class,'parent_id','id');
    }
    public function getCount()
    {
        $category = $this->childCategory();
        $count = 0;
        foreach($category as $cat)
        {
            $count +=$cat->faqs()->count();
        }
        return $count;
    }
    public function videos()
    {
        return $this->hasMany(Video::class, 'category', 'id');
    }

    public function agentGroups()
    {
        return $this->belongsToMany(AgentGroup::class, 'agent_group_category');
    }

}
