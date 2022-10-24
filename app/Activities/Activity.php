<?php

namespace App\Activities;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Activity
 * @package App\Activities
 */
class Activity extends Model
{
	/**
	 * @var array
     */
	protected $fillable = [
		'subject_id',
		'subject_type',
		'name',
		'user_id'
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * @return $this
     */
	public function subject()
	{
		return $this->morphTo()->withTrashed();
	}
}
