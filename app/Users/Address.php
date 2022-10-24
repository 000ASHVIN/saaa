<?php

namespace App\Users;

use App\Activities\RecordsActivity;
use App\Country;
use App\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Address
 * @package App\Users
 */
class Address extends Model
{
    use RecordsActivity, SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'line_one',
        'line_two',
        'city',
        'province',
        'country',
        'area_code',
    ];
    //protected $appends = ['fullAddress'];

    /**
     * @var array
     */
    protected static $recordEvents = ['created', 'deleted'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *
     */
    public function setPrimary()
    {
        $this->clearPrimaryAddress($this->user_id);
        $this->primary = true;
        $this->save();
    }

    /**
     * @param $user_id
     */
    protected function clearPrimaryAddress($user_id)
    {
        $addresses = $this->where('user_id', $user_id)->where('primary', 1)->get();

        foreach ($addresses as $address) {
            if ($address->primary) {
                $address->primary = false;
                $address->save();
            }
        }
    }

    public function getFullAddressAttribute()
    {
        $full = $this->line_one;
        if ($this->line_two && $this->line_two != "")
            $full .= ', ' . $this->line_two;
        if ($this->city && $this->city != "")
            $full .= ', ' . $this->city;
        if ($this->area_code && $this->area_code != "")
            $full .= ', ' . $this->area_code;
        if ($this->province && $this->province != "" && Province::byCode($this->province))
            $full .= ', ' . Province::byCode($this->province);
        if ($this->country && $this->country != "" && Country::byCode($this->country))
            $full .= ', ' . Country::byCode($this->country);

        return $full;
    }
}
