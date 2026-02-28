{{--
╔══════════════════════════════════════════════════════════════╗
║  VIEW: Categories Management                                 ║
║  Learning: Inline forms, color picker, delete with confirm   ║
╚══════════════════════════════════════════════════════════════╝
--}}
@extends('layouts.app')
@section('title', 'Categories')

@section('content')
    <div class="max-w-2xl">
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-white">Categories</h1>
            <p class="text-sm text-gray-500 mt-1">Organize your tasks into categories</p>
        </div>

        {{-- Create Category Form --}}
        <div class="bg-[#161b22] border border-[#30363d] rounded-xl p-5 mb-6">
            <h2 class="text-sm font-semibold text-white mb-4">Create New Category</h2>
            <form action="{{ route('categories.store') }}" method="POST" class="flex flex-wrap gap-3 items-end">
                @csrf

                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs text-gray-500 mb-1">Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="e.g., Feature, Bug, Docs"
                        class="w-full px-3 py-2 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:border-teal-700 @error('name') border-red-600 @enderror"
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Color</label>
                    <input
                        type="color"
                        name="color"
                        value="{{ old('color', '#14b8a6') }}"
                        class="w-12 h-9 rounded-lg border border-[#30363d] cursor-pointer bg-[#0d1117]"
                    >
                </div>

                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs text-gray-500 mb-1">Description (optional)</label>
                    <input
                        type="text"
                        name="description"
                        value="{{ old('description') }}"
                        placeholder="Brief description..."
                        class="w-full px-3 py-2 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:border-teal-700"
                    >
                </div>

                <button type="submit" class="px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-500 transition">
                    Create
                </button>
            </form>
        </div>

        {{-- Categories List --}}
        <div class="space-y-2">
            @forelse($categories as $category)
                <div class="bg-[#161b22] border border-[#30363d] rounded-xl p-4 hover:border-[#484f58] transition flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $category->color ?? '#6b7280' }}"></div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-200">{{ $category->name }}</h3>
                            @if($category->description)
                                <p class="text-xs text-gray-500">{{ $category->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('tasks.index', ['category' => $category->id]) }}"
                           class="text-xs text-gray-600 hover:text-teal-400 transition">
                            {{ $category->tasks_count }} {{ Str::plural('task', $category->tasks_count) }}
                        </a>

                        <form action="{{ route('categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('Delete this category? Tasks will become uncategorized.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-600 hover:text-red-400 transition p-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-[#161b22] border border-dashed border-[#30363d] rounded-xl">
                    <svg class="mx-auto h-10 w-10 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <p class="mt-3 text-sm text-gray-500">No categories yet</p>
                    <p class="mt-1 text-xs text-gray-600">Create your first category using the form above.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
