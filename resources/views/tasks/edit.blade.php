@extends('layouts.app')
@section('title', 'Edit: ' . $task->title)

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 dark:text-slate-500 mb-6 animate-fade-in">
        <a href="{{ route('tasks.index') }}" class="hover:text-indigo-500 transition">Tasks</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('tasks.show', $task) }}" class="hover:text-indigo-500 transition">{{ Str::limit($task->title, 30) }}</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-slate-600 dark:text-slate-300">Edit</span>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 animate-fade-in-up">

        {{-- LEFT: Edit Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-slate-800 dark:text-white">Edit Task</h1>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Update the task details below</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('tasks.update', $task) }}" method="POST" class="px-6 py-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                            Task Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all @error('title') border-red-400 @enderror">
                        @error('title')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">{{ old('description', $task->description) }}</textarea>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" required
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $task->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label for="priority" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Priority <span class="text-red-500">*</span></label>
                            <select id="priority" name="priority" required
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority', $task->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Category</label>
                            <select id="category_id" name="category_id"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                <option value="">None</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Due Date</label>
                        <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all @error('due_date') border-red-400 @enderror">
                        @error('due_date')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-5 border-t border-slate-100 dark:border-slate-700/50">
                        <button type="submit" class="px-6 py-3 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                            Save Changes
                        </button>
                        <a href="{{ route('tasks.show', $task) }}" class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-medium transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- RIGHT: Sidebar --}}
        <div class="space-y-4">
            {{-- Current Task Status --}}
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                    <h2 class="text-sm font-semibold text-slate-700 dark:text-white">Current Info</h2>
                </div>
                <div class="p-4 space-y-0">
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Current Status</span>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-lg
                            @if($task->status==='completed') bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400
                            @elseif($task->status==='in_progress') bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400
                            @elseif($task->status==='cancelled') bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400
                            @else bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 @endif">
                            {{ ucfirst(str_replace('_',' ',$task->status)) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Priority</span>
                        <span class="text-xs font-semibold @if($task->priority==='urgent') text-red-500 @elseif($task->priority==='high') text-orange-500 @elseif($task->priority==='medium') text-yellow-500 @else text-slate-500 @endif">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Created</span>
                        <span class="text-xs text-slate-600 dark:text-slate-300">{{ $task->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Last Updated</span>
                        <span class="text-xs text-slate-600 dark:text-slate-300">{{ $task->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            {{-- View Task --}}
            <a href="{{ route('tasks.show', $task) }}" class="flex items-center gap-3 w-full px-4 py-3 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-2xl hover:border-indigo-300 dark:hover:border-indigo-500/40 hover:text-indigo-500 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View Task Details
            </a>

            {{-- Danger Zone --}}
            <div class="bg-white dark:bg-slate-800/50 border border-red-200 dark:border-red-500/20 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-3 bg-red-50/50 dark:bg-red-500/5 border-b border-red-100 dark:border-red-500/10">
                    <h2 class="text-xs font-semibold text-red-500 uppercase tracking-wider">Danger Zone</h2>
                </div>
                <div class="p-4">
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="flex items-center gap-2 w-full px-4 py-2.5 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-500 text-xs font-semibold rounded-xl hover:bg-red-100 dark:hover:bg-red-500/20 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete This Task
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
