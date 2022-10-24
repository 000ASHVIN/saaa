<?php

namespace App\Users;

use Bican\Roles\Traits\Slugable;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Traits\PermissionHasRelations;
use Bican\Roles\Contracts\PermissionHasRelations as PermissionHasRelationsContract;

class Permission extends Model implements PermissionHasRelationsContract, SluggableInterface
{
    use SluggableTrait, PermissionHasRelations;

    protected $table = 'permissions';
    protected $fillable = ['name', 'slug', 'description', 'model'];

    protected $sluggable = array(
        'build_from' => 'name',
        'save_to'    => 'slug',
        'on_update'  => true,
    );

    public function getNameAttribute()
    {
        return strtolower($this->attributes['name']);
    }

    /**
     * Create a new model instance.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if ($connection = config('roles.connection')) {
            $this->connection = $connection;
        }
    }
}
