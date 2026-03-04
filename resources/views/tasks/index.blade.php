@extends('layouts.app')
@section('title', 'My Tasks')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">Task Management</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Organize, track, and manage your tasks efficiently.</p>
        </div>
        <a href="{{ route('tasks.create') }}"
           class="inline-flex items-center gap-2 px-5 py-3 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Create Task
        </a>
    </div>

    {{-- ═══════════ STATUS SUMMARY CARDS ═══════════ --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 hover-lift card-shine animate-fade-in-up stagger-1 opacity-0">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">To Do</span>
            </div>
            <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $taskCounts['pending'] }}</div>
        </div>

        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 hover-lift card-shine animate-fade-in-up stagger-2 opacity-0">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-amber-50 dark:bg-amber-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">In Progress</span>
            </div>
            <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $taskCounts['in_progress'] }}</div>
        </div>

        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 hover-lift card-shine animate-fade-in-up stagger-3 opacity-0">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Completed</span>
            </div>
            <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $taskCounts['completed'] }}</div>
        </div>
    </div>

    {{-- ═══════════ FILTERS ═══════════ --}}
    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-6 mb-6 space-y-5 animate-fade-in-up stagger-4 opacity-0">

        {{-- STATUS FILTER TABS --}}
        <div>
            <p class="text-xs font-semibold tracking-widest text-slate-400 dark:text-slate-500 uppercase mb-3">Status</p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('tasks.index', array_merge(request()->except('status', 'page'), [])) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200
                          {{ !request('status') ? 'gradient-primary text-white shadow-md shadow-indigo-500/20' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 hover:text-slate-700 dark:hover:text-slate-200' }}">
                    All Tasks {{ $taskCounts['total'] }}
                </a>
                <a href="{{ route('tasks.index', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200
                          {{ request('status') === 'pending' ? 'bg-blue-500 text-white shadow-md shadow-blue-500/20' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 hover:text-slate-700 dark:hover:text-slate-200' }}">
                    To Do {{ $taskCounts['pending'] }}
                </a>
                <a href="{{ route('tasks.index', array_merge(request()->except('status', 'page'), ['status' => 'in_progress'])) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200
                          {{ request('status') === 'in_progress' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 hover:text-slate-700 dark:hover:text-slate-200' }}">
                    In Progress {{ $taskCounts['in_progress'] }}
                </a>
                <a href="{{ route('tasks.index', array_merge(request()->except('status', 'page'), ['status' => 'completed'])) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200
                          {{ request('status') === 'completed' ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 hover:text-slate-700 dark:hover:text-slate-200' }}">
                    Done {{ $taskCounts['completed'] }}
                </a>
            </div>
        </div>

        {{-- CATEGORY FILTER --}}
        @if($categories->isNotEmpty())
        <div>
            <p class="text-xs font-semibold tracking-widest text-slate-400 dark:text-slate-500 uppercase mb-3">Category</p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('tasks.index', array_merge(request()->except('category', 'page'), [])) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-medium transition-all duration-200
                          {{ !request('category') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 border border-transparent hover:border-slate-200 dark:hover:border-slate-600' }}">
                    All
                </a>
                @foreach($categories as $category)
                <a href="{{ route('tasks.index', array_merge(request()->except('category', 'page'), ['category' => $category->id])) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium transition-all duration-200
                          {{ request('category') == $category->id ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 border border-transparent hover:border-slate-200 dark:hover:border-slate-600' }}">
                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $category->color ?? '#6b7280' }}"></span>
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- SEARCH --}}
        <form action="{{ route('tasks.index') }}" method="GET" class="flex gap-3">
            @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search tasks..."
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all"
                >
            </div>
            <button type="submit" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-all btn-press">
                Search
            </button>
            @if(request()->hasAny(['search','status','category','priority']))
            <a href="{{ route('tasks.index') }}" class="px-4 py-2.5 text-slate-400 dark:text-slate-500 text-sm hover:text-slate-600 dark:hover:text-slate-300 transition font-medium">Clear</a>
            @endif
        </form>
    </div>

    {{-- ═══════════ TASK GRID ═══════════ --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($tasks as $index => $task)
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden hover-lift hover-glow card-shine group animate-fade-in-up stagger-{{ min($index + 1, 8) }} opacity-0">
                {{-- Top Status Bar --}}
                <div class="h-1
                    @if($task->status === 'completed') bg-emerald-500
                    @elseif($task->status === 'in_progress') bg-amber-500
                    @elseif($task->status === 'cancelled') bg-red-500
                    @else bg-blue-500
                    @endif">
                </div>

                <div class="p-5">
                    {{-- Header: Title + Priority --}}
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <a href="{{ route('tasks.show', $task) }}" class="block flex-1 min-w-0">
                            <h3 class="font-semibold text-sm text-slate-800 dark:text-white leading-snug group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition
                               {{ $task->status === 'completed' ? 'line-through text-slate-400 dark:text-slate-500' : '' }}">
                                {{ $task->title }}
                            </h3>
                        </a>
                        <span class="text-[11px] px-2 py-0.5 rounded-lg font-semibold flex-shrink-0
                            @if($task->priority === 'urgent') bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400
                            @elseif($task->priority === 'high') bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400
                            @elseif($task->priority === 'medium') bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400
                            @else bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400
                            @endif">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>

                    {{-- Description --}}
                    @if($task->description)
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-3 line-clamp-2">{{ $task->description }}</p>
                    @endif

                    {{-- Status + Assignee --}}
                    <div class="flex items-center justify-between mb-3">
                        @if($task->status === 'completed')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Completed
                            </span>
                        @elseif($task->status === 'in_progress')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>In Progress
                            </span>
                        @elseif($task->status === 'cancelled')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Cancelled
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>To Do
                            </span>
                        @endif

                        <span class="text-xs text-slate-400 dark:text-slate-500">{{ $task->user->first_name ?? 'Unassigned' }}</span>
                    </div>

                    {{-- Category Tags --}}
                    @if($task->category)
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-medium border"
                              style="border-color: {{ $task->category->color ?? '#6b7280' }}30; color: {{ $task->category->color ?? '#9ca3af' }}; background-color: {{ $task->category->color ?? '#6b7280' }}08">
                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $task->category->color ?? '#9ca3af' }}"></span>
                            {{ $task->category->name }}
                        </span>
                    </div>
                    @endif

                                        {{-- Progress Bar --}}
                    @php
                        $__pct = $task->status === 'completed' ? 100 : ($task->status === 'in_progress' ? 50 : 0);
                        $__barColor = $task->status === 'completed' ? '#10b981' : ($task->status === 'in_progress' ? '#f59e0b' : ($task->status === 'cancelled' ? '#ef4444' : '#60a5fa'));
                    @endphp
                    <div class="mb-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] text-slate-400 dark:text-slate-500">Progress</span>
                            <span class="text-[10px] font-semibold" style="color:{{ $__barColor }}">{{ $__pct }}%</span>
                        </div>
                        <div class="h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500" style="width:{{ $__pct }}%;background-color:{{ $__barColor }};"></div>
                        </div>
                    </div>

{{-- Footer: Due Date + Actions --}}
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-700/50">
                        <div class="flex items-center gap-1.5 text-xs {{ $task->is_overdue ? 'text-red-500' : 'text-slate-400 dark:text-slate-500' }}">
                            @if($task->due_date)
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Due: {{ $task->due_date->format('M d, Y') }}
                            @else
                                <span class="text-slate-300 dark:text-slate-600">No due date</span>
                            @endif
                        </div>

                        {{-- Quick Actions --}}
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <a href="{{ route('tasks.edit', $task) }}"
                               class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all"
                               title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                  onsubmit="return confirm('Delete this task?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all"
                                        title="Delete">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 lg:col-span-3 text-center py-20 bg-white dark:bg-slate-800/50 border border-dashed border-slate-200 dark:border-slate-700 rounded-2xl animate-fade-in-up">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <p class="text-slate-600 dark:text-slate-400 text-sm font-semibold">No tasks found</p>
                <p class="text-slate-400 dark:text-slate-500 text-xs mt-1">
                    @if(request()->hasAny(['search','status','category']))
                        Try adjusting your filters
                    @else
                        Create your first task to get started
                    @endif
                </p>
                @unless(request()->hasAny(['search','status','category']))
                    <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 gradient-primary text-white text-sm font-medium rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all btn-press">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Task
                    </a>
                @endunless
            </div>
        @endforelse
    </div>

    {{-- ═══════════ PAGINATION ═══════════ --}}
    @if($tasks->hasPages())
    <div class="mt-8">
        {{ $tasks->links() }}
    </div>
    @endif

@endsection
