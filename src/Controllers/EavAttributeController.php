<?php

namespace Dohnall\LaravelEav\Controllers;

use App\Http\Controllers\Controller;
use Dohnall\LaravelEav\Models\Attribute;

class EavAttributeController extends Controller
{
    public function store() {
        $data = request()->validate([
            'entity_type_id' => 'required',
            'name' => 'required',
            'slug' => 'required',
            'input_type' => 'required',
            'frontend_input_renderer' => 'required',
            'sort_order' => 'required',
        ]);

        $attribute = Attribute::create($data);

        return response([
            'attribute' => $attribute,
            'status' => 'ok',
        ]);
    }

    public function update(Attribute $attribute) {
        $attribute->update(request()->all());
    }

    public function destroy(Attribute $attribute) {
        $attribute->delete();
    }
}
