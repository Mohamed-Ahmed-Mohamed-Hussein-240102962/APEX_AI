<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowController extends Controller
{
    /**
     * Borrow a book.
     */
    public function store(Request $request, Book $book)
    {
        if ($book->copies <= 0) {
            return response()->json([
                'message' => 'Book currently unavailable',
            ], 409);
        }

        DB::transaction(function () use ($book) {
            $book->decrement('copies');

            BorrowRecord::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'status' => 'borrowed',
            ]);
        });

        return response()->json([
            'message' => 'Book borrowed successfully',
        ], 201);
    }

    /**
     * Return a borrowed book.
     */
    public function returnBook(BorrowRecord $record)
    {
        if ($record->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized action',
            ], 403);
        }

        if ($record->status === 'returned') {
            return response()->json([
                'message' => 'Book is already returned',
            ], 409);
        }

        DB::transaction(function () use ($record) {
            $record->update(['status' => 'returned']);
            $record->book->increment('copies');
        });

        return response()->json([
            'message' => 'Book returned successfully',
        ]);
    }

    /**
     * Get current user's borrow records.
     */
    public function myBorrows()
    {
        $records = BorrowRecord::where('user_id', Auth::id())
            ->with('book')
            ->latest()
            ->get();

        return response()->json([
            'records' => $records,
        ]);
    }

    /**
     * Get all borrow records (Admin|Librarian).
     */
    public function index()
    {
        $records = BorrowRecord::with(['user', 'book'])
            ->latest('updated_at')
            ->get();

        return response()->json([
            'records' => $records,
        ]);
    }
}
