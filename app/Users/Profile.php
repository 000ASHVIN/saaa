<?php

namespace App\Users;

use App\Traits\ActivityTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Profile
 * @package App\Users
 */
class Profile extends Model
{
    use ActivityTrait;

    protected $fillable = [
       'about_me',
        'id_number',
        'company',
        'website',
        'avatar',
        'cell',
        'position',
        'gender',
        'tax',
        'body',
        'area',
        'province',
    ];
    /**
     * @var string
     */
    protected $table = 'profiles';

    /**
     * @var array
     */
    protected $dates = ['birthday'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
