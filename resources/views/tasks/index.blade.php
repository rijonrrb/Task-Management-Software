{{--
╔══════════════════════════════════════════════════════════════╗
║  VIEW: Tasks Index (List)                                    ║
║  Purpose: Display all tasks with filters and search          ║
║  Learning: Query params, conditional rendering, pagination   ║
╚══════════════════════════════════════════════════════════════╝
--}}
@extends('layouts.app')
@section('title', 'My Tasks')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Tasks</h1>
            <p class="text-gray-500 mt-1">Manage and track all your tasks</p>
        </div>
        <a href="{{ route('tasks.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Task
        </a>
    </div>

    {{-- Filters Bar --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6">
        <form action="{{ route('tasks.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
            {{-- Search --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Search</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search tasks..."
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>🔄 In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                </select>
            </div>

            {{-- Priority Filter --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Priority</label>
                <select name="priority" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Priorities</option>
                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>🔥 Urgent</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>🟠 High</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>🔵 Medium</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>⚪ Low</option>
                </select>
            </div>

            {{-- Category Filter --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Category</label>
                <select name="category" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter & Clear Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    🔍 Filter
                </button>
                <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                    Clear
                </a>
            </div>
        </form>
    </div>

    {{-- Task Board (Vue Component) --}}
    <task-board :initial-tasks='@json($tasks->items())'></task-board>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $tasks->links() }}
    </div>
@endsection
