<?php

namespace App\Http\Controllers;

use App\Events\BookBorrowed;
use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    public function index()
    {
        $borrows = Borrow::with(['user', 'book'])->get();
        return view('books.borrow', compact('borrows'));
    }
    public function borrow(Book $book)
    {
        $user = Auth::user();

        // Prevent duplicate pending borrow
        $hasPending = Borrow::where('user_id', $user->user_id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return redirect()->back()->with('error', 'You already have a pending borrow request. Please wait until it is approved or rejected before borrowing another book.');
        }

        if ($book->quantity < 1) {
            return redirect()->back()->with('error', 'Sorry, this book is currently not available.');
        }

        Borrow::create([
            'user_id' => $user->user_id,
            'book_id' => $book->book_id,
            'status' => 'pending',
            'borrow_code' => 'BR-' . strtoupper(Str::random(6)),
            'borrow_date' => now(),
            'return_date' => null,
        ]);

        event(new BookBorrowed($user, $book));

        return redirect()->route('books.index')->with('success', 'Borrow request sent!');
    }

    public function approve(Borrow $borrow)
    {
        if ($borrow->status === 'pending') {
            // Decrease quantity for the approved borrow
            $borrow->status = 'approved';
            $borrow->borrow_date = now();
            $borrow->save();

            if ($borrow->book && $borrow->book->quantity > 0) {
                $borrow->book->decrement('quantity');
            }
        }
        return back()->with('success', 'Borrow request approved.');
    }

    public function reject(Borrow $borrow)
    {
        if ($borrow->status === 'pending') {
            $borrow->status = 'rejected';
            $borrow->save();
        }
        return back()->with('success', 'Borrow request rejected.');
    }

    public function return(Borrow $borrow)
    {
        if ($borrow->status === 'approved') {
            $borrow->status = 'returned';
            $borrow->return_date = now();
            $borrow->save();

            // Increase back the quantity on return
            if ($borrow->book) {
                $borrow->book->increment('quantity');
            }
        }
        return back()->with('success', 'Book successfully returned.');
    }

    public function returnBook(Borrow $borrow)
    {
        $borrow->update(['return_date' => now()]);
        $borrow->book->increment('quantity');

        return redirect()->back()->with('success', 'Book borrowed successfully.');
    }

    public function destroy($id)
    {
        Borrow::where('borrow_id', $id)->delete();
        return back()->with('success', 'Borrow record deleted.');
    }

    public function deleteAll()
    {
        Borrow::truncate();
        return back()->with('success', 'All borrow records deleted.');
    }

    public function history()
    {
        return view('books.history');
    }
}
