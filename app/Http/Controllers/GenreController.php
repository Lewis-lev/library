<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the genres.
     */
    public function index()
    {
        $genres = Genre::all();
        return response()->json($genres);
    }

    /**
     * Store a newly created genre in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:genres,name|max:255',
        ]);

        $genre = Genre::create($data);

        return response()->json($genre, 201);
    }

    /**
     * Display the specified genre.
     */
    public function show($id)
    {
        $genre = Genre::findOrFail($id);
        return response()->json($genre);
    }

    /**
     * Update the specified genre in storage.
     */
    public function update(Request $request, $id)
    {
        $genre = Genre::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|unique:genres,name,' . $id . '|max:255',
        ]);

        $genre->update($data);

        return response()->json($genre);
    }

    /**
     * Remove the specified genre from storage.
     */
    public function destroy($id)
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();

        return response()->json(['message' => 'Genre deleted successfully']);
    }
}
