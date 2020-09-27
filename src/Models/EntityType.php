<?php

namespace Dohnall\LaravelEav\Models;

use Illuminate\Database\Eloquent\Model;

class EntityType extends Model
{
    protected $table = 'eav_entity_types';
    protected $guarded = [];

    public function attributes() {
        return $this->hasMany(Attribute::class);
    }
}
