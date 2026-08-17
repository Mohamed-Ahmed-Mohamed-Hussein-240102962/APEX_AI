<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    public function manage()
    {
        $books = Book::all();
        return view('books.manage', compact('books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:255|unique:books',
            'copies' => 'required|integer|min:0',
        ]);

        Book::create($validated);

        return redirect()->back()->with('success', 'Book added successfully.');
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'copies' => 'required|integer|min:0',
        ]);

        $book->update(['copies' => $request->copies]);

        return redirect()->back()->with('success', 'Book stock updated.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->back()->with('success', 'Book deleted successfully.');
    }
}
