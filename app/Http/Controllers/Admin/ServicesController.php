<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Http\Controllers\Controller;
use App\Models\Properties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
{
    public function index()
    {
        return Service::all();
    }


    public function store(Request $request)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        Service::create([
            'name' => $validated['name'],
            'description' => $request->description,
            'image' => $request->image_created,
        ]);
        return response()->json(['message' => 'success']);
    }

    public function edit($id)
    {
        return Service::findOrFail($id);
    }

    public function update(Request $request, Service $services)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        $services->update([
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
            Service::where('id', $request->id)->update(['image' => $link]);
            return response()->json(['success' => true, 'image_created' => $link]);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }

    public function destroy(Services $services)
    {
        $services->delete();

        return response()->json(['success' => true], 200);
    }
}
