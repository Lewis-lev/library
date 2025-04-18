<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ImageUploadTestController extends Controller
{
    /**
     * Show the test upload form.
     */
    public function showForm()
    {
        return view('test.upload_image');
    }

    /**
     * Handle the upload POST request, convert image to webp format.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $file = $request->file('image');

        // Generate unique name
        $filename = uniqid('test_upload_') . '.webp';
        // Path relative to 'public' disk
        $relativePath = 'img/test_upload/' . $filename;
        // Full disk path for writing
        $fullPath = Storage::disk('public')->path($relativePath);

        try {
            // Get image contents and create GD resource
            $imgContent = file_get_contents($file->getRealPath());
            $gdImage = @imagecreatefromstring($imgContent);
            if (!$gdImage) {
                return back()->withErrors(['image' => 'Could not process uploaded image.']);
            }

            // Create directory if it does not exist
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            // Save as webp
            imagewebp($gdImage, $fullPath);
            imagedestroy($gdImage);

            // Save path relative to storage, for asset()
            $savedPath = $relativePath;

            return back()->with('success', 'Image uploaded and converted to .webp!')
                         ->with('image', $savedPath);
        } catch (\Exception $e) {
            return back()->withErrors(['image' => 'Image upload failed: ' . $e->getMessage()]);
        }
    }
}
