<?php
namespace Dohnall\LaravelEav\Models;

use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    protected $guarded = [];

    protected $table = 'eav_attribute_option_values';

    public function option() {
        return $this->belongsTo(Option::class, 'option_id', 'id');
    }
}
