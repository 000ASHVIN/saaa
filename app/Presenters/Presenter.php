<?php

namespace App\Presenters;

use App\AppEvents\Event;
use App\Traits\SEOTrait;
use App\Traits\ActivityTrait;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use App\Video;


/**
 * Class Presenter
 * @package App\Presenters
 */
class Presenter extends Model implements SluggableInterface {

    use \Rutorika\Sortable\SortableTrait,SluggableTrait,ActivityTrait,SEOTrait,SearchableTrait;

	protected $searchable = [
        'columns' => [
            'name' => 10,
        ]
	];
	
	  /**
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'fullname',
		'save_to' => 'slug',
    ];
	
	protected $Seoname = 'name';

	/**
	 * @var string`
     */
	protected $table = 'presenters';
	/**
	 * @var bool
     */
	public $timestamps = true;

	/**
	 * @var array
     */
	protected $guarded = [];
    public function getAvatarAttribute()
    {
        return $this->attributes['avatar'] ? : "http://imageshack.com/a/img924/5552/cZ1ADM.jpg";
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	public function events()
	{
		return $this->belongsToMany(Event::class);
	}
	
	public function getFullnameAttribute() {
		return $this->title . ' ' . $this->name;
	}

	public function videos()
	{
		return $this->belongsToMany(Video::class);
	}

	 // For dynamic meta title
	 public function checkMetaTitle()
	 {
		 $meta_title = '';
		 if($this->getMetaTitle()!="") {
			 $meta_title = $this->getMetaTitle();
		 }
		 else {
			 $meta_title = 'Meet Our Presenters | '. $this->attributes['name']  .' | '. config('app.name');
		 }
		 return $meta_title;
	 }
}