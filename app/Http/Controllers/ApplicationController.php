<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function __invoke()
    {
        return view('admin.layouts.app');
    }

    
    public function uploadFile(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);

        if ($request->file('file')) {
            $link = Storage::disk('public')->put('/images', $request->file('file'));

            // // $file = $request->file('image');
            // // $filename = $file->getClientOriginalName(); // Use the original filename or generate a unique one
            // Store the image in the 'public' disk under the 'images' directory
            // // Storage::disk('public')->put('images/' . $filename, file_get_contents($file));
            // You can save the image file path in your database if needed
            // Service::where('id', $request->id)->update(['image' => $link]);
            return response()->json(['success' => true, 'file_link' => $link]);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }

}
