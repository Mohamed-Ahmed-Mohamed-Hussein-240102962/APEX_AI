@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
<div class="card">
    <h1 class="page-title">My Borrowing History</h1>
    
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Record ID</th>
                    <th>Book Title</th>
                    <th>Borrow Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr>
                    <td>{{ $record->id }}</td>
                    <td>{{ $record->book?->title ?? 'Unknown Book' }}</td>
                    <td>{{ $record->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        @if($record->status === 'returned')
                            <span class="status-badge status-returned">Returned</span>
                        @else
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <span class="status-badge status-borrowed">Borrowed</span>
                                <form action="{{ route('borrow.return', $record->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-secondary">Return</button>
                                </form>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-secondary);">You have not borrowed any books yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
