<?php

namespace Dohnall\LaravelEav\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'eav_attributes';
    protected $guarded = [];

    public function entityType() {
        return $this->belongsTo(EntityType::class, 'entity_type_id', 'id');
    }

    public function values() {
        $model = ucfirst($this->input_type);
        return $this->hasMany('Dohnall\\LaravelEav\\Models\\Types\\'.$model, 'attribute_id', 'id');
    }
}
