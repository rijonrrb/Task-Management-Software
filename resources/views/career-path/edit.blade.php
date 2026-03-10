@extends('layouts.app')
@section('title', 'Edit: ' . $careerPath->title)

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 dark:text-slate-500 mb-6 animate-fade-in">
        <a href="{{ route('career-path.index') }}" class="hover:text-indigo-500 transition">Career Paths</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        <a href="{{ route('career-path.show', $careerPath) }}" class="hover:text-indigo-500 transition">{{ Str::limit($careerPath->title, 25) }}</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        <span class="text-slate-600 dark:text-slate-300">Edit</span>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 animate-fade-in-up">
        {{-- LEFT: Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-slate-800 dark:text-white">Edit Career Path</h1>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Update your learning journey details</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('career-path.update', $careerPath) }}" method="POST" class="px-6 py-6 space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                            Path Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $careerPath->title) }}" required autofocus
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all @error('title') border-red-400 @enderror"
                            placeholder="e.g., Master Full-Stack Web Development">
                        @error('title')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Target Role --}}
                    <div>
                        <label for="target_role" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                            Target Role / Skill <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="target_role" name="target_role" value="{{ old('target_role', $careerPath->target_role) }}" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all @error('target_role') border-red-400 @enderror"
                            placeholder="e.g., Full-Stack Developer">
                        @error('target_role')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all"
                            placeholder="Describe your learning goals...">{{ old('description', $careerPath->description) }}</textarea>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Status</label>
                        <select id="status" name="status"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                            <option value="active" {{ old('status', $careerPath->status) === 'active' ? 'selected' : '' }}>🟢 Active</option>
                            <option value="paused" {{ old('status', $careerPath->status) === 'paused' ? 'selected' : '' }}>⏸️ Paused</option>
                            <option value="completed" {{ old('status', $careerPath->status) === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                            <option value="archived" {{ old('status', $careerPath->status) === 'archived' ? 'selected' : '' }}>📦 Archived</option>
                        </select>
                    </div>

                    {{-- Level selects --}}
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="current_level" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Current Level <span class="text-red-500">*</span></label>
                            <select id="current_level" name="current_level" required
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                <option value="beginner" {{ old('current_level', $careerPath->current_level) === 'beginner' ? 'selected' : '' }}>🟢 Beginner</option>
                                <option value="intermediate" {{ old('current_level', $careerPath->current_level) === 'intermediate' ? 'selected' : '' }}>🟡 Intermediate</option>
                                <option value="advanced" {{ old('current_level', $careerPath->current_level) === 'advanced' ? 'selected' : '' }}>🔴 Advanced</option>
                            </select>
                        </div>
                        <div>
                            <label for="target_level" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Target Level <span class="text-red-500">*</span></label>
                            <select id="target_level" name="target_level" required
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                <option value="beginner" {{ old('target_level', $careerPath->target_level) === 'beginner' ? 'selected' : '' }}>🟢 Beginner</option>
                                <option value="intermediate" {{ old('target_level', $careerPath->target_level) === 'intermediate' ? 'selected' : '' }}>🟡 Intermediate</option>
                                <option value="advanced" {{ old('target_level', $careerPath->target_level) === 'advanced' ? 'selected' : '' }}>🔴 Advanced</option>
                            </select>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="grid sm:grid-cols-3 gap-4">
                        <div>
                            <label for="estimated_weeks" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Estimated Weeks</label>
                            <input type="number" id="estimated_weeks" name="estimated_weeks" value="{{ old('estimated_weeks', $careerPath->estimated_weeks) }}" min="1" max="520"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all"
                                placeholder="e.g., 12">
                        </div>
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $careerPath->start_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                        </div>
                        <div>
                            <label for="target_date" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Target Completion</label>
                            <input type="date" id="target_date" name="target_date" value="{{ old('target_date', $careerPath->target_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all @error('target_date') border-red-400 @enderror">
                            @error('target_date')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div>
                        <label for="tags" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                            Tags <span class="text-xs text-slate-400 font-normal">(comma separated)</span>
                        </label>
                        <input type="text" id="tags" name="tags" value="{{ old('tags', is_array($careerPath->tags) ? implode(', ', $careerPath->tags) : '') }}"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all"
                            placeholder="e.g., php, laravel, vue, javascript">
                    </div>

                    <div class="flex items-center gap-3 pt-5 border-t border-slate-100 dark:border-slate-700/50">
                        <button type="submit" class="px-6 py-3 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                            Update Career Path
                        </button>
                        <a href="{{ route('career-path.show', $careerPath) }}" class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-medium transition">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- RIGHT: Stats --}}
        <div class="space-y-4">
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 shadow-sm">
                <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Path Stats</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Total Tasks</span>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $careerPath->tasks->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Completed</span>
                        <span class="text-sm font-bold text-emerald-500">{{ $careerPath->tasks->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500 dark:text-slate-400">In Progress</span>
                        <span class="text-sm font-bold text-amber-500">{{ $careerPath->tasks->where('status', 'in_progress')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Progress</span>
                        <span class="text-sm font-bold text-indigo-500">{{ $careerPath->progress }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2 mt-1">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full transition-all duration-500" style="width: {{ $careerPath->progress }}%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 shadow-sm">
                <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Metadata</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Source</span><span class="font-medium text-slate-700 dark:text-slate-300">{{ ucfirst($careerPath->source) }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Created</span><span class="font-medium text-slate-700 dark:text-slate-300">{{ $careerPath->created_at->format('M d, Y') }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Updated</span><span class="font-medium text-slate-700 dark:text-slate-300">{{ $careerPath->updated_at->diffForHumans() }}</span></div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="bg-red-50 dark:bg-red-500/5 border border-red-100 dark:border-red-500/20 rounded-2xl p-5">
                <h3 class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-wider mb-3">Danger Zone</h3>
                <p class="text-[11px] text-red-500/70 mb-3">Permanently delete this career path and all its tasks, resources, and keywords.</p>
                <form action="{{ route('career-path.destroy', $careerPath) }}" method="POST"
                    onsubmit="return confirm('Are you sure? This will permanently delete this career path and ALL its tasks. This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition-all">
                        Delete Career Path
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
