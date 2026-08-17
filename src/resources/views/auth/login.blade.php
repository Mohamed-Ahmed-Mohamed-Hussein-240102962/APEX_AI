@extends('layouts.app')
@section('title', 'Login - LibrarySys')
@section('content')
<div class="auth-form card">
    <h2 class="page-title" style="text-align: center; margin-bottom: 1.5rem;">Welcome Back</h2>
    
    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin-left: 1.5rem">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn" style="width: 100%; margin-top: 1rem;">Login</button>
    </form>
    <p style="text-align: center; margin-top: 1.5rem; color: var(--text-secondary);">
        Don't have an account? <a href="{{ route('register') }}" style="color: var(--accent);">Register here</a>
    </p>
</div>
@endsection
