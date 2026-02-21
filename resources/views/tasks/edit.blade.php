{{--
╔══════════════════════════════════════════════════════════════╗
║  VIEW: Edit Task                                             ║
║  Learning: Form with existing data, @method('PUT'), old()    ║
╚══════════════════════════════════════════════════════════════╝
--}}
@extends('layouts.app')
@section('title', 'Edit: ' . $task->title)

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('tasks.index') }}" class="hover:text-gray-700 transition">Tasks</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('tasks.show', $task) }}" class="hover:text-gray-700 transition">{{ Str::limit($task->title, 30) }}</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-700 font-medium">Edit</span>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-8 py-6">
                <h1 class="text-2xl font-bold text-white">Edit Task</h1>
                <p class="text-amber-100 mt-1">Update the task details below</p>
            </div>

            {{--
            Form uses PUT method (for updating existing resources).
            HTML forms only support GET and POST, so we use @method('PUT')
            which adds a hidden _method field that Laravel reads.
            --}}
            <form action="{{ route('tasks.update', $task) }}" method="POST" class="px-8 py-8 space-y-6">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Task Title <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title', $task->title) }}"
                        required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition @error('title') border-red-500 @enderror"
                    >
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition @error('description') border-red-500 @enderror"
                    >{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Three Columns: Status, Priority, Category --}}
                <div class="grid sm:grid-cols-3 gap-4">
                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500">
                            <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>🔄 In Progress</option>
                            <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                            <option value="cancelled" {{ old('status', $task->status) === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        </select>
                    </div>

                    {{-- Priority --}}
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select id="priority" name="priority" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500">
                            <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>⚪ Low</option>
                            <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>🔵 Medium</option>
                            <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>🟠 High</option>
                            <option value="urgent" {{ old('priority', $task->priority) === 'urgent' ? 'selected' : '' }}>🔥 Urgent</option>
                        </select>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="category_id" name="category_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500">
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
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                    <input
                        type="date"
                        id="due_date"
                        name="due_date"
                        value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 transition @error('due_date') border-red-500 @enderror"
                    >
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                    <button type="submit"
                        class="px-6 py-3 bg-amber-600 text-white font-semibold rounded-xl hover:bg-amber-700 focus:ring-4 focus:ring-amber-200 transition shadow-lg shadow-amber-200">
                        Save Changes
                    </button>
                    <a href="{{ route('tasks.show', $task) }}" class="px-6 py-3 text-gray-600 hover:text-gray-800 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
