<?php

namespace Dohnall\LaravelEav\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'eav_attributes';
    protected $guarded = [];

    public function entityType()
    {
        return $this->belongsTo(EntityType::class, 'entity_type_id', 'id');
    }
}
