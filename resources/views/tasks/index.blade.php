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

    {{-- ═══════════ STATUS SUMMARY CARDS ═══════════ --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        {{-- To Do --}}
        <div class="bg-[#161b22] border border-[#30363d] rounded-xl p-5">
            <div class="flex items-center gap-2 text-gray-400 text-sm mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10" />
                </svg>
                To Do
            </div>
            <div class="text-3xl font-bold text-white">{{ $taskCounts['pending'] }}</div>
        </div>

        {{-- In Progress --}}
        <div class="bg-[#161b22] border border-[#30363d] rounded-xl p-5">
            <div class="flex items-center gap-2 text-gray-400 text-sm mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                In Progress
            </div>
            <div class="text-3xl font-bold text-white">{{ $taskCounts['in_progress'] }}</div>
        </div>

        {{-- Completed --}}
        <div class="bg-[#161b22] border border-[#30363d] rounded-xl p-5">
            <div class="flex items-center gap-2 text-gray-400 text-sm mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Completed
            </div>
            <div class="text-3xl font-bold text-white">{{ $taskCounts['completed'] }}</div>
        </div>
    </div>

    {{-- ═══════════ FILTERS ═══════════ --}}
    <div class="bg-[#161b22] border border-[#30363d] rounded-xl p-5 mb-6 space-y-4">

        {{-- STATUS FILTER --}}
        <div>
            <p class="text-[10px] font-semibold tracking-widest text-gray-500 uppercase mb-2">Status</p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('tasks.index', array_merge(request()->except('status', 'page'), [])) }}"
                   class="px-3 py-1 rounded-full text-xs font-medium transition border
                          {{ !request('status') ? 'bg-teal-600/20 border-teal-600/50 text-teal-300' : 'border-[#30363d] text-gray-400 hover:border-gray-500 hover:text-gray-200' }}">
                    All {{ $taskCounts['total'] }}
                </a>
                <a href="{{ route('tasks.index', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}"
                   class="px-3 py-1 rounded-full text-xs font-medium transition border
                          {{ request('status') === 'pending' ? 'bg-teal-600/20 border-teal-600/50 text-teal-300' : 'border-[#30363d] text-gray-400 hover:border-gray-500 hover:text-gray-200' }}">
                    To Do {{ $taskCounts['pending'] }}
                </a>
                <a href="{{ route('tasks.index', array_merge(request()->except('status', 'page'), ['status' => 'in_progress'])) }}"
                   class="px-3 py-1 rounded-full text-xs font-medium transition border
                          {{ request('status') === 'in_progress' ? 'bg-teal-600/20 border-teal-600/50 text-teal-300' : 'border-[#30363d] text-gray-400 hover:border-gray-500 hover:text-gray-200' }}">
                    In Progress {{ $taskCounts['in_progress'] }}
                </a>
                <a href="{{ route('tasks.index', array_merge(request()->except('status', 'page'), ['status' => 'completed'])) }}"
                   class="px-3 py-1 rounded-full text-xs font-medium transition border
                          {{ request('status') === 'completed' ? 'bg-teal-600/20 border-teal-600/50 text-teal-300' : 'border-[#30363d] text-gray-400 hover:border-gray-500 hover:text-gray-200' }}">
                    Done {{ $taskCounts['completed'] }}
                </a>
            </div>
        </div>

        {{-- CATEGORY FILTER --}}
        @if($categories->isNotEmpty())
        <div>
            <p class="text-[10px] font-semibold tracking-widest text-gray-500 uppercase mb-2">Category</p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('tasks.index', array_merge(request()->except('category', 'page'), [])) }}"
                   class="px-3 py-1 rounded-full text-xs font-medium transition border
                          {{ !request('category') ? 'bg-teal-600/20 border-teal-600/50 text-teal-300' : 'border-[#30363d] text-gray-400 hover:border-gray-500 hover:text-gray-200' }}">
                    All
                </a>
                @foreach($categories as $category)
                <a href="{{ route('tasks.index', array_merge(request()->except('category', 'page'), ['category' => $category->id])) }}"
                   class="px-3 py-1 rounded-full text-xs font-medium transition border
                          {{ request('category') == $category->id ? 'bg-teal-600/20 border-teal-600/50 text-teal-300' : 'border-[#30363d] text-gray-400 hover:border-gray-500 hover:text-gray-200' }}">
                    <span class="w-1.5 h-1.5 rounded-full inline-block mr-1" style="background-color: {{ $category->color ?? '#6b7280' }}"></span>
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- SEARCH (compact, inline) --}}
        <form action="{{ route('tasks.index') }}" method="GET" class="flex gap-2">
            @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search tasks..."
                class="flex-1 px-3 py-1.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:border-teal-700"
            >
            <button type="submit" class="px-3 py-1.5 bg-[#21262d] border border-[#30363d] text-gray-400 text-sm rounded-lg hover:text-gray-200 transition">
                Search
            </button>
            @if(request()->hasAny(['search','status','category','priority']))
            <a href="{{ route('tasks.index') }}" class="px-3 py-1.5 text-gray-500 text-sm hover:text-gray-300 transition">Clear</a>
            @endif
        </form>
    </div>

    {{-- ═══════════ NEW TASK BUTTON ═══════════ --}}
    <a href="{{ route('tasks.create') }}"
       class="flex items-center gap-2 w-full px-4 py-3 bg-transparent border border-dashed border-[#30363d] rounded-xl text-gray-500 hover:text-gray-300 hover:border-gray-500 transition mb-4 text-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Task
    </a>

    {{-- ═══════════ TASK LIST ═══════════ --}}
    <div class="space-y-3">
        @forelse($tasks as $task)
            {{-- Task Card with left colored border --}}
            <div class="bg-[#161b22] border border-[#30363d] rounded-xl overflow-hidden flex hover:border-[#484f58] transition group">
                {{-- Left Status Border --}}
                <div class="w-1 flex-shrink-0
                    @if($task->status === 'completed') bg-teal-500
                    @elseif($task->status === 'in_progress') bg-orange-400
                    @else bg-blue-500
                    @endif">
                </div>

                {{-- Card Content --}}
                <div class="flex-1 px-4 py-4 flex items-start gap-4">
                    {{-- Status Icon --}}
                    <div class="flex-shrink-0 mt-0.5">
                        @if($task->status === 'completed')
                            <div class="w-5 h-5 rounded-full bg-teal-600 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        @else
                            <div class="w-5 h-5 rounded-full border-2 border-[#484f58]"></div>
                        @endif
                    </div>

                    {{-- Task Info --}}
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('tasks.show', $task) }}" class="block">
                            <p class="font-semibold text-sm text-white leading-snug group-hover:text-teal-300 transition
                               {{ $task->status === 'completed' ? 'line-through text-gray-500 group-hover:text-gray-400' : '' }}">
                                {{ $task->title }}
                            </p>
                            @if($task->description)
                                <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $task->description }}</p>
                            @endif
                        </a>

                        {{-- Badges Row --}}
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            {{-- Category Badge --}}
                            @if($task->category)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium border"
                                      style="border-color: {{ $task->category->color ?? '#6b7280' }}40; color: {{ $task->category->color ?? '#9ca3af' }}; background-color: {{ $task->category->color ?? '#6b7280' }}15">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $task->category->color ?? '#9ca3af' }}"></span>
                                    {{ $task->category->name }}
                                </span>
                            @endif

                            {{-- Status Badge --}}
                            @if($task->status === 'completed')
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium bg-teal-900/40 text-teal-400 border border-teal-800/50">Done</span>
                            @elseif($task->status === 'in_progress')
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium bg-orange-900/30 text-orange-400 border border-orange-800/40">In Progress</span>
                            @elseif($task->status === 'cancelled')
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium bg-red-900/30 text-red-400 border border-red-800/40">Cancelled</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium bg-blue-900/30 text-blue-400 border border-blue-800/40">To Do</span>
                            @endif

                            {{-- Due Date --}}
                            @if($task->due_date)
                                <span class="text-[11px] {{ $task->is_overdue ? 'text-red-400' : 'text-gray-600' }}">
                                    {{ $task->due_date->format('M d') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Edit / Delete quick actions --}}
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                        <a href="{{ route('tasks.edit', $task) }}"
                           class="p-1.5 rounded-md text-gray-600 hover:text-gray-300 hover:bg-[#21262d] transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                              onsubmit="return confirm('Delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="p-1.5 rounded-md text-gray-600 hover:text-red-400 hover:bg-red-900/20 transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-[#161b22] border border-dashed border-[#30363d] rounded-xl">
                <div class="w-12 h-12 rounded-full bg-[#21262d] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <p class="text-gray-500 text-sm font-medium">No tasks found</p>
                <p class="text-gray-600 text-xs mt-1">
                    @if(request()->hasAny(['search','status','category']))
                        Try adjusting your filters
                    @else
                        Create your first task to get started
                    @endif
                </p>
                @unless(request()->hasAny(['search','status','category']))
                    <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 bg-teal-600 text-white text-sm rounded-lg hover:bg-teal-500 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Task
                    </a>
                @endunless
            </div>
        @endforelse
    </div>

    {{-- ═══════════ PAGINATION ═══════════ --}}
    @if($tasks->hasPages())
    <div class="mt-6">
        {{ $tasks->links() }}
    </div>
    @endif

@endsection
