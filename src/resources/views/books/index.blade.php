@extends('layouts.app')
@section('title', 'Library Catalogue')
@section('content')
<div class="card">
    <h1 class="page-title">Library Catalogue</h1>
    
    <div class="books-grid">
        @foreach($books as $book)
        <div class="book-card">
            <div class="book-info">
                <h3>{{ $book->title }}</h3>
                <p><strong>Author:</strong> {{ $book->author }}</p>
                <p><strong>ISBN:</strong> {{ $book->isbn }}</p>
                <p style="margin-bottom: 0;"><strong>Available Copies:</strong> {{ $book->copies }}</p>

                @if($book->copies > 0)
                    <span class="stock-badge stock-in">In Stock</span>
                @else
                    <span class="stock-badge stock-out">Out of Stock</span>
                @endif
            </div>

            @auth
            <div style="margin-top: 1rem;">
                <form action="{{ route('borrow.store', $book->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn" style="width: 100%;" @if($book->copies <= 0) disabled @endif>
                        Borrow Book
                    </button>
                </form>
            </div>
            @endauth
        </div>
        @endforeach
    </div>
</div>
@endsection
