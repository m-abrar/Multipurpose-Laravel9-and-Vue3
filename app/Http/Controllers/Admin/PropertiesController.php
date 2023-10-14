<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Properties;
use App\Models\PropertyLineItem;
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

    public function store(Request $request, Properties $properties)
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
        $properties->create($request->except(['amenities','features','lineitems']));

        // Use the sync method to update the selected amenities
        $properties->amenities()->sync($request->input('amenities', []));
        // Use the sync method to update the selected features
        $properties->features()->sync($request->input('features', []));


        // Handle lineitems if provided
        if (isset($request['lineitems'])) {
            // Create and associate line items
            $lineItems = [];
            foreach ($request['lineitems'] as $lineitem) {
                $lineItems[] = new PropertyLineItem([
                    'name' => $lineitem['name'],
                    'value' => $lineitem['value'],
                    'value_type' => $lineitem['value_type'],
                    'apply_on' => $lineitem['apply_on'],
                    'is_required' => $lineitem['is_required'],
                    'image' => $lineitem['image'],
                    'display_order' => $lineitem['display_order'],
                ]);
            }

            $property->lineitems()->saveMany($lineItems);
        }

        return response()->json(['message' => 'success']);
    }

    public function edit(Properties $properties)
    {
        $properties->load('lineitems');
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
        $properties->update($request->except(['amenities','features','lineitems']));

        // Use the sync method to update the selected amenities
        $properties->amenities()->sync($request->input('amenities', []));
        // Use the sync method to update the selected features
        $properties->features()->sync($request->input('features', []));

        if (isset($request['lineitems'])) {
            // Delete existing line items for the property
            $properties->lineitems()->delete();
        
            // Create and associate new line items
            $lineItems = [];
            foreach ($request['lineitems'] as $lineitem) {
                $lineItems[] = new PropertyLineItem([
                    'name' => $lineitem['name'],
                    'value' => $lineitem['value'],
                    'value_type' => $lineitem['value_type'],
                    'apply_on' => $lineitem['apply_on'],
                    'is_required' => $lineitem['is_required'],
                    'image' => $lineitem['image'],
                    'display_order' => $lineitem['display_order'],
                ]);
            }
        
            $properties->lineitems()->saveMany($lineItems);
        }

        return response()->json(['success' => true]);
    }


    public function destroy(Properties $properties)
    {
        $properties->delete();

        return response()->json(['success' => true], 200);
    }
}
