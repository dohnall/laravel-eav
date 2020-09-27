<?php
namespace Dohnall\LaravelEav\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Value extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function attribute() {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }

    public function getValue($attributeId, $entityId, $langId) {
        return $this->where('attribute_id', $attributeId)
                    ->where('entity_id', $entityId)
                    ->where('lang_id', $langId)
                    ->first()->value ?? null;
    }

    public function getValues($attributeId, $entityId, $langId) {
        return $this->where('attribute_id', $attributeId)
                    ->where('entity_id', $entityId)
                    ->where('lang_id', $langId)
                    ->get()->pluck('value', 'id')->toArray() ?? [];
    }

    public function saveEntityAttribute($attributeId, $entityId, $langId, $values, $original) {
        if(!is_array($values)) {
            $values = (array) $values;
        }

        if(is_null($original) || is_array($original)) {
            $this->deleteEntityAttribute($attributeId, $entityId, $langId);

            foreach($values as $value) {
                self::create([
                    'lang_id' => $langId,
                    'attribute_id' => $attributeId,
                    'entity_id' => $entityId,
                    'value' => $this->formatValue($value),
                ]);
            }
        } else {
            foreach($values as $value) {
                self::where('lang_id', $langId)
                    ->where('attribute_id', $attributeId)
                    ->where('entity_id', $entityId)
                    ->update(['value' => $this->formatValue($value)]);
            }
        }
    }

    public function deleteEntityAttribute($attributeId, $entityId = 0, $langId = 0) {
        $collection = $this->where('attribute_id', $attributeId);
        if($entityId) {
            $collection = $collection->where('entity_id', $entityId);
        }
        if($langId) {
            $collection = $collection->where('lang_id', $langId);
        }
        $collection->delete();
    }

    protected function formatValue($value) {
        return $value;
    }
}
