<?php
namespace Dohnall\LaravelEav\Models\Types;

use Dohnall\LaravelEav\Models\Value;

class Integer extends Value
{
    protected $table = 'eav_entity_integer';

    public function options() {
        return $this->hasMany('Dohnall\\LaravelEav\\Models\\Options', 'attribute_id', 'id');
    }
}
