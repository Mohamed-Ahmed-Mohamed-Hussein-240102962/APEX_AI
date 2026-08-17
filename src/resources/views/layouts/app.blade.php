<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Library Management')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="bg-flares">
        <div class="flare flare-1"></div>
        <div class="flare flare-2"></div>
        <div class="flare flare-3"></div>
    </div>

    <header class="navbar">
        <a href="/" class="nav-brand">LibrarySys</a>
        <div class="nav-links">
            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('books.index') }}">Catalogue</a>
                <a href="{{ route('profile.index') }}">My Profile</a>
                
                @hasanyrole('Admin|Librarian')
                    <a href="{{ route('books.manage') }}">Manage Books</a>
                    <a href="{{ route('users.index') }}">Members</a>
                    <a href="{{ route('borrows.index') }}">Borrowings</a>
                @endhasanyrole

                @role('Admin')
                    <a href="{{ route('admin.roles') }}">Roles & Permissions</a>
                @endrole

                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-link">Logout ({{ Auth::user()->name }})</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </header>

    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        &copy; {{ date('Y') }} Secure Online Library Management System. All rights reserved.
    </footer>
</body>
</html>
