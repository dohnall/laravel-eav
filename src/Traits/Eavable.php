<?php
namespace Dohnall\LaravelEav\Traits;

use Dohnall\LaravelEav\Models\Attribute;
use Dohnall\LaravelEav\Models\EntityType;
use Illuminate\Support\Facades\Cache;

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
            $this->forget($attribute->id, $this->id, self::$lang_id);
        }
    }

    public function delete() {
        foreach($this->entityAttributes as $key => $attribute) {
            $model = ucfirst($attribute->input_type);
            $attributeValue = app('Dohnall\\LaravelEav\\Models\\Types\\'.$model);
            $attributeValue->deleteEntityAttribute($attribute->id, $this->id);
            $this->forget($attribute->id, $this->id, self::$lang_id);
        }

        parent::delete();
    }

    public function setEntityLanguage($lang_id) {
        self::$lang_id = $lang_id;
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
        $value = Cache::rememberForever($this->getCacheString($attribute->id, $this->id, self::$lang_id), function() use ($attribute) {
            $model = ucfirst($attribute->input_type);
            $value = app('Dohnall\\LaravelEav\\Models\\Types\\' . $model);

            if ($attribute->collection) {
                return $value->getValues($attribute->id, $this->id, self::$lang_id);
            } else {
                return $value->getValue($attribute->id, $this->id, self::$lang_id);
            }
        });
        return $value;
    }

    protected function forget($attributeId, $entityId, $langId) {
        Cache::forget($this->getCacheString($attributeId, $entityId, $langId));
    }

    protected function getCacheString($attributeId, $entityId, $langId) {
        return 'eav:'.$attributeId.':'.$entityId.':'.$langId;
    }
}
