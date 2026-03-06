@extends('admin.layouts.app')

@section('title', $user->name . ' - Tasks')
@section('page-title', $user->name . "'s Tasks")
@section('page-subtitle', 'View all tasks for this user')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.show', $user) }}" class="btn-admin btn-admin-outline btn-admin-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
        Back to User
    </a>
</div>

{{-- Filter --}}
<div class="admin-card p-4 mb-6">
    <form method="GET" class="flex items-end gap-4">
        <div class="w-48">
            <label class="admin-label">Status</label>
            <select name="status" class="admin-input">
                <option value="">All</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn-admin btn-admin-primary btn-admin-sm">Filter</button>
    </form>
</div>

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr><th>Title</th><th>Category</th><th>Status</th><th>Priority</th><th>Due Date</th><th>Created</th></tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr>
                    <td class="font-medium text-gray-700">{{ Str::limit($task->title, 50) }}</td>
                    <td>
                        @if($task->category)
                        <span class="inline-flex items-center gap-1.5 text-xs">
                            <span class="w-2 h-2 rounded-full" style="background: {{ $task->category->color }}"></span>
                            {{ $task->category->name }}
                        </span>
                        @else
                        <span class="text-gray-400 text-xs">None</span>
                        @endif
                    </td>
                    <td><span class="badge badge-{{ $task->status === 'completed' ? 'success' : ($task->status === 'pending' ? 'warning' : ($task->status === 'cancelled' ? 'secondary' : 'info')) }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span></td>
                    <td><span class="badge badge-{{ $task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : 'secondary') }}">{{ ucfirst($task->priority) }}</span></td>
                    <td class="text-xs text-gray-400">{{ $task->due_date ? $task->due_date->format('M d, Y') : '-' }}</td>
                    <td class="text-xs text-gray-400">{{ $task->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-gray-400 py-12">No tasks found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tasks->hasPages())
    <div class="p-5 border-t border-gray-100">{{ $tasks->links() }}</div>
    @endif
</div>
@endsection
