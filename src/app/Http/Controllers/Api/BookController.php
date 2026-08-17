<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * List all books (catalogue).
     */
    public function index()
    {
        $books = Book::all();

        return response()->json([
            'books' => $books,
        ]);
    }

    /**
     * Store a new book (Admin|Librarian).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:255|unique:books',
            'copies' => 'required|integer|min:0',
        ]);

        $book = Book::create($validated);

        return response()->json([
            'message' => 'Book created successfully',
            'book' => $book,
        ], 201);
    }

    /**
     * Show a single book.
     */
    public function show(Book $book)
    {
        return response()->json([
            'book' => $book->load('borrowRecords'),
        ]);
    }

    /**
     * Update a book's stock (Admin|Librarian).
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'isbn' => 'sometimes|string|max:255|unique:books,isbn,' . $book->id,
            'copies' => 'sometimes|integer|min:0',
        ]);

        $book->update($validated);

        return response()->json([
            'message' => 'Book updated successfully',
            'book' => $book->fresh(),
        ]);
    }

    /**
     * Delete a book (Admin|Librarian).
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully',
        ]);
    }
}
