<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImageUploadTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/books', [BookController::class, 'index'])->name('books.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('books', BookController::class)->except(['index']);
    Route::get('/borrow', [BorrowController::class, 'index'])->name('borrow.index');
    Route::put('/borrows/{borrow}/approve', [BorrowController::class, 'approve'])->name('borrows.approve');
    Route::put('/borrows/{borrow}/reject', [BorrowController::class, 'reject'])->name('borrows.reject');
    Route::put('/borrows/{borrow}/return', [BorrowController::class, 'return'])->name('borrows.return');
    Route::delete('/borrows/{borrow}', [BorrowController::class, 'destroy'])->name('borrows.destroy');
    Route::delete('/borrows', [BorrowController::class, 'deleteAll'])->name('borrows.deleteAll');
});

Route::middleware(['auth', 'role:borrower'])->group(function () {
    Route::post('/books/{book}/borrow', [BorrowController::class, 'borrow'])->name('books.borrow');
    Route::get('/profile/history', [BorrowController::class, 'history'])->name('books.history');
});

Route::get('/test/upload', [ImageUploadTestController::class, 'showForm'])->name('test.upload');
Route::post('/test/upload', [ImageUploadTestController::class, 'upload'])->name('test.upload.store');

require __DIR__ . '/auth.php';
