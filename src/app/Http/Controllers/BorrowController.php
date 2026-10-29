<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowController extends Controller
{
    public function store(Request $request, Book $book)
    {
        if ($book->copies <= 0) {
            return redirect()->back()->with('error', 'Book Currently Unavailable');
        }

        DB::transaction(function () use ($book) {
            $book->decrement('copies');

            BorrowRecord::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'status' => 'borrowed',
            ]);
        });

        return redirect()->route('profile.index')->with('success', 'Book borrowed successfully!');
    }

    public function profile()
    {
        $records = BorrowRecord::where('user_id', Auth::id())->with('book')->latest()->get();

        return view('profile.index', compact('records'));
    }

    public function index()
    {
        $records = BorrowRecord::with(['user', 'book'])->latest('updated_at')->get();

        return view('borrows.index', compact('records'));
    }

    public function returnBook(BorrowRecord $record)
    {
        if ($record->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($record->status === 'returned') {
            return redirect()->back()->with('error', 'Book is already returned.');
        }

        DB::transaction(function () use ($record) {
            $record->update(['status' => 'returned']);
            $record->book->increment('copies');
        });

        return redirect()->route('profile.index')->with('success', 'Book returned successfully!');
    }
}
