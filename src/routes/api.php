<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BorrowController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes are prefixed with /api automatically.
| Token-based auth via Laravel Sanctum (Bearer Token).
|
*/

// ── Health & Readiness Checks (Docker & Monitoring) ─────────────────────
Route::get('/health', function () {
    return response()->json(['status' => 'ok'], 200);
});

Route::get('/ready', function () {
    try {
        DB::connection()->getPdo();

        return response()->json(['status' => 'healthy', 'database' => 'up'], 200);
    } catch (Exception $e) {
        return response()->json(['status' => 'unhealthy', 'error' => $e->getMessage()], 500);
    }
});

// ── Public (Guest) ──────────────────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ── Authenticated ───────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // Books — catalogue (all authenticated users)
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{book}', [BookController::class, 'show']);

    // Borrowing
    Route::post('/borrow/{book}', [BorrowController::class, 'store']);
    Route::post('/borrow/return/{record}', [BorrowController::class, 'returnBook']);
    Route::get('/my-borrows', [BorrowController::class, 'myBorrows']);

    // ── Admin | Librarian ───────────────────────────────────────────────
    Route::middleware('role:Admin|Librarian')->group(function () {
        Route::post('/books', [BookController::class, 'store']);
        Route::put('/books/{book}', [BookController::class, 'update']);
        Route::delete('/books/{book}', [BookController::class, 'destroy']);

        Route::get('/users', [UserController::class, 'index']);
        Route::get('/borrows', [BorrowController::class, 'index']);
    });

    // ── Admin Only ──────────────────────────────────────────────────────
    Route::middleware('role:Admin')->group(function () {
        Route::post('/users/librarian', [UserController::class, 'storeLibrarian']);
        Route::get('/roles', [UserController::class, 'roles']);
    });
});
