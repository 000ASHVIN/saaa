<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Users\User;
use App\Blog\Category;
use App\Traits\ActivityTrait;

class Thread extends Model implements SluggableInterface
{
    use SoftDeletes;
    use SluggableTrait,ActivityTrait;
    protected $table = 'threads';
    protected $guarded = [];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
    ];

    public function addReply()
    {
        $replies = $this->replies;
        $this->update(['replies' => $replies + 1]);
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class);
    }

    public function agentGroup()
    {
        return $this->belongsTo(AgentGroup::class);
    }

    public function getpriorityTextAttribute() {
        $priorities = getThreadPriorities();
        if(isset($priorities[$this->attributes['priority']])) {
            return $priorities[$this->attributes['priority']];
        }
        return 'N/A';
    }

    public function getstatusTextAttribute() {
        $statuses = getThreadStatuses();
        if(isset($statuses[$this->attributes['status']])) {
            return $statuses[$this->attributes['status']];
        }
        return 'N/A';
    }

    public function cat() {
        return $this->belongsTo(Category::class, 'category', 'id');
    }
    
}
