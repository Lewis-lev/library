<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::query();

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Sorting logic
        if ($request->sort == 'asc') {
            $query->orderBy('title', 'asc');
        } elseif ($request->sort == 'desc') {
            $query->orderBy('title', 'desc');
        } elseif ($request->sort == 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($request->sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        }

        // Use the built query, not Book::all()
        $books = $query->get();

        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = uniqid('img_') . '.webp';
            $savePath = storage_path('app/public/book_images/' . $filename);

            try {
                $imageContents = file_get_contents($image);
                $imageResource = @imagecreatefromstring($imageContents);

                if ($imageResource) {
                    imagewebp($imageResource, $savePath);
                    imagedestroy($imageResource);
                    $imagePath = 'book_images/' . $filename;
                } else {
                    return redirect()->back()->withErrors(['image' => 'Failed to process image. Please upload a valid image file.']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['image' => 'Image upload failed: ' . $e->getMessage()]);
            }
        }

        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'quantity' => $request->quantity,
            'code' => 'BK-' . strtoupper(Str::random(6)),
            'image' => $imagePath,
        ]);

        return redirect()->route('books.index')->with('success', 'Book added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'quantity' => 'required|integer',
        ]);

        // Fix typo: update, not udpate
        $book->update($request->all());

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        // Use lowercase 'success' for flash message key
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
}
