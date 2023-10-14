<?php

namespace App\Http\Controllers\Admin;

use App\Models\Locations;
use App\Http\Controllers\Controller;
use App\Models\Properties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocationsController extends Controller
{
    public function index()
    {
        return Locations::all();
    }


    public function store(Request $request)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        Locations::create([
            'name' => $validated['name'],
            'description' => $request->description,
            'image' => $request->image_created,
        ]);
        return response()->json(['message' => 'success']);
    }

    public function edit($id)
    {
        return Locations::findOrFail($id);
    }

    public function update(Request $request, Locations $locations)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        $locations->update([
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
            Locations::where('id', $request->id)->update(['image' => $link]);
            return response()->json(['success' => true, 'image_created' => $link]);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }

    public function destroy(Locations $locations)
    {
        $locations->delete();

        return response()->json(['success' => true], 200);
    }
}