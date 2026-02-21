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
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('tasks.index') }}" class="hover:text-gray-700 transition">Tasks</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-700 font-medium">{{ Str::limit($task->title, 40) }}</span>
    </div>

    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- Header with Priority Color --}}
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        {{-- Badges Row --}}
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            {{-- Status Badge --}}
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                {{ $task->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $task->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $task->status === 'pending' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $task->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ str_replace('_', ' ', ucfirst($task->status)) }}
                            </span>

                            {{-- Priority Badge --}}
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $task->priority === 'medium' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $task->priority === 'low' ? 'bg-gray-100 text-gray-600' : '' }}">
                                {{ ucfirst($task->priority) }} Priority
                            </span>

                            {{-- Category Badge --}}
                            @if($task->category)
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium border"
                                      style="border-color: {{ $task->category->color }}; color: {{ $task->category->color }}">
                                    {{ $task->category->name }}
                                </span>
                            @endif

                            {{-- Overdue Badge --}}
                            @if($task->is_overdue)
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-red-500 text-white animate-pulse">
                                    ⚠️ OVERDUE
                                </span>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h1 class="text-2xl font-bold text-gray-900 {{ $task->status === 'completed' ? 'line-through opacity-60' : '' }}">
                            {{ $task->title }}
                        </h1>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-2">
                        <a href="{{ route('tasks.edit', $task) }}"
                           class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                            ✏️ Edit
                        </a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition">
                                🗑️ Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Task Details --}}
            <div class="px-8 py-6 space-y-6">
                {{-- Description --}}
                @if($task->description)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Description</h3>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $task->description }}</p>
                    </div>
                @endif

                {{-- Metadata Grid --}}
                <div class="grid sm:grid-cols-2 gap-4">
                    {{-- Due Date --}}
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Due Date</p>
                        <p class="font-semibold text-gray-800">
                            {{ $task->due_date ? $task->due_date->format('F j, Y') : 'No due date' }}
                        </p>
                        @if($task->due_date && !$task->is_overdue && !in_array($task->status, ['completed', 'cancelled']))
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $task->due_date->diffForHumans() }}
                            </p>
                        @endif
                    </div>

                    {{-- Created At --}}
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Created</p>
                        <p class="font-semibold text-gray-800">{{ $task->created_at->format('F j, Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $task->created_at->diffForHumans() }}</p>
                    </div>

                    {{-- Completed At --}}
                    @if($task->completed_at)
                        <div class="bg-green-50 rounded-xl p-4">
                            <p class="text-xs font-medium text-green-600 uppercase mb-1">Completed</p>
                            <p class="font-semibold text-green-800">{{ $task->completed_at->format('F j, Y') }}</p>
                            <p class="text-xs text-green-600 mt-1">{{ $task->completed_at->diffForHumans() }}</p>
                        </div>
                    @endif

                    {{-- Owner --}}
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Assigned To</p>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600">
                                {{ $task->user->initials }}
                            </div>
                            <p class="font-semibold text-gray-800">{{ $task->user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('tasks.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">
                    ← Back to Tasks
                </a>
                <p class="text-xs text-gray-400">
                    Last updated: {{ $task->updated_at->diffForHumans() }}
                </p>
            </div>
        </div>
    </div>
@endsection
