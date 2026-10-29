<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile & Borrowing
    Route::post('/borrow/{book}', [BorrowController::class, 'store'])->name('borrow.store');
    Route::post('/borrow/return/{record}', [BorrowController::class, 'returnBook'])->name('borrow.return');
    Route::get('/profile', [BorrowController::class, 'profile'])->name('profile.index');

    // Book Catalogue (Available for all authenticated members)
    Route::get('/books', [BookController::class, 'index'])->name('books.index');

    // User & Book Management Routes
    Route::middleware('role:Admin|Librarian')->group(function () {
        Route::get('/books/manage', [BookController::class, 'manage'])->name('books.manage');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/borrows', [BorrowController::class, 'index'])->name('borrows.index');
    });

    Route::middleware('role:Admin')->group(function () {
        Route::post('/users/librarian', [UserController::class, 'storeLibrarian'])->name('users.storeLibrarian');
        Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles');
    });
});
