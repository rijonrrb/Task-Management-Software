{{--
╔══════════════════════════════════════════════════════════════╗
║  VIEW: Show Single Task                                      ║
║  Learning: Model data display, conditional rendering, delete ║
╚══════════════════════════════════════════════════════════════╝
--}}
@extends('layouts.app')
@section('title', $task->title)

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-gray-600 mb-6">
        <a href="{{ route('tasks.index') }}" class="hover:text-gray-300 transition">Tasks</a>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-400">{{ Str::limit($task->title, 40) }}</span>
    </div>

    <div class="max-w-2xl">
        <div class="bg-[#161b22] border border-[#30363d] rounded-xl overflow-hidden">
            {{-- Left border color strip based on status --}}
            <div class="h-1
                @if($task->status === 'completed') bg-teal-500
                @elseif($task->status === 'in_progress') bg-orange-400
                @else bg-blue-500
                @endif">
            </div>

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-[#30363d]">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        {{-- Badges --}}
                        <div class="flex flex-wrap gap-2 mb-3">
                            @if($task->status === 'completed')
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-teal-900/40 text-teal-400 border border-teal-800/50">Done</span>
                            @elseif($task->status === 'in_progress')
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-orange-900/30 text-orange-400 border border-orange-800/40">In Progress</span>
                            @elseif($task->status === 'cancelled')
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-red-900/30 text-red-400 border border-red-800/40">Cancelled</span>
                            @else
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-blue-900/30 text-blue-400 border border-blue-800/40">To Do</span>
                            @endif

                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium border border-[#30363d] text-gray-400">
                                {{ ucfirst($task->priority) }}
                            </span>

                            @if($task->category)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium border"
                                      style="border-color: {{ $task->category->color ?? '#6b7280' }}40; color: {{ $task->category->color ?? '#9ca3af' }}; background-color: {{ $task->category->color ?? '#6b7280' }}15">
                                    {{ $task->category->name }}
                                </span>
                            @endif

                            @if($task->is_overdue)
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-red-900/50 text-red-300 border border-red-800/50">
                                    Overdue
                                </span>
                            @endif
                        </div>

                        <h1 class="text-lg font-semibold text-white {{ $task->status === 'completed' ? 'line-through text-gray-500' : '' }}">
                            {{ $task->title }}
                        </h1>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="{{ route('tasks.edit', $task) }}"
                           class="px-3 py-1.5 bg-[#21262d] border border-[#30363d] text-gray-300 text-xs font-medium rounded-lg hover:border-teal-700 hover:text-teal-300 transition">
                            Edit
                        </a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1.5 bg-[#21262d] border border-[#30363d] text-gray-500 text-xs font-medium rounded-lg hover:border-red-800 hover:text-red-400 transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Task Details --}}
            <div class="px-6 py-5 space-y-5">
                {{-- Description --}}
                @if($task->description)
                    <div>
                        <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Description</h3>
                        <p class="text-sm text-gray-300 leading-relaxed whitespace-pre-line">{{ $task->description }}</p>
                    </div>
                @endif

                {{-- Metadata Grid --}}
                <div class="grid sm:grid-cols-2 gap-3">
                    <div class="bg-[#0d1117] rounded-lg p-3 border border-[#30363d]">
                        <p class="text-xs text-gray-600 uppercase mb-1">Due Date</p>
                        <p class="text-sm font-medium text-gray-300">
                            {{ $task->due_date ? $task->due_date->format('F j, Y') : 'No due date' }}
                        </p>
                        @if($task->due_date && !$task->is_overdue && !in_array($task->status, ['completed', 'cancelled']))
                            <p class="text-xs text-gray-600 mt-1">{{ $task->due_date->diffForHumans() }}</p>
                        @endif
                    </div>

                    <div class="bg-[#0d1117] rounded-lg p-3 border border-[#30363d]">
                        <p class="text-xs text-gray-600 uppercase mb-1">Created</p>
                        <p class="text-sm font-medium text-gray-300">{{ $task->created_at->format('F j, Y') }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $task->created_at->diffForHumans() }}</p>
                    </div>

                    @if($task->completed_at)
                        <div class="bg-teal-900/20 rounded-lg p-3 border border-teal-800/30">
                            <p class="text-xs text-teal-600 uppercase mb-1">Completed</p>
                            <p class="text-sm font-medium text-teal-300">{{ $task->completed_at->format('F j, Y') }}</p>
                            <p class="text-xs text-teal-600 mt-1">{{ $task->completed_at->diffForHumans() }}</p>
                        </div>
                    @endif

                    <div class="bg-[#0d1117] rounded-lg p-3 border border-[#30363d]">
                        <p class="text-xs text-gray-600 uppercase mb-1">Assigned To</p>
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 bg-teal-800 rounded-full flex items-center justify-center text-xs font-bold text-teal-200">
                                {{ $task->user->initials }}
                            </div>
                            <p class="text-sm font-medium text-gray-300">{{ $task->user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-3 bg-[#0d1117] border-t border-[#30363d] flex items-center justify-between">
                <a href="{{ route('tasks.index') }}" class="text-xs text-gray-600 hover:text-gray-400 transition">
                    ← Back to Tasks
                </a>
                <p class="text-xs text-gray-700">
                    Updated {{ $task->updated_at->diffForHumans() }}
                </p>
            </div>
        </div>
    </div>
@endsection
