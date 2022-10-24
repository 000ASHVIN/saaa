<?php

namespace App\AppEvents;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Section
 * @package App\AppEvents
 */
class Section extends Model {

	/**
	 * @var string
     */
	protected $table = 'sections';

	/**
	 * @var bool
     */
	public $timestamps = true;

	/**
	 * @var array
     */
	protected $fillable = [
		'order',
		'title',
		'body'
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function event()
	{
		return $this->belongsTo(Event::class);
	}

}