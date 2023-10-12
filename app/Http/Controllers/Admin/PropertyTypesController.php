<?php

namespace App\Http\Controllers\Admin;

use App\Models\PropertyTypes;
use App\Http\Controllers\Controller;
use App\Models\Properties;

class PropertyTypesController extends Controller
{
    public function index()
    {
        return PropertyTypes::all();
    }
    public function getTypesWithCount()
    {
        $types = PropertyTypes::all();

        // dd($types);

        return collect($types)->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'count' => Properties::where('property_type_id', $type->id)->count(),
                'color' => 'success', //PropertyType::from($status->value)->color(),
            ];
        });
    }
}
