<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    public function borrow(Book $book)
    {
        if ($book->quantity > 0) {
            Borrow::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'borrow_date' => now(),
                'return_date' => null,
            ]);

            $book->decrement('quantity');

            return redirect()->back()->with('success', 'Book borrowed successfully.');
        }

        return redirect()->back()->with('error', 'Book not available.');
    }

    public function returnBook(Borrow $borrow)
    {
        $borrow->update(['return_date' => now()]);
        $borrow->book->increment('quantity');

        return redirect()->back()->with('success', 'Book borrowed successfully.');
    }
}
