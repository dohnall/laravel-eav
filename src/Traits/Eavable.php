<?php
namespace Dohnall\LaravelEav\Traits;

use Dohnall\LaravelEav\Models\Attribute;
use Dohnall\LaravelEav\Models\EntityType;

trait Eavable {
    public $entityAttributes = [];
    public $entityAttributesToSave = [];

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

    public function setAttribute($key, $value) {
        return $this->isEntityAttribute($key) ? $this->setEntityAttribute($key, $value) : parent::setAttribute($key, $value);
    }

    public function save(array $options = []) {
        parent::save($options);

        $entityType = $this->getEntityType();
        foreach($this->entityAttributesToSave as $key => $value) {
            $attribute = Attribute::where(['entity_type_id' => $entityType->id, 'slug' => $key])->first();
            $model = ucfirst($attribute->input_type);
            $attributeValue = app('Dohnall\\LaravelEav\\Models\\Types\\'.$model);
            $attributeValue->saveEntityAttribute($attribute->id, $this->id, self::$lang_id, $value, $this->$key);
        }
    }

    public function delete() {
        foreach($this->entityAttributes as $key => $attribute) {
            $model = ucfirst($attribute->input_type);
            $attributeValue = app('Dohnall\\LaravelEav\\Models\\Types\\'.$model);
            $attributeValue->deleteEntityAttribute($attribute->id, $this->id);
        }

        parent::delete();
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

    protected function setEntityAttribute($key, $value) {
        $this->entityAttributesToSave[$key] = $value;
        return $this;
    }

    protected function getEntityAttributeValue(Attribute $attribute) {
        $model = ucfirst($attribute->input_type);
        $value = app('Dohnall\\LaravelEav\\Models\\Types\\'.$model);

        if($attribute->collection) {
            return $value->getValues($attribute->id, $this->id, self::$lang_id);
        } else {
            return $value->getValue($attribute->id, $this->id, self::$lang_id);
        }
    }
}
