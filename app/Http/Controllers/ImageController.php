<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Image::latest()
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => asset('storage/' . $image->path),
                    'label' => $image->label,
                ];
            });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'image' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg'],
                'label' => ['nullable', 'string', 'max:255'],
            ]);

            $path = $request->file('image')->store('images', 'public');

            $image = Image::create([
                'path' => $path,
                'label' => $request->label
            ]);

            return response([
                'id' => $image->id,
                'url' => asset('storage/' . $path),
                'label' => $image->label
            ], 201);
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        $image->delete();

        return response(null, 204);
    }
}
