<?php
namespace Dohnall\LaravelEav\Models;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $guarded = [];

    protected $entityAttribute;
    protected $entityId;

    public function attribute() {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }

    public function setEntityAttribute(Attribute $entityAttribute) {
        $this->entityAttribute = $entityAttribute;
    }

    public function setEntityId(int $entityId) {
        $this->entityId = $entityId;
    }

}
