<?php
namespace Dohnall\LaravelEav\Models\Types;

use Dohnall\LaravelEav\Models\Option;
use Dohnall\LaravelEav\Models\Value;

class Integer extends Value
{
    protected $table = 'eav_entity_integer';

    public function options() {
        return $this->hasMany('Dohnall\\LaravelEav\\Models\\Option', 'attribute_id', 'attribute_id');
    }

    public function getValue($attributeId, $entityId, $langId) {
        $option = $this->where('attribute_id', $attributeId)
                    ->where('entity_id', $entityId)
                    ->where('lang_id', $langId)
                    ->first();
        if($option && count($option->options()->get()) > 0) {
            return $option->options()->where('id', $option->value)->first()->value()->where('lang_id', $langId)->first() ?? null;
        } else {
            return $option->value ?? null;
        }
    }

    public function getValues($attributeId, $entityId, $langId) {
        $options = $this->where('attribute_id', $attributeId)
                    ->where('entity_id', $entityId)
                    ->where('lang_id', $langId)
                    ->get();
        if(count($options) > 0) {
            $return = [];
            foreach($options as $row) {
                $return[] = $row->options()->where('id', $row->value)->first()->value()->where('lang_id', $langId)->first();
            }
            return $return;
        } else {
            return parent::getValues($attributeId, $entityId, $langId);
        }
    }

    protected function formatValue($value) {
        return (int) $value;
    }
}
