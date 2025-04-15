<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

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

        $trendingBooks = Book::where('genre', 'Trending')->get();
        $classicBooks = Book::where('genre', 'Classic')->get();

        return view('books.index', compact('trendingBooks', 'classicBooks'));
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
            'genre' => 'required',
            'quantity' => 'required|integer',
        ]);

        Book::create($request->all());

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
            'genre' => 'required',
            'quantity' => 'required|integer',
        ]);

        $book->udpate($request->all());

        return redirect()->route('books.index')->with('success', 'Book update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')->with('Success', 'Book deletes successfully.');
    }
}
