@extends('layouts.app')
@section('title', 'Create Ticket')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4 sm:px-6">
    <div class="mb-6">
        <a href="{{ route('tickets.index') }}" class="text-sm text-indigo-500 hover:text-indigo-600 font-medium">← Back to Tickets</a>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white mt-2">Create Support Ticket</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Describe your issue and our team will help you</p>
    </div>

    <form action="{{ route('tickets.store') }}" method="POST" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Subject <span class="text-red-400">*</span></label>
            <input type="text" name="subject" value="{{ old('subject') }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('subject') border-red-400 @enderror" placeholder="Brief summary of your issue" required>
            @error('subject') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Priority</label>
            <select name="priority" class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Description <span class="text-red-400">*</span></label>
            <textarea name="description" rows="6" class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('description') border-red-400 @enderror" placeholder="Describe your issue in detail..." required>{{ old('description') }}</textarea>
            @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('tickets.index') }}" class="px-4 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 transition-all">Submit Ticket</button>
        </div>
    </form>
</div>
@endsection
