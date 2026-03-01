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

    <div class="max-w-2xl animate-fade-in-up">
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
            {{-- Header --}}
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

                {{-- Title --}}
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

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all @error('description') border-red-400 @enderror">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Three Columns --}}
                <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                            <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>To Do</option>
                            <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $task->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
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
                                <option value="{{ $category->id }}" {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Due Date --}}
                <div>
                    <label for="due_date" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Due Date</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all @error('due_date') border-red-400 @enderror">
                    @error('due_date')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-5 border-t border-slate-100 dark:border-slate-700/50">
                    <button type="submit"
                        class="px-6 py-3 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                        Save Changes
                    </button>
                    <a href="{{ route('tasks.show', $task) }}" class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-medium transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
