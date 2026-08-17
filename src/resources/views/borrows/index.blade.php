@extends('layouts.app')
@section('title', 'All Borrowings')
@section('content')
<div class="card">
    <h1 class="page-title">All Borrowings (History)</h1>
    
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Record ID</th>
                    <th>User</th>
                    <th>Book Title</th>
                    <th>Borrowed At</th>
                    <th>Returned At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr>
                    <td>{{ $record->id }}</td>
                    <td>
                        {{ $record->user?->name ?? 'Unknown User' }}<br>
                        <small style="color: var(--text-secondary)">{{ $record->user?->email ?? 'N/A' }}</small>
                    </td>
                    <td>{{ $record->book?->title ?? 'Unknown Book' }}</td>
                    <td>{{ $record->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        @if($record->status === 'returned')
                            {{ $record->updated_at->format('M d, Y H:i') }}
                        @else
                            <span style="color: var(--text-secondary)">-</span>
                        @endif
                    </td>
                    <td>
                        @if($record->status === 'returned')
                            <span class="status-badge status-returned">Returned</span>
                        @else
                            <span class="status-badge status-borrowed">Borrowed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-secondary);">No borrowing records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
