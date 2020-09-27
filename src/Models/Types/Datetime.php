<?php
namespace Dohnall\LaravelEav\Models\Types;

use Dohnall\LaravelEav\Models\Value;
use Illuminate\Support\Carbon;

class Datetime extends Value
{
    protected $table = 'eav_entity_datetime';

    public function getValue($attributeId, $entityId, $langId) {
        $value = $this->where('attribute_id', $attributeId)
            ->where('entity_id', $entityId)
            ->where('lang_id', $langId)
            ->first();

        return $value ? new Carbon($value->value) : null;
    }

    public function getValues($attributeId, $entityId, $langId) {
        $return = [];
        $values = $this->where('attribute_id', $attributeId)
            ->where('entity_id', $entityId)
            ->where('lang_id', $langId)
            ->get()->pluck('value', 'id')->toArray();
        foreach($values as $id => $value) {
            $return[$id] = new Carbon($value);
        }
        return $return;
    }

    protected function formatValue($value) {
        $value = new Carbon($value);
        return $value->format('Y-m-d H:i:s');
    }
}
