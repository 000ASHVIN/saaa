<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportProvider extends Model
{
    protected $appends = ['instance'];
    protected $casts = [
        'validation_rules' => 'array'
    ];

    public static function getMenuEntries()
    {
        return static::isActive()->get()->pluck('menu_text', 'id')->toArray();
    }

    public function scopeIsActive($query, $active = true)
    {
        return $query->where('is_active', $active);
    }

    public function getInstanceAttribute()
    {
        $instance = app($this->model);
        $instance->setImportProvider($this);
        return $instance;
    }

    public function imports()
    {
        return $this->hasMany(Import::class);
    }
}
