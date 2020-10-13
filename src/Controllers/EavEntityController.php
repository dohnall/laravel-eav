<?php

namespace Dohnall\LaravelEav\Controllers;

use App\Http\Controllers\Controller;
use Dohnall\LaravelEav\Models\EntityType;

class EavEntityController extends Controller
{
    public function store() {
        $data = request()->validate([
            'name' => 'required|unique:eav_entity_types',
            'slug' => 'required|unique:eav_entity_types',
            'entity_table' => 'required',
        ]);

        $entity = EntityType::create($data);

        return response([
            'entity' => $entity,
            'status' => 'ok',
        ]);
    }

    public function update(EntityType $entity) {
        $entity->update(request()->all());
    }

    public function destroy(EntityType $entity) {
        $entity->delete();
    }
}
