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
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}, {{ Auth::user()->first_name }}! 👋
            </h1>
            <p class="text-gray-500 mt-1">Here's what's happening with your tasks today.</p>
        </div>
        <a href="{{ route('tasks.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Task
        </a>
    </div>

    {{--
    ═══════════ STATS CARDS (Vue Component) ═══════════
    The <stats-cards> component receives stats from Laravel as JSON props.
    @json($stats) converts the PHP array into a JavaScript object.
    --}}
    <div class="mb-8">
        <stats-cards :stats='@json($stats)'></stats-cards>
    </div>

    {{-- Two Column Layout --}}
    <div class="grid lg:grid-cols-3 gap-8">

        {{-- ═══════════ RECENT TASKS ═══════════ --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900">📋 Recent Tasks</h2>
                    <a href="{{ route('tasks.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition">
                        View All →
                    </a>
                </div>

                <div class="divide-y divide-gray-50">
                    @forelse($recentTasks as $task)
                        <a href="{{ route('tasks.show', $task) }}" class="block px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-4">
                                {{-- Status Icon --}}
                                <div class="flex-shrink-0">
                                    @if($task->status === 'completed')
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    @elseif($task->status === 'in_progress')
                                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Task Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-800 truncate {{ $task->status === 'completed' ? 'line-through opacity-60' : '' }}">
                                        {{ $task->title }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        {{-- Priority Badge --}}
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                            {{ $task->priority === 'medium' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $task->priority === 'low' ? 'bg-gray-100 text-gray-600' : '' }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                        {{-- Category --}}
                                        @if($task->category)
                                            <span class="text-xs text-gray-500">{{ $task->category->name }}</span>
                                        @endif
                                        {{-- Due Date --}}
                                        @if($task->due_date)
                                            <span class="text-xs {{ $task->is_overdue ? 'text-red-500 font-medium' : 'text-gray-400' }}">
                                                📅 {{ $task->due_date->format('M d') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Arrow --}}
                                <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-3 text-sm font-medium text-gray-900">No tasks yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first task.</p>
                            <a href="{{ route('tasks.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                + Create Task
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ═══════════ SIDEBAR: Categories + Quick Links ═══════════ --}}
        <div class="space-y-6">
            {{-- Categories Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900">🏷️ Categories</h2>
                    <a href="{{ route('categories.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition">
                        Manage
                    </a>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @forelse($categories as $category)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $category->color }}"></div>
                                <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                            </div>
                            <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                                {{ $category->tasks_count }} tasks
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No categories yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Quick Links Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">⚡ Quick Links</h2>
                <div class="space-y-2">
                    <a href="{{ route('tasks.create') }}" class="flex items-center gap-3 px-3 py-2d rounded-lg hover:bg-indigo-50 transition text-sm text-gray-700 hover:text-indigo-700">
                        ➕ Create New Task
                    </a>
                    <a href="{{ route('tasks.index', ['status' => 'pending']) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-yellow-50 transition text-sm text-gray-700 hover:text-yellow-700">
                        ⏳ View Pending Tasks
                    </a>
                    <a href="{{ route('tasks.index', ['priority' => 'urgent']) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-50 transition text-sm text-gray-700 hover:text-red-700">
                        🔥 View Urgent Tasks
                    </a>
                    <a href="{{ route('redis.demo') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-50 transition text-sm text-gray-700 hover:text-red-700">
                        🔴 Redis Playground
                    </a>
                </div>
            </div>

            {{-- Tech Stack Info --}}
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white">
                <h3 class="font-bold text-lg mb-3">🛠️ Tech Stack</h3>
                <div class="space-y-2 text-indigo-100 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-400 rounded-full"></span> Laravel 12
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-red-400 rounded-full"></span> Redis (Cache, Session, Queue)
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full"></span> Vue.js 3 (Components)
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-400 rounded-full"></span> Pusher (Real-time)
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-cyan-400 rounded-full"></span> Tailwind CSS 4
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
