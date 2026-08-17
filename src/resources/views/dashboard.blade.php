@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="card">
    <h1 class="page-title">Dashboard</h1>
    <p>Welcome back, {{ Auth::user()->name }}!</p>
    <p style="margin-top: 0.5rem">Your Role(s): 
        @foreach(Auth::user()->roles as $role)
            <span class="stock-badge stock-in">{{ $role->name }}</span>
        @endforeach
    </p>

    <!-- Specific dashboard options can go here, like shortcuts -->
    <div style="margin-top: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('books.index') }}" class="btn">Browse Catalogue</a>
        @hasanyrole('Admin|Librarian')
            <a href="{{ route('books.manage') }}" class="btn btn-secondary">Manage Books</a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">View Members</a>
        @endhasanyrole
    </div>
</div>
@endsection
