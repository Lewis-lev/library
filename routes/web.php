<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImageUploadTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('books', BookController::class)->except(['index','show']);
    Route::get('/userlist', [ProfileController::class, 'userList'])->name('auth.user-list');
    Route::get('/borrow', [BorrowController::class, 'index'])->name('borrow.index');
    Route::get('/user/{user_id}/view', [ProfileController::class, 'userView'])->name('user.view');
    Route::put('/borrows/{borrow}/approve', [BorrowController::class, 'approve'])->name('borrows.approve');
    Route::put('/borrows/{borrow}/reject', [BorrowController::class, 'reject'])->name('borrows.reject');
    Route::put('/borrows/{borrow}/return', [BorrowController::class, 'return'])->name('borrows.return');
    Route::delete('/borrows/{borrow}', [BorrowController::class, 'destroy'])->name('borrows.destroy');
    Route::delete('/user/{user_id}', [ProfileController::class, 'delete'])->name('user.delete');
    Route::delete('/borrows', [BorrowController::class, 'deleteAll'])->name('borrows.deleteAll');
});

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

Route::middleware(['auth', 'role:borrower', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/books/{book}/borrow', [BorrowController::class, 'borrow'])->name('books.borrow');
    Route::get('/profile/history', [BorrowController::class, 'history'])->name('books.history');
    Route::get('/dashboard', [BookController::class, 'dashboard'])->name('dashboard');
});

Route::get('/test/upload', [ImageUploadTestController::class, 'showForm'])->name('test.upload');
Route::post('/test/upload', [ImageUploadTestController::class, 'upload'])->name('test.upload.store');
Route::get('/debug-scheme', function (Request $request) {
    return $request->getScheme(); // returns 'http' or 'https'
});


require __DIR__ . '/auth.php';
