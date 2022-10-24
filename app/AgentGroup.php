<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Users\User;
use App\Blog\Category;
use App\AgentGroup;

class AgentGroup extends Model
{
    protected $table = 'agent_group';
    protected $guarded = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agents()
    {
        return $this->belongsToMany(User::class,'agent_group_users');
    }

    public function getAgentsListAttribute()
    {
        return $this->agents->pluck('id')->toArray();
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,'agent_group_category');
    }
}
