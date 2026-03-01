@extends('layouts.app')
@section('title', 'Categories')

@section('content')
    <div class="max-w-2xl animate-fade-in-up">
        <div class="mb-6">
            <h1 class="text-xl font-bold text-slate-800 dark:text-white">Categories</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Organize your tasks into categories</p>
        </div>

        {{-- Create Category Form --}}
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-6 mb-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-700 dark:text-white mb-4 flex items-center gap-2">
                <div class="w-7 h-7 gradient-primary rounded-lg flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                Create New Category
            </h2>
            <form action="{{ route('categories.store') }}" method="POST" class="flex flex-wrap gap-3 items-end">
                @csrf

                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g., Feature, Bug, Docs"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5">Color</label>
                    <input type="color" name="color" value="{{ old('color', '#6366f1') }}"
                        class="w-12 h-[42px] rounded-xl border border-slate-200 dark:border-slate-600 cursor-pointer bg-slate-50 dark:bg-slate-700/50">
                </div>

                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5">Description (optional)</label>
                    <input type="text" name="description" value="{{ old('description') }}" placeholder="Brief description..."
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all">
                </div>

                <button type="submit" class="px-5 py-2.5 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                    Create
                </button>
            </form>
        </div>

        {{-- Categories List --}}
        <div class="space-y-2">
            @forelse($categories as $category)
                <div class="group bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-4 hover:border-indigo-300 dark:hover:border-indigo-500/40 hover:shadow-md transition-all duration-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded-full flex-shrink-0 ring-2 ring-offset-2 ring-offset-white dark:ring-offset-slate-800 transition-all"
                             style="background-color: {{ $category->color ?? '#6366f1' }}; --tw-ring-color: {{ $category->color ?? '#6366f1' }}40"></div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $category->name }}</h3>
                            @if($category->description)
                                <p class="text-xs text-slate-400 dark:text-slate-500">{{ $category->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('tasks.index', ['category' => $category->id]) }}"
                           class="inline-flex items-center gap-1 text-xs font-medium text-slate-400 dark:text-slate-500 hover:text-indigo-500 transition">
                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-700/50 rounded-md">{{ $category->tasks_count }}</span>
                            {{ Str::plural('task', $category->tasks_count) }}
                        </a>

                        <form action="{{ route('categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('Delete this category? Tasks will become uncategorized.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-300 dark:text-slate-600 hover:text-red-500 transition p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10 opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-14 bg-white dark:bg-slate-800/30 border border-dashed border-slate-300 dark:border-slate-700/50 rounded-2xl">
                    <div class="w-14 h-14 mx-auto bg-slate-100 dark:bg-slate-700/50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">No categories yet</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Create your first category using the form above.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
