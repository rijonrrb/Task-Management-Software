{{--
╔══════════════════════════════════════════════════════════════╗
║  VIEW: Create Task                                           ║
║  Learning: Form building, validation errors, select options  ║
╚══════════════════════════════════════════════════════════════╝
--}}
@extends('layouts.app')
@section('title', 'Create Task')

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-gray-600 mb-6">
        <a href="{{ route('tasks.index') }}" class="hover:text-gray-300 transition">Tasks</a>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-400">Create New</span>
    </div>

    <div class="max-w-2xl">
        <div class="bg-[#161b22] border border-[#30363d] rounded-xl overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-[#30363d]">
                <h1 class="text-lg font-semibold text-white">Create New Task</h1>
                <p class="text-sm text-gray-500 mt-0.5">Fill in the details to create a new task</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('tasks.store') }}" method="POST" class="px-6 py-6 space-y-5">
                @csrf

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-xs font-medium text-gray-400 mb-1.5">
                        Task Title <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        autofocus
                        class="w-full px-3 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:border-teal-700 transition @error('title') border-red-600 @enderror"
                        placeholder="e.g., Complete the project report"
                    >
                    @error('title')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-xs font-medium text-gray-400 mb-1.5">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="w-full px-3 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:border-teal-700 transition @error('description') border-red-600 @enderror"
                        placeholder="Add more details about this task..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Two Columns: Priority & Category --}}
                <div class="grid sm:grid-cols-2 gap-4">
                    {{-- Priority --}}
                    <div>
                        <label for="priority" class="block text-xs font-medium text-gray-400 mb-1.5">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="priority"
                            name="priority"
                            required
                            class="w-full px-3 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-300 focus:outline-none focus:border-teal-700 transition"
                        >
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label for="category_id" class="block text-xs font-medium text-gray-400 mb-1.5">Category</label>
                        <select
                            id="category_id"
                            name="category_id"
                            class="w-full px-3 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-300 focus:outline-none focus:border-teal-700 transition"
                        >
                            <option value="">No Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Due Date --}}
                <div>
                    <label for="due_date" class="block text-xs font-medium text-gray-400 mb-1.5">Due Date</label>
                    <input
                        type="date"
                        id="due_date"
                        name="due_date"
                        value="{{ old('due_date') }}"
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-3 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-300 focus:outline-none focus:border-teal-700 transition @error('due_date') border-red-600 @enderror"
                    >
                    @error('due_date')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center gap-3 pt-4 border-t border-[#30363d]">
                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-500 transition"
                    >
                        Create Task
                    </button>
                    <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 text-sm text-gray-500 hover:text-gray-300 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
