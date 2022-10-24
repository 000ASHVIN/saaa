<?php

namespace App\Users;

use Bican\Roles\Traits\Slugable;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Traits\RoleHasRelations;
use Bican\Roles\Contracts\RoleHasRelations as RoleHasRelationsContract;

class Role extends Model implements RoleHasRelationsContract, SluggableInterface
{
    use SluggableTrait, RoleHasRelations;

    protected $table = 'roles';
    protected $fillable = ['name', 'slug', 'description', 'level'];

    protected $sluggable = array(
        'build_from' => 'name',
        'save_to'    => 'slug',
        'on_update'  => true,
    );

    public function getAllPermissionsAttribute()
    {
        return $this->permissions->pluck('id', 'name')->toArray();
//        return $this->attributes['all_permissions'] = 'none!';
    }

    public function getNameAttribute()
    {
        return ucfirst($this->attributes['name']);
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
