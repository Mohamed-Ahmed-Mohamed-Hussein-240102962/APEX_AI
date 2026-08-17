@extends('layouts.app')
@section('title', 'Roles & Permissions')
@section('content')
<div class="card">
    <h1 class="page-title">Roles & Permissions</h1>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Role ID</th>
                    <th>Role Name</th>
                    <th>Permissions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td><span class="stock-badge stock-in">{{ $role->name }}</span></td>
                    <td>
                        @forelse($role->permissions as $permission)
                            <span class="status-badge status-borrowed">{{ $permission->name }}</span>
                        @empty
                            <em style="color: var(--text-secondary);">Role relies on structural logic or doesn't have custom scoped permissions.</em>
                        @endforelse
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
