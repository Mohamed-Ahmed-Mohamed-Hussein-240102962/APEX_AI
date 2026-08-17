@extends('layouts.app')
@section('title', 'User Management')
@section('content')

@role('Admin')
<div class="card" style="margin-bottom: 2rem;">
    <h2>Add New Librarian</h2>
    <form method="POST" action="{{ route('users.storeLibrarian') }}" style="margin-top: 1rem; max-width: 500px;">
        @csrf
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required minlength="8">
        </div>
        <button type="submit" class="btn">Create Librarian</button>
    </form>
</div>
@endrole

<div class="card">
    <h1 class="page-title">Registered Members</h1>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach($user->roles as $role)
                            <span class="stock-badge @if($role->name === 'Admin') stock-out @elseif($role->name === 'Librarian') status-borrowed @else stock-in @endif">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
