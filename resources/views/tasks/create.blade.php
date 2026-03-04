@extends('layouts.app')
@section('title', 'Create Task')

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 dark:text-slate-500 mb-6 animate-fade-in">
        <a href="{{ route('tasks.index') }}" class="hover:text-indigo-500 transition">Tasks</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-slate-600 dark:text-slate-300">Create New</span>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 animate-fade-in-up">

        {{-- LEFT: Create Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-slate-800 dark:text-white">Create New Task</h1>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Fill in the details to create a new task</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('tasks.store') }}" method="POST" class="px-6 py-6 space-y-5">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                            Task Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all @error('title') border-red-400 @enderror"
                            placeholder="e.g., Complete the project report">
                        @error('title')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all"
                            placeholder="Add more details about this task...">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="priority" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Priority <span class="text-red-500">*</span></label>
                            <select id="priority" name="priority" required
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Category</label>
                            <select id="category_id" name="category_id"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                <option value="">No Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Due Date</label>
                        <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all @error('due_date') border-red-400 @enderror">
                        @error('due_date')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-5 border-t border-slate-100 dark:border-slate-700/50">
                        <button type="submit" class="px-6 py-3 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                            Create Task
                        </button>
                        <a href="{{ route('tasks.index') }}" class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-medium transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- RIGHT: Tips Sidebar --}}
        <div class="space-y-4">
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-5 text-white shadow-lg shadow-indigo-500/20">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    <h3 class="font-semibold text-sm">Tips for Great Tasks</h3>
                </div>
                <ul class="space-y-2 text-xs text-indigo-100">
                    <li class="flex items-start gap-2"><span class="mt-0.5 text-indigo-200">&rarr;</span> Use clear, action-oriented titles</li>
                    <li class="flex items-start gap-2"><span class="mt-0.5 text-indigo-200">&rarr;</span> Set realistic due dates</li>
                    <li class="flex items-start gap-2"><span class="mt-0.5 text-indigo-200">&rarr;</span> Assign priority based on impact</li>
                    <li class="flex items-start gap-2"><span class="mt-0.5 text-indigo-200">&rarr;</span> Categorize for easy filtering</li>
                    <li class="flex items-start gap-2"><span class="mt-0.5 text-indigo-200">&rarr;</span> Add description for context</li>
                </ul>
            </div>

            @if($categories->isNotEmpty())
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-700 dark:text-white">Categories</h2>
                    <a href="{{ route('categories.index') }}" class="text-xs text-indigo-500 hover:text-indigo-600 font-medium">Manage</a>
                </div>
                <div class="p-3 space-y-1">
                    @foreach($categories as $cat)
                    <div class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                        <span style="width:10px;height:10px;border-radius:50%;background:{{ $cat->color ?? '#6366f1' }};display:inline-block;flex-shrink:0;"></span>
                        <span class="text-xs text-slate-600 dark:text-slate-300 flex-1">{{ $cat->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-4 shadow-sm">
                <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Priority Guide</h3>
                <div class="space-y-2.5">
                    <div class="flex items-center gap-2.5">
                        <span style="width:8px;height:8px;border-radius:50%;background:#ef4444;display:inline-block;flex-shrink:0;"></span>
                        <span class="text-xs text-slate-600 dark:text-slate-300"><strong class="text-slate-700 dark:text-white">Urgent</strong> &mdash; Do immediately</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span style="width:8px;height:8px;border-radius:50%;background:#f97316;display:inline-block;flex-shrink:0;"></span>
                        <span class="text-xs text-slate-600 dark:text-slate-300"><strong class="text-slate-700 dark:text-white">High</strong> &mdash; Do today</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span style="width:8px;height:8px;border-radius:50%;background:#eab308;display:inline-block;flex-shrink:0;"></span>
                        <span class="text-xs text-slate-600 dark:text-slate-300"><strong class="text-slate-700 dark:text-white">Medium</strong> &mdash; Do this week</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span style="width:8px;height:8px;border-radius:50%;background:#94a3b8;display:inline-block;flex-shrink:0;"></span>
                        <span class="text-xs text-slate-600 dark:text-slate-300"><strong class="text-slate-700 dark:text-white">Low</strong> &mdash; Do when possible</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
