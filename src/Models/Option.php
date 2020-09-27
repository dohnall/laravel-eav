<?php
namespace Dohnall\LaravelEav\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $guarded = [];

    protected $table = 'eav_attribute_options';

    public function value() {
        return $this->hasMany(OptionValue::class, 'option_id', 'id');
    }
}
