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

        return $properties;
    }

    public function update(Request $request, Properties $properties)
    {
        $validated = request()->validate([
            'name' => 'required',
            'item_code' => 'required',
            'slug' => 'required',
            'property_type_id' => 'required',
            'excerpt' => 'required',
            'description' => 'required',
        ], [
            'property_type_id.required' => 'The property type field is required.',
        ]);
        $properties->update($validated);

        // Attach selected amenities to the property
        // $properties->amenities()->attach($request->input('amenities', []));

        // Use the sync method to update the selected amenities
        $properties->amenities()->sync($request->input('amenities', []));


        return response()->json(['success' => true]);
    }

    public function destroy(Properties $properties)
    {
        $properties->delete();

        return response()->json(['success' => true], 200);
    }
}
