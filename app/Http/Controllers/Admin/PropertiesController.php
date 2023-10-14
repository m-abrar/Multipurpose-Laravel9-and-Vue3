<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Properties;
use Illuminate\Http\Request;

class PropertiesController extends Controller
{
    public function index()
    {
        return Properties::query()
        ->with('type')
        ->when(request('type'), function ($query) {
            return $query->where('property_type_id', request('type'));
        })
            ->latest()
            ->paginate();
    }

    public function store()
    {
        $validated = request()->validate([
            'name' => 'required',
            'item_code' => 'required',
            'slug' => 'required',
            'property_type_id' => 'required',
            'excerpt' => 'required',
            'description' => 'required',
        ], [
            'client_id.required' => 'The client name field is required.',
        ]);

        Properties::create([
            'name' => $validated['name'],
            'item_code' => $validated['item_code'],
            'slug' => $validated['slug'],
            'property_type_id' => $validated['property_type_id'],
            'excerpt' => $validated['excerpt'],
            'description' => $validated['description'],
        ], [
            'property_type_id.required' => 'The property type field is required.',
        ]);

        return response()->json(['message' => 'success']);
    }

    public function edit(Properties $properties)
    {
        $properties['associated_amenities'] = $properties->amenities->pluck('id');
        $properties['associated_features'] = $properties->features->pluck('id');

        return $properties;
    }

    public function update(Request $request, Properties $properties)
    {
        // Define validation rules for specific fields
        $validationRules = [
            'name' => 'required',
            'item_code' => 'required',
            'slug' => 'required',
            'property_type_id' => 'required',
            'excerpt' => 'required',
            'description' => 'required',
        ];

        // Validate the specific fields
        $request->validate($validationRules, [
            'property_type_id.required' => 'The property type field is required.',
        ]);
        // Update the model with all form fields
        $properties->update($request->except(['amenities','features']));

        // Use the sync method to update the selected amenities
        $properties->amenities()->sync($request->input('amenities', []));
        // Use the sync method to update the selected features
        $properties->features()->sync($request->input('features', []));

        return response()->json(['success' => true]);
    }


    public function destroy(Properties $properties)
    {
        $properties->delete();

        return response()->json(['success' => true], 200);
    }
}
