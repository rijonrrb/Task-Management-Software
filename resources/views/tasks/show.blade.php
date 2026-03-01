@extends('layouts.app')
@section('title', $task->title)

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 dark:text-slate-500 mb-6 animate-fade-in">
        <a href="{{ route('tasks.index') }}" class="hover:text-indigo-500 transition">Tasks</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-slate-600 dark:text-slate-300">{{ Str::limit($task->title, 40) }}</span>
    </div>

    <div class="max-w-3xl animate-fade-in-up">
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
            {{-- Color strip based on status --}}
            <div class="h-1.5
                @if($task->status === 'completed') bg-gradient-to-r from-emerald-500 to-green-400
                @elseif($task->status === 'in_progress') bg-gradient-to-r from-amber-500 to-orange-400
                @elseif($task->status === 'cancelled') bg-gradient-to-r from-red-500 to-rose-400
                @else bg-gradient-to-r from-indigo-500 to-blue-400
                @endif">
            </div>

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/50">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        {{-- Badges --}}
                        <div class="flex flex-wrap gap-2 mb-3">
                            @if($task->status === 'completed')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Done
                                </span>
                            @elseif($task->status === 'in_progress')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-500/15 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    In Progress
                                </span>
                            @elseif($task->status === 'cancelled')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/30">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Cancelled
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-500/15 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                                    To Do
                                </span>
                            @endif

                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold border
                                @if($task->priority === 'urgent') bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border-red-200 dark:border-red-500/30
                                @elseif($task->priority === 'high') bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border-orange-200 dark:border-orange-500/30
                                @elseif($task->priority === 'medium') bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border-yellow-200 dark:border-yellow-500/30
                                @else bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-600
                                @endif">
                                {{ ucfirst($task->priority) }}
                            </span>

                            @if($task->category)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border"
                                      style="border-color: {{ $task->category->color ?? '#6366f1' }}40; color: {{ $task->category->color ?? '#6366f1' }}; background-color: {{ $task->category->color ?? '#6366f1' }}10">
                                    <span class="w-2 h-2 rounded-full" style="background: {{ $task->category->color ?? '#6366f1' }}"></span>
                                    {{ $task->category->name }}
                                </span>
                            @endif

                            @if($task->is_overdue)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/30 animate-pulse">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                    Overdue
                                </span>
                            @endif
                        </div>

                        <h1 class="text-xl font-bold text-slate-800 dark:text-white {{ $task->status === 'completed' ? 'line-through opacity-60' : '' }}">
                            {{ $task->title }}
                        </h1>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="{{ route('tasks.edit', $task) }}"
                           class="px-4 py-2 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 text-xs font-semibold rounded-xl hover:border-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition-all">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </span>
                        </a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-400 dark:text-slate-500 text-xs font-semibold rounded-xl hover:border-red-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Task Details --}}
            <div class="px-6 py-6 space-y-6">
                {{-- Description --}}
                @if($task->description)
                    <div>
                        <h3 class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Description</h3>
                        <div class="p-4 bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-100 dark:border-slate-700/50">
                            <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $task->description }}</p>
                        </div>
                    </div>
                @endif

                {{-- Metadata Grid --}}
                <div class="grid sm:grid-cols-2 gap-3">
                    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 border border-slate-100 dark:border-slate-700/50">
                        <div class="flex items-center gap-2 mb-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase">Due Date</p>
                        </div>
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                            {{ $task->due_date ? $task->due_date->format('F j, Y') : 'No due date' }}
                        </p>
                        @if($task->due_date && !$task->is_overdue && !in_array($task->status, ['completed', 'cancelled']))
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $task->due_date->diffForHumans() }}</p>
                        @endif
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 border border-slate-100 dark:border-slate-700/50">
                        <div class="flex items-center gap-2 mb-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase">Created</p>
                        </div>
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $task->created_at->format('F j, Y') }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $task->created_at->diffForHumans() }}</p>
                    </div>

                    @if($task->completed_at)
                        <div class="bg-emerald-50 dark:bg-emerald-500/10 rounded-xl p-4 border border-emerald-200 dark:border-emerald-500/20">
                            <div class="flex items-center gap-2 mb-1.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-xs font-semibold text-emerald-500 dark:text-emerald-400 uppercase">Completed</p>
                            </div>
                            <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">{{ $task->completed_at->format('F j, Y') }}</p>
                            <p class="text-xs text-emerald-500 dark:text-emerald-400 mt-1">{{ $task->completed_at->diffForHumans() }}</p>
                        </div>
                    @endif

                    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 border border-slate-100 dark:border-slate-700/50">
                        <div class="flex items-center gap-2 mb-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase">Assigned To</p>
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="w-6 h-6 gradient-primary rounded-full flex items-center justify-center text-[10px] font-bold text-white shadow-sm">
                                {{ $task->user->initials }}
                            </div>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $task->user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-3 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-slate-700/50 flex items-center justify-between">
                <a href="{{ route('tasks.index') }}" class="text-xs text-slate-400 dark:text-slate-500 hover:text-indigo-500 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Back to Tasks
                </a>
                <p class="text-xs text-slate-400 dark:text-slate-600">
                    Updated {{ $task->updated_at->diffForHumans() }}
                </p>
            </div>
        </div>
    </div>
@endsection
