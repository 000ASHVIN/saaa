<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Charge
 *
 * @package App
 */
class Charge extends Model
{
    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [
            'user_id',
            'amount',
            'gateway_reference',
            'description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
