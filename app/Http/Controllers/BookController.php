<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

        $books = $query->get();

        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allGenres = Genre::all();
        return view('books.create', compact('allGenres'));
    }

    /**
     * Store a newly created resource in storage, with image conversion to .webp in img/book_images.
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
            $filename = uniqid('book_') . '.webp';
            $relativePath = 'img/book_images/' . $filename;
            $fullPath = Storage::disk('public')->path($relativePath);

            try {
                $imgContent = file_get_contents($image->getRealPath());
                $gdImage = @imagecreatefromstring($imgContent);

                if ($gdImage) {
                    if (!file_exists(dirname($fullPath))) {
                        mkdir(dirname($fullPath), 0755, true);
                    }
                    imagewebp($gdImage, $fullPath);
                    imagedestroy($gdImage);
                    $imagePath = $relativePath;
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

        if ($request->has('genres')) {
            $book->genres()->attach($request->input('genres'));

            return redirect()->route('books.index')->with('success', 'Book added successfully.');
        }
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
        $allGenres = Genre::all();
        return view('books.edit', compact('book', 'allGenres'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $book->update($data);
        if ($request->has('genres')) {
            $book->genres()->sync($request->input('genres'));
        }

        $data = $request->only(['title', 'author', 'publisher', 'quantity']);

        // If a new image is uploaded, handle conversion, replace and delete old image
        if ($request->hasFile('image')) {
            // Remove previous image if exists
            if ($book->image && Storage::disk('public')->exists($book->image)) {
                Storage::disk('public')->delete($book->image);
            }

            $image = $request->file('image');
            $filename = uniqid('book_') . '.webp';
            $relativePath = 'img/book_images/' . $filename;
            $fullPath = Storage::disk('public')->path($relativePath);

            try {
                $imgContent = file_get_contents($image->getRealPath());
                $gdImage = @imagecreatefromstring($imgContent);

                if ($gdImage) {
                    if (!file_exists(dirname($fullPath))) {
                        mkdir(dirname($fullPath), 0755, true);
                    }
                    imagewebp($gdImage, $fullPath);
                    imagedestroy($gdImage);
                    $data['image'] = $relativePath;
                } else {
                    return redirect()->back()->withErrors(['image' => 'Failed to process image. Please upload a valid image file.']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['image' => 'Image upload failed: ' . $e->getMessage()]);
            }
        }

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // Delete associated image file if exists
        if ($book->image && Storage::disk('public')->exists($book->image)) {
            Storage::disk('public')->delete($book->image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
}
