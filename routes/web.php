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
Route::post('/books/{book}/borrow', [BookController::class, 'borrow'])->name('books.borrow');

Route::get('/dashboard', function () {
    return view('dashboard'); // Or whatever view you want
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('books', BookController::class)->except(['index']);
});

Route::middleware(['auth', 'role:borrower'])->group(function () {
    Route::post('/borrow/{book}', [BorrowController::class, 'borrow'])->name('borrow.book');
    Route::post('/return/{borrow}', [BorrowController::class, 'returnBo'])->name('return.book');
});

Route::get('/test/upload', [ImageUploadTestController::class, 'showForm'])->name('test.upload');
Route::post('/test/upload', [ImageUploadTestController::class, 'upload'])->name('test.upload.store');

require __DIR__.'/auth.php';
