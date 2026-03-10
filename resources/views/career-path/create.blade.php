@extends('layouts.app')
@section('title', 'Create Career Path')

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 dark:text-slate-500 mb-6 animate-fade-in">
        <a href="{{ route('career-path.index') }}" class="hover:text-indigo-500 transition">Career Paths</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        <span class="text-slate-600 dark:text-slate-300">Create New</span>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 animate-fade-in-up">
        {{-- LEFT: Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342" /></svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-slate-800 dark:text-white">Create Career Path</h1>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Define your learning journey and build structured tasks</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('career-path.store') }}" method="POST" class="px-6 py-6 space-y-5">
                    @csrf

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                            Path Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all @error('title') border-red-400 @enderror"
                            placeholder="e.g., Master Full-Stack Web Development">
                        @error('title')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Target Role --}}
                    <div>
                        <label for="target_role" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                            Target Role / Skill <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="target_role" name="target_role" value="{{ old('target_role') }}" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all @error('target_role') border-red-400 @enderror"
                            placeholder="e.g., Full-Stack Developer, Data Scientist, UI/UX Designer">
                        @error('target_role')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all"
                            placeholder="Describe your learning goals, what you want to achieve...">{{ old('description') }}</textarea>
                    </div>

                    {{-- Level selects --}}
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="current_level" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Current Level <span class="text-red-500">*</span></label>
                            <select id="current_level" name="current_level" required
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                <option value="beginner" {{ old('current_level', 'beginner') === 'beginner' ? 'selected' : '' }}>🟢 Beginner</option>
                                <option value="intermediate" {{ old('current_level') === 'intermediate' ? 'selected' : '' }}>🟡 Intermediate</option>
                                <option value="advanced" {{ old('current_level') === 'advanced' ? 'selected' : '' }}>🔴 Advanced</option>
                            </select>
                        </div>
                        <div>
                            <label for="target_level" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Target Level <span class="text-red-500">*</span></label>
                            <select id="target_level" name="target_level" required
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                <option value="beginner" {{ old('target_level') === 'beginner' ? 'selected' : '' }}>🟢 Beginner</option>
                                <option value="intermediate" {{ old('target_level') === 'intermediate' ? 'selected' : '' }}>🟡 Intermediate</option>
                                <option value="advanced" {{ old('target_level', 'advanced') === 'advanced' ? 'selected' : '' }}>🔴 Advanced</option>
                            </select>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="grid sm:grid-cols-3 gap-4">
                        <div>
                            <label for="estimated_weeks" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Estimated Weeks</label>
                            <input type="number" id="estimated_weeks" name="estimated_weeks" value="{{ old('estimated_weeks') }}" min="1" max="520"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all"
                                placeholder="e.g., 12">
                        </div>
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                        </div>
                        <div>
                            <label for="target_date" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Target Completion</label>
                            <input type="date" id="target_date" name="target_date" value="{{ old('target_date') }}"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all @error('target_date') border-red-400 @enderror">
                            @error('target_date')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div>
                        <label for="tags" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                            Tags <span class="text-xs text-slate-400 font-normal">(comma separated)</span>
                        </label>
                        <input type="text" id="tags" name="tags" value="{{ old('tags') }}"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all"
                            placeholder="e.g., php, laravel, vue, javascript">
                    </div>

                    <div class="flex items-center gap-3 pt-5 border-t border-slate-100 dark:border-slate-700/50">
                        <button type="submit" class="px-6 py-3 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                            Create Career Path
                        </button>
                        <a href="{{ route('career-path.index') }}" class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-medium transition">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- RIGHT: Guide --}}
        <div class="space-y-4">
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-5 text-white shadow-lg shadow-indigo-500/20">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                    <h3 class="font-semibold text-sm">How It Works</h3>
                </div>
                <ul class="space-y-2.5 text-xs text-indigo-100">
                    <li class="flex items-start gap-2"><span class="mt-0.5 w-5 h-5 flex items-center justify-center rounded-full bg-white/20 text-[10px] font-bold flex-shrink-0">1</span> Create a career path with your target role</li>
                    <li class="flex items-start gap-2"><span class="mt-0.5 w-5 h-5 flex items-center justify-center rounded-full bg-white/20 text-[10px] font-bold flex-shrink-0">2</span> Add main tasks (learning modules/phases)</li>
                    <li class="flex items-start gap-2"><span class="mt-0.5 w-5 h-5 flex items-center justify-center rounded-full bg-white/20 text-[10px] font-bold flex-shrink-0">3</span> Break each into subtasks with resources</li>
                    <li class="flex items-start gap-2"><span class="mt-0.5 w-5 h-5 flex items-center justify-center rounded-full bg-white/20 text-[10px] font-bold flex-shrink-0">4</span> Add videos, links, and keywords per task</li>
                    <li class="flex items-start gap-2"><span class="mt-0.5 w-5 h-5 flex items-center justify-center rounded-full bg-white/20 text-[10px] font-bold flex-shrink-0">5</span> Track your progress as you learn</li>
                </ul>
            </div>

            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-4 shadow-sm">
                <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">3-Layer Structure</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">L1</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">Main Task</p>
                            <p class="text-[10px] text-slate-400">Learning phase or module (e.g., "Frontend Fundamentals")</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 ml-4">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-bold text-purple-600 dark:text-purple-400">L2</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">Subtask</p>
                            <p class="text-[10px] text-slate-400">Specific topic (e.g., "Learn HTML5 Semantic Elements")</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 ml-8">
                        <div class="w-8 h-8 rounded-lg bg-cyan-50 dark:bg-cyan-500/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-bold text-cyan-600 dark:text-cyan-400">L3</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">Sub-subtask</p>
                            <p class="text-[10px] text-slate-400">Granular exercise (e.g., "Build a form with validation")</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-4 shadow-sm">
                <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Popular Paths</h3>
                <div class="space-y-2">
                    @foreach(['Full-Stack Developer', 'Data Scientist', 'DevOps Engineer', 'UI/UX Designer', 'Mobile Developer', 'Cloud Architect'] as $role)
                    <div class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/30 transition cursor-pointer" onclick="document.getElementById('target_role').value='{{ $role }}'">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        <span class="text-xs text-slate-600 dark:text-slate-300">{{ $role }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
