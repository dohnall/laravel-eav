<?php
namespace Dohnall\LaravelEav\Traits;

use Dohnall\LaravelEav\Models\EntityType;

trait Eavable {

    public function getAttributes() {
        parent::getAttributes();
        foreach($this->getEntityType()->attributes()->get() as $attribute) {
            $this->setAttribute($attribute->slug, $this->getEntityAttribute($attribute->slug));
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
        return in_array($key, $this->getEntityType()->attributes()->get()->pluck('slug')->toArray());
    }

    protected function getEntityAttribute($key) {
        return 'some entity value';
    }

}
