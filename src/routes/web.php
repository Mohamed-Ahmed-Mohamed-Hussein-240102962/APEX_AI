<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    Route::post('/borrow/{book}', [\App\Http\Controllers\BorrowController::class, 'store'])->name('borrow.store');
    Route::post('/borrow/return/{record}', [\App\Http\Controllers\BorrowController::class, 'returnBook'])->name('borrow.return');
    Route::get('/profile', [\App\Http\Controllers\BorrowController::class, 'profile'])->name('profile.index');

    // Book Catalogue (Available for all authenticated members)
    Route::get('/books', [\App\Http\Controllers\BookController::class, 'index'])->name('books.index');
    
    // User & Book Management Routes
    Route::middleware('role:Admin|Librarian')->group(function () {
        Route::get('/books/manage', [\App\Http\Controllers\BookController::class, 'manage'])->name('books.manage');
        Route::post('/books', [\App\Http\Controllers\BookController::class, 'store'])->name('books.store');
        Route::put('/books/{book}', [\App\Http\Controllers\BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}', [\App\Http\Controllers\BookController::class, 'destroy'])->name('books.destroy');
        
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('/borrows', [\App\Http\Controllers\BorrowController::class, 'index'])->name('borrows.index');
    });
    
    Route::middleware('role:Admin')->group(function () {
        Route::post('/users/librarian', [\App\Http\Controllers\UserController::class, 'storeLibrarian'])->name('users.storeLibrarian');
        Route::get('/admin/roles', [\App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles');
    });
});
