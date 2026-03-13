@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-3xl font-bold text-slate-800 dark:text-white">Dashboard</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Welcome back! Here's your task overview.</p>
    </div>

    {{-- ═══════════ STATS CARDS ═══════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 hover-lift card-shine animate-fade-in-up stagger-1 opacity-0">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Tasks</p>
                <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800 dark:text-white">{{ $stats['total_tasks'] }}</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">All tasks in system</p>
        </div>

        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 hover-lift card-shine animate-fade-in-up stagger-2 opacity-0">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">To Do</p>
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-blue-500">{{ $stats['pending_tasks'] }}</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Waiting to start</p>
        </div>

        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 hover-lift card-shine animate-fade-in-up stagger-3 opacity-0">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">In Progress</p>
                <div class="w-10 h-10 bg-amber-50 dark:bg-amber-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-amber-500">{{ $stats['in_progress'] }}</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Currently active</p>
        </div>

        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 hover-lift card-shine animate-fade-in-up stagger-4 opacity-0">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Completed</p>
                <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-emerald-500">{{ $stats['completed_tasks'] }}</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Tasks finished</p>
        </div>
    </div>

    {{-- Progress Bar --}}
    @if($stats['total_tasks'] > 0)
    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 mb-8 animate-fade-in-up stagger-5 opacity-0">
        <div class="flex items-center justify-between text-sm mb-3">
            <span class="text-slate-600 dark:text-slate-400 font-medium">Overall Progress</span>
            <span class="text-slate-800 dark:text-white font-semibold">{{ $stats['completed_tasks'] }}/{{ $stats['total_tasks'] }} done</span>
        </div>
        <div class="w-full h-3 bg-slate-100 dark:bg-slate-700/50 rounded-full overflow-hidden">
            <div class="h-full gradient-primary rounded-full transition-all duration-1000 ease-out"
                 style="width: {{ $stats['total_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100) : 0 }}%"></div>
        </div>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">{{ $stats['total_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100) : 0 }}% complete</p>
    </div>
    @endif

    {{-- Two Column Layout --}}
    <div class="grid lg:grid-cols-3 gap-6">

        {{-- ═══════════ RECENT TASKS ═══════════ --}}
        <div class="lg:col-span-2 animate-fade-in-up stagger-6 opacity-0">
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700/50">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h2 class="text-base font-semibold text-slate-800 dark:text-white">Recent Tasks</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-amber-500 font-medium">Pin up to 3 tasks</span>
                        <a href="{{ route('tasks.index') }}" class="text-sm text-indigo-500 hover:text-indigo-400 font-medium transition flex items-center gap-1">
                            View All
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <form action="{{ route('tasks.bulk-status') }}" method="POST" id="dashboard-bulk-form" class="hidden">
                    @csrf
                </form>
                <div class="px-6 py-3 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/70 dark:bg-slate-900/30">
                        <div class="flex flex-wrap items-center gap-2">
                            <label class="inline-flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 cursor-pointer select-none">
                                <span class="relative block w-4 h-4 flex-shrink-0">
                                    <input type="checkbox" id="dashboard-select-all" class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <span class="absolute inset-0 rounded border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 peer-checked:bg-indigo-500 peer-checked:border-transparent transition-all duration-150"></span>
                                    <svg class="absolute inset-0 w-full h-full p-0.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Select all
                            </label>
                            <select id="dashboard-bulk-status" class="text-xs px-2 py-1.5 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200">
                                <option value="">Bulk status...</option>
                                <option value="pending">To Do</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <button type="button" id="dashboard-bulk-submit" disabled class="text-xs px-3 py-1.5 rounded-lg bg-indigo-500 text-white disabled:opacity-40 disabled:cursor-not-allowed hover:bg-indigo-600 transition-colors">
                                Apply
                            </button>
                        </div>
                    </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-700/30">
                    @forelse($recentTasks as $task)
                        <div class="flex items-start gap-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-all duration-200 group">
                            <label class="relative flex-shrink-0 mt-1 block w-4 h-4 cursor-pointer">
                                <input type="checkbox" value="{{ $task->id }}" data-task-id="{{ $task->id }}" class="peer dashboard-task-checkbox absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <span class="absolute inset-0 rounded border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 peer-checked:bg-indigo-500 peer-checked:border-transparent transition-all duration-150"></span>
                                <svg class="absolute inset-0 w-full h-full p-0.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </label>

                            {{-- Status icon --}}
                            <div class="flex-shrink-0 mt-0.5">
                                @if($task->status === 'completed')
                                    <div class="w-6 h-6 rounded-lg gradient-success flex items-center justify-center shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                @elseif($task->status === 'in_progress')
                                    <div class="w-6 h-6 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                @elseif($task->status === 'cancelled')
                                    <div class="w-6 h-6 rounded-lg bg-red-100 dark:bg-red-500/20 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                @else
                                    {{-- pending --}}
                                    <div class="w-6 h-6 rounded-full border-2 border-slate-200 dark:border-slate-600 flex items-center justify-center">
                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-500"></div>
                                    </div>
                                @endif
                            </div>

                            {{-- Task Info --}}
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('tasks.show', $task) }}" class="block">
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-200 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 truncate transition
                                       {{ $task->status === 'completed' ? 'line-through text-slate-400 dark:text-slate-500' : '' }}">
                                        {{ $task->title }}
                                    </p>
                                </a>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($task->is_pinned)
                                        <span class="text-[11px] text-amber-500 font-medium">Pinned</span>
                                    @endif
                                    @if($task->category)
                                        <span class="text-xs text-slate-400 dark:text-slate-500">{{ $task->category->name }}</span>
                                    @endif
                                    @if($task->due_date)
                                        <span class="text-xs {{ $task->is_overdue ? 'text-red-500' : 'text-slate-400 dark:text-slate-500' }}">
                                            {{ $task->due_date->format('M d') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Priority Badge --}}
                            <span class="text-xs px-2.5 py-1 rounded-lg font-medium flex-shrink-0
                                @if($task->priority === 'urgent') bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400
                                @elseif($task->priority === 'high') bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400
                                @elseif($task->priority === 'medium') bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400
                                @else bg-slate-50 dark:bg-slate-600/30 text-slate-500 dark:text-slate-400
                                @endif">
                                {{ ucfirst($task->priority) }}
                            </span>
                            <form action="{{ route('tasks.pin', $task) }}" method="POST" class="flex-shrink-0">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="p-1.5 rounded-lg {{ $task->is_pinned ? 'text-amber-500 bg-amber-50 dark:bg-amber-500/10' : 'text-slate-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10' }} transition-colors" title="{{ $task->is_pinned ? 'Unpin task' : 'Pin task' }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.5 2.5a1 1 0 011 0l4 2.3a1 1 0 01.5.87V9l1.4 1.4a1 1 0 01-.7 1.7H11v4.5a1 1 0 11-2 0V12.1H5.3a1 1 0 01-.7-1.7L6 9V5.67a1 1 0 01.5-.87l2-1.15z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="px-6 py-16 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">No tasks yet</p>
                            <a href="{{ route('tasks.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2.5 gradient-primary text-white text-sm font-medium rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all btn-press">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                Create Task
                            </a>
                        </div>
                    @endforelse
                    </div>
            </div>
        </div>

        {{-- ═══════════ SIDEBAR ═══════════ --}}
        <div class="space-y-5 animate-fade-in-up stagger-7 opacity-0">
            {{-- New Task CTA --}}
            <a href="{{ route('tasks.create') }}"
               class="flex items-center justify-center gap-2 w-full px-4 py-3.5 gradient-primary text-white text-sm font-semibold rounded-2xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Task
            </a>

            {{-- Quick Actions --}}
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700/50">
                    <h2 class="text-sm font-semibold text-slate-800 dark:text-white">Quick Actions</h2>
                </div>
                <div class="p-2 space-y-0.5">
                    <a href="{{ route('tasks.index', ['status' => 'pending']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-all text-sm text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 group">
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div>
                        <span>View Pending Tasks</span>
                        <span class="ml-auto text-xs bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 px-2 py-0.5 rounded-lg font-medium group-hover:bg-blue-50 dark:group-hover:bg-blue-500/10 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $stats['pending_tasks'] }}</span>
                    </a>
                    <a href="{{ route('tasks.index', ['status' => 'in_progress']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-all text-sm text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 group">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                        <span>In Progress</span>
                        <span class="ml-auto text-xs bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 px-2 py-0.5 rounded-lg font-medium group-hover:bg-amber-50 dark:group-hover:bg-amber-500/10 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $stats['in_progress'] }}</span>
                    </a>
                    @if($stats['overdue_tasks'] > 0)
                    <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-50 dark:hover:bg-red-500/10 transition-all text-sm text-red-500 hover:text-red-600 group">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-500 badge-pulse"></div>
                        <span>Overdue Tasks</span>
                        <span class="ml-auto text-xs bg-red-50 dark:bg-red-500/10 text-red-500 px-2 py-0.5 rounded-lg font-medium">{{ $stats['overdue_tasks'] }}</span>
                    </a>
                    @endif
                    {{-- <a href="{{ route('redis.demo') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-all text-sm text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                        <span>Redis Playground</span>
                    </a> --}}
                </div>
            </div>

            {{-- Categories --}}
            @if($categories->isNotEmpty())
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-700/50">
                    <h2 class="text-sm font-semibold text-slate-800 dark:text-white">Categories</h2>
                    <a href="{{ route('categories.index') }}" class="text-xs text-indigo-500 hover:text-indigo-400 font-medium transition">Manage</a>
                </div>
                <div class="p-2 space-y-0.5">
                    @foreach($categories as $category)
                        <a href="{{ route('tasks.index', ['category' => $category->id]) }}"
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $category->color ?? '#6b7280' }}"></div>
                                <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200 transition">{{ $category->name }}</span>
                            </div>
                            <span class="text-xs bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 px-2 py-0.5 rounded-lg font-medium">{{ $category->tasks_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
<script>
window.addEventListener('load', function () {
    var form = document.getElementById('dashboard-bulk-form');
    if (!form) return;

    function getBoxes() {
        return Array.from(document.querySelectorAll('.dashboard-task-checkbox'));
    }

    function updateState() {
        var boxes    = getBoxes();
        var selected = boxes.filter(function (c) { return c.checked; });
        var sa       = document.getElementById('dashboard-select-all');
        var statusEl = document.getElementById('dashboard-bulk-status');
        var btn      = document.getElementById('dashboard-bulk-submit');

        if (sa) {
            if (boxes.length > 0 && selected.length === boxes.length) {
                sa.checked = true; sa.indeterminate = false;
            } else if (selected.length > 0) {
                sa.checked = false; sa.indeterminate = true;
            } else {
                sa.checked = false; sa.indeterminate = false;
            }
        }
        if (btn) btn.disabled = (selected.length === 0 || !statusEl || !statusEl.value);
    }

    /* ── Event delegation — survives Vue's DOM replacement ── */
    document.addEventListener('change', function (e) {
        if (e.target.id === 'dashboard-select-all') {
            var checked = e.target.checked;
            getBoxes().forEach(function (cb) { cb.checked = checked; });
        }
        updateState();
    });

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('#dashboard-bulk-submit');
        if (!btn || btn.disabled) return;

        var selected = getBoxes().filter(function (c) { return c.checked; });
        var statusEl = document.getElementById('dashboard-bulk-status');
        if (!selected.length || !statusEl || !statusEl.value) return;

        /* Inject hidden task_ids + status into the hidden form, then submit */
        form.querySelectorAll('.bulk-injected').forEach(function (el) { el.remove(); });
        selected.forEach(function (cb) {
            var inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'task_ids[]'; inp.value = cb.value;
            inp.className = 'bulk-injected';
            form.appendChild(inp);
        });
        var sinp = document.createElement('input');
        sinp.type = 'hidden'; sinp.name = 'status'; sinp.value = statusEl.value;
        sinp.className = 'bulk-injected';
        form.appendChild(sinp);

        form.submit();
    });

    updateState();
});
</script>
@endpush
