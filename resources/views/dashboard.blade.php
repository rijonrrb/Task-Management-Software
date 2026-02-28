{{--
╔══════════════════════════════════════════════════════════════╗
║  VIEW: Dashboard                                             ║
║  Purpose: Main dashboard with stats, recent tasks, charts    ║
║  Learning: Vue components in Blade, @json directive          ║
╚══════════════════════════════════════════════════════════════╝
--}}
@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

    {{-- ═══════════ STATS CARDS ═══════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-[#161b22] border border-[#30363d] rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Total Tasks</p>
            <p class="text-3xl font-bold text-white">{{ $stats['total_tasks'] }}</p>
        </div>
        <div class="bg-[#161b22] border border-[#30363d] rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">To Do</p>
            <p class="text-3xl font-bold text-blue-400">{{ $stats['pending_tasks'] }}</p>
        </div>
        <div class="bg-[#161b22] border border-[#30363d] rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">In Progress</p>
            <p class="text-3xl font-bold text-orange-400">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="bg-[#161b22] border border-[#30363d] rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Completed</p>
            <p class="text-3xl font-bold text-teal-400">{{ $stats['completed_tasks'] }}</p>
        </div>
    </div>

    {{-- Progress Bar --}}
    @if($stats['total_tasks'] > 0)
    <div class="bg-[#161b22] border border-[#30363d] rounded-xl p-4 mb-8">
        <div class="flex items-center justify-between text-sm mb-2">
            <span class="text-gray-400">Overall Progress</span>
            <span class="text-gray-300 font-medium">{{ $stats['completed_tasks'] }}/{{ $stats['total_tasks'] }} done</span>
        </div>
        <div class="w-full h-2 bg-[#21262d] rounded-full overflow-hidden">
            <div class="h-full bg-teal-500 rounded-full transition-all"
                 style="width: {{ $stats['total_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100) : 0 }}%"></div>
        </div>
    </div>
    @endif

    {{-- Two Column Layout --}}
    <div class="grid lg:grid-cols-3 gap-6">

        {{-- ═══════════ RECENT TASKS ═══════════ --}}
        <div class="lg:col-span-2">
            <div class="bg-[#161b22] border border-[#30363d] rounded-xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-[#30363d]">
                    <h2 class="text-sm font-semibold text-white">Recent Tasks</h2>
                    <a href="{{ route('tasks.index') }}" class="text-xs text-teal-400 hover:text-teal-300 transition">
                        View All →
                    </a>
                </div>

                <div class="divide-y divide-[#21262d]">
                    @forelse($recentTasks as $task)
                        <a href="{{ route('tasks.show', $task) }}"
                           class="flex items-start gap-3 px-5 py-3.5 hover:bg-[#1c2128] transition group">
                            {{-- Left border indicator --}}
                            <div class="w-0.5 self-stretch rounded-full flex-shrink-0 mt-1
                                @if($task->status === 'completed') bg-teal-500
                                @elseif($task->status === 'in_progress') bg-orange-400
                                @else bg-blue-500
                                @endif"></div>

                            {{-- Status icon --}}
                            <div class="flex-shrink-0 mt-0.5">
                                @if($task->status === 'completed')
                                    <div class="w-4 h-4 rounded-full bg-teal-700 flex items-center justify-center">
                                        <svg class="w-2.5 h-2.5 text-teal-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-4 h-4 rounded-full border border-[#484f58]"></div>
                                @endif
                            </div>

                            {{-- Task Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-200 group-hover:text-white truncate transition
                                   {{ $task->status === 'completed' ? 'line-through text-gray-500' : '' }}">
                                    {{ $task->title }}
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($task->category)
                                        <span class="text-[11px] text-gray-500">{{ $task->category->name }}</span>
                                    @endif
                                    @if($task->due_date)
                                        <span class="text-[11px] {{ $task->is_overdue ? 'text-red-400' : 'text-gray-600' }}">
                                            {{ $task->due_date->format('M d') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Status Badge --}}
                            @if($task->status === 'in_progress')
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-orange-900/30 text-orange-400 border border-orange-800/40 flex-shrink-0 mt-0.5">In Progress</span>
                            @elseif($task->status === 'completed')
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-teal-900/30 text-teal-400 border border-teal-800/40 flex-shrink-0 mt-0.5">Done</span>
                            @else
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-900/30 text-blue-400 border border-blue-800/40 flex-shrink-0 mt-0.5">To Do</span>
                            @endif
                        </a>
                    @empty
                        <div class="px-5 py-12 text-center">
                            <div class="w-10 h-10 rounded-full bg-[#21262d] flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500">No tasks yet</p>
                            <a href="{{ route('tasks.create') }}" class="mt-3 inline-flex items-center gap-1 px-3 py-1.5 bg-teal-600 text-white text-xs rounded-lg hover:bg-teal-500 transition">
                                + Create Task
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ═══════════ SIDEBAR ═══════════ --}}
        <div class="space-y-4">
            {{-- New Task CTA --}}
            <a href="{{ route('tasks.create') }}"
               class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-teal-600 hover:bg-teal-500 text-white text-sm font-medium rounded-xl transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Task
            </a>

            {{-- Quick Actions --}}
            <div class="bg-[#161b22] border border-[#30363d] rounded-xl overflow-hidden">
                <div class="px-5 py-3 border-b border-[#30363d]">
                    <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Quick Actions</h2>
                </div>
                <div class="p-2 space-y-1">
                    <a href="{{ route('tasks.index', ['status' => 'pending']) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[#21262d] transition text-sm text-gray-400 hover:text-gray-200">
                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                        View Pending Tasks
                        <span class="ml-auto text-xs text-gray-600">{{ $stats['pending_tasks'] }}</span>
                    </a>
                    <a href="{{ route('tasks.index', ['status' => 'in_progress']) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[#21262d] transition text-sm text-gray-400 hover:text-gray-200">
                        <div class="w-2 h-2 rounded-full bg-orange-400"></div>
                        In Progress
                        <span class="ml-auto text-xs text-gray-600">{{ $stats['in_progress'] }}</span>
                    </a>
                    @if($stats['overdue_tasks'] > 0)
                    <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-900/20 transition text-sm text-red-400 hover:text-red-300">
                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                        Overdue Tasks
                        <span class="ml-auto text-xs text-red-600">{{ $stats['overdue_tasks'] }}</span>
                    </a>
                    @endif
                    <a href="{{ route('redis.demo') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[#21262d] transition text-sm text-gray-400 hover:text-gray-200">
                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                        Redis Playground
                    </a>
                </div>
            </div>

            {{-- Categories --}}
            @if($categories->isNotEmpty())
            <div class="bg-[#161b22] border border-[#30363d] rounded-xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3 border-b border-[#30363d]">
                    <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Categories</h2>
                    <a href="{{ route('categories.index') }}" class="text-[11px] text-teal-500 hover:text-teal-400 transition">Manage</a>
                </div>
                <div class="p-3 space-y-2">
                    @foreach($categories as $category)
                        <a href="{{ route('tasks.index', ['category' => $category->id]) }}"
                           class="flex items-center justify-between px-2 py-1.5 rounded-lg hover:bg-[#21262d] transition">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full" style="background-color: {{ $category->color ?? '#6b7280' }}"></div>
                                <span class="text-sm text-gray-400">{{ $category->name }}</span>
                            </div>
                            <span class="text-[11px] text-gray-600">{{ $category->tasks_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

@endsection
