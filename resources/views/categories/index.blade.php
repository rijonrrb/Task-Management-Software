{{--
╔══════════════════════════════════════════════════════════════╗
║  VIEW: Categories Management                                 ║
║  Learning: Inline forms, color picker, delete with confirm   ║
╚══════════════════════════════════════════════════════════════╝
--}}
@extends('layouts.app')
@section('title', 'Categories')

@section('content')
    <div class="max-w-3xl">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Categories</h1>
        <p class="text-gray-500 mb-8">Organize your tasks into categories</p>

        {{-- Create Category Form --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4">➕ Create New Category</h2>
            <form action="{{ route('categories.store') }}" method="POST" class="flex flex-wrap gap-4 items-end">
                @csrf

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="e.g., Work, Personal, Shopping"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Color</label>
                    <input
                        type="color"
                        name="color"
                        value="{{ old('color', '#3B82F6') }}"
                        class="w-14 h-10 rounded-lg border border-gray-200 cursor-pointer"
                    >
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Description (optional)</label>
                    <input
                        type="text"
                        name="description"
                        value="{{ old('description') }}"
                        placeholder="Brief description..."
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    Create
                </button>
            </form>
        </div>

        {{-- Categories List --}}
        <div class="space-y-4">
            @forelse($categories as $category)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            {{-- Color Dot --}}
                            <div class="w-5 h-5 rounded-full shadow-sm border border-white" style="background-color: {{ $category->color }}"></div>

                            <div>
                                <h3 class="font-bold text-gray-800">{{ $category->name }}</h3>
                                @if($category->description)
                                    <p class="text-sm text-gray-500">{{ $category->description }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            {{-- Task Count --}}
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                                {{ $category->tasks_count }} {{ Str::plural('task', $category->tasks_count) }}
                            </span>

                            {{-- Delete Button --}}
                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                  onsubmit="return confirm('Delete this category? Tasks will become uncategorized.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No categories yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Create your first category using the form above.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
