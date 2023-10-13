<?php

namespace App\Http\Controllers\Admin;

use App\Models\PropertyTypes;
use App\Http\Controllers\Controller;
use App\Models\Properties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    

    public function store(Request $request)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        PropertyTypes::create([
            'name' => $validated['name'],
            'description' => $request->description,
            'image' => $request->image_created,
        ]);
        return response()->json(['message' => 'success']);
    }

    public function edit(PropertyTypes $propertyType)
    {
        return $propertyType;
    }

    public function update(Request $request, PropertyTypes $propertyType)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        $propertyType->update([
            'name' => $validated['name'],
            'description' => $request->description,
        ]);

        return response()->json(['success' => true]);
    }

    public function uploadImage(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);

        if ($request->file('image')) {
            $link = Storage::disk('public')->put('/images', $request->file('image'));

            // // $file = $request->file('image');
            // // $filename = $file->getClientOriginalName(); // Use the original filename or generate a unique one
            // Store the image in the 'public' disk under the 'images' directory
            // // Storage::disk('public')->put('images/' . $filename, file_get_contents($file));
            // You can save the image file path in your database if needed
            PropertyTypes::where('id', $request->id)->update(['image' => $link]);
            return response()->json(['success' => true, 'image_created' => $link]);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }

    public function destroy(PropertyTypes $propertyType)
    {
        $propertyType->delete();

        return response()->json(['success' => true], 200);
    }
}
