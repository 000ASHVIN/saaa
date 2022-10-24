<?php

namespace App;

use App\Users\User;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use App\ActivityLog;
use App\Traits\ActivityTrait;

class Company extends Model implements SluggableInterface
{
    use SluggableTrait,ActivityTrait;

    protected $table = 'companies';
    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
    ];

    protected static function boot()
    {
        parent::boot();
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                $ActivityLog = ActivityLog::create([
                    'user_id'=> (auth()->check())?auth()->user()->id:0,
                    'model'=> get_class($model),
                    'model_id'=>$model->id,
                    'action_by'=> 'manually',
                    'action'=> $event,
                    'data'=> json_encode(request()->all()),
                    'request_url'=> request()->path()
                ]);
            });
        }
        static::creating(function ($company) {
            $secret = $company->getNextSecret();
            $company->secret = str_random(6).$secret;
        });
    }

    protected $fillable = [
        'city',
        'title',
        'email',
        'secret',
        'country',
        'province',
        'area_code',
        'company_vat',
        'description',
        'address_line_two',
        'address_line_one',
        'plan_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->belongsToMany(User::class)->distinct();
    }

    public function getNextSecret()
    {
        $latest = static::latest('id')->first();
        if ($latest) {
            $ref = (int)$latest->reference += 1;
            return str_pad('Comp'.$ref, 6, '0', STR_PAD_LEFT);
        } else {
            return str_pad(1, 6, '0', STR_PAD_LEFT);
        }
    }

    public function invites()
    {
        return $this->hasMany(Invite::class)->orderBy('created_at', 'desc');
    }

    public function admin()
    {
        return User::where('id', $this->user_id)->first();
    }
}
