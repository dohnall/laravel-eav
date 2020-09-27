<?php
namespace Dohnall\LaravelEav\Traits;

use Dohnall\LaravelEav\Models\Attribute;
use Dohnall\LaravelEav\Models\EntityType;
use Dohnall\LaravelEav\Models\Option;

trait Eavable {
    public $entityAttributes = [];

    public function getAttributes() {
        parent::getAttributes();
        foreach($this->getEntityType()->attributes()->get() as $attribute) {
            $this->entityAttributes[$attribute->slug] = $attribute;
        }
        return $this->attributes;
    }

    public function getAttribute($key) {
        return $this->isEntityAttribute($key) ? $this->getEntityAttribute($key) : parent::getAttribute($key);
    }

    protected function getEntityType() {
        return EntityType::where(['entity_table' => $this->getTable()])->first();
    }

    protected function isEntityAttribute($key) {
        return in_array($key, array_keys($this->entityAttributes));
    }

    protected function getEntityAttribute($key) {
        $entityType = $this->getEntityType();
        $attribute = Attribute::where(['entity_type_id' => $entityType->id, 'slug' => $key])->first();
        return $this->getEntityAttributeValue($attribute);
    }

    protected function getEntityAttributeValue(Attribute $attribute) {
        if($attribute->collection) {
            if($attribute->input_type == 'integer') {
                $options = Option::where('attribute_id', $attribute->id)->whereIn('id', $attribute->values()->where(['entity_id' => $this->id])->get()->pluck('value')->toArray())->get();
                if(count($options) > 0) {
                    $return = [];
                    foreach($options as $row) {
                        $return[] = $row->value()->where(['locale' => 'en_US'])->first();
                    }
                    return $return;
                } else {
                    return $attribute->values()->where(['entity_id' => $this->id])->get()->pluck('value', 'id')->toArray();
                }
            } else {
                return $attribute->values()->where(['entity_id' => $this->id])->get()->pluck('value', 'id')->toArray();
            }
        } else {
            if($attribute->input_type == 'integer') {
                $options = Option::where(['attribute_id' => $attribute->id, 'id' => $attribute->values()->where(['entity_id' => $this->id])->first()->value])->first();
                if($options) {
                    return $options->value()->where(['locale' => 'en_US'])->first();
                } else {
                    return $attribute->values()->where(['entity_id' => $this->id])->first()->value;
                }
            } else {
                return $attribute->values()->where(['entity_id' => $this->id])->first()->value;
            }
        }
    }
}
