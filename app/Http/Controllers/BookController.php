<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Genre;
use App\Models\Borrow;
use App\Events\BookBorrowed;
use App\Notifications\BookBorrowedNotification;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

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

    public function dashboard()
    {
        $user = Auth::user();

        // Use the correct user id column (user_id), adjust if your PK/column differs!
        $userKey = property_exists($user, 'user_id') ? $user->user_id : $user->user_id;

        // 1. Current borrowed (status = approved, not yet returned)
        $currentBorrowCount = Borrow::where('user_id', $userKey)
            ->where('status', 'approved')
            ->count();

        // 2. Completed returns (status = returned, has returned_at)
        $borrowHistoryCount = Borrow::where('user_id', $userKey)
            ->where('status', 'returned')
            ->count();

        // 3. Overdue books (still borrowed and due date in the past)
        $overdueCount = Borrow::where('user_id', $userKey)
            ->where('status', 'approved')
            ->where('return_date', '<', now())
            ->count();

        // 4. Recent borrowed books (the latest 5 borrow records for this user, with books joined)
        $recentBorrows = Borrow::with('book')
            ->where('user_id', $userKey)
            ->orderByDesc('borrow_date') // Use your actual borrow date field
            ->limit(5)
            ->get();

        return view('dashboard', [
            'currentBorrowCount' => $currentBorrowCount,
            'borrowHistoryCount' => $borrowHistoryCount,
            'overdueCount' => $overdueCount,
            'recentBorrows' => $recentBorrows,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allGenres = Genre::orderBy('name')->get();
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
            'description' => 'required',
            'publisher' => 'required',
            'quantity' => 'required|integer',
            'genres' => 'sometimes|array',
            'genres.*' => 'exists:genres,genre_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = uniqid('book_') . '.webp';
            $relativePath = 'img/book_images/' . $filename;

            try {
                $imgContent = file_get_contents($image->getRealPath());
                $gdImage = @imagecreatefromstring($imgContent);

                if ($gdImage) {
                    // Convert to .webp in-memory
                    ob_start();
                    imagewebp($gdImage, null, 80); // 80 quality (adjust if needed)
                    $webpData = ob_get_clean();
                    imagedestroy($gdImage);

                    if (!$webpData) {
                        return redirect()->back()->withErrors(['image' => 'Failed to process image. Please upload a valid image file.']);
                    }

                    // Store to R2 using Storage facade
                    Storage::disk('r2')->put($relativePath, $webpData, 'public');
                } else {
                    return redirect()->back()->withErrors(['image' => 'Failed to process image. Please upload a valid image file.']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['image' => 'Image upload failed: ' . $e->getMessage()]);
            }
        }

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'description' => $request->description,
            'publisher' => $request->publisher,
            'quantity' => $request->quantity,
            'code' => 'BK-' . strtoupper(Str::random(6)),
            'image' => $filename,
        ]);

        $genreIds = array_unique(array_filter($request->input('genres', [])));

        if (!empty($genreIds)) {
            $book->genres()->sync($genreIds); //<-- use sync instead of attach!
        }

        return redirect()->route('books.index')->with('success', 'Book added successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::with('genres')->findOrFail($id); // eager-load as in index
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $allGenres = Genre::orderBy('name')->get();
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
            'description' => 'required',
            'publisher' => 'required',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['title', 'author', 'description', 'publisher', 'quantity']);

        // Update genres if provided
        if ($request->has('genres')) {
            $book->genres()->sync($request->input('genres', []));
        }

        // If a new image is uploaded, convert and upload to R2, delete the old image from R2
        if ($request->hasFile('image')) {
            // Remove previous image if it exists on R2
            if ($book->image && Storage::disk('r2')->exists('img/book_images/' . $book->image)) {
                Storage::disk('r2')->delete('img/book_images/' . $book->image);
            }

            $image = $request->file('image');
            $filename = uniqid('book_') . '.webp';
            $relativePath = 'img/book_images/' . $filename;

            try {
                $imgContent = file_get_contents($image->getRealPath());
                $gdImage = @imagecreatefromstring($imgContent);

                if ($gdImage) {
                    ob_start();
                    imagewebp($gdImage, null, 80); // 80 quality (adjust if needed)
                    $webpData = ob_get_clean();
                    imagedestroy($gdImage);

                    if (!$webpData) {
                        return redirect()->back()->withErrors(['image' => 'Failed to process image. Please upload a valid image file.']);
                    }

                    // Store to R2
                    Storage::disk('r2')->put($relativePath, $webpData, 'public');
                    $data['image'] = $filename;
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
        // Delete associated image file from R2 if exists
        if ($book->image && Storage::disk('r2')->exists('img/book_images/' . $book->image)) {
            Storage::disk('r2')->delete('img/book_images/' . $book->image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }

}
