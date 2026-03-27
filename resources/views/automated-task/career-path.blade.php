@extends('layouts.app')

@section('title', 'AI Career Path Generator')

@section('content')
<div class="space-y-8">

    {{-- ═══════════ Back Link ═══════════ --}}
    <a href="{{ route('automated-task.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Back to AI Services
    </a>

    {{-- ═══════════ Header ═══════════ --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 p-8 md:p-10">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImEiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMSIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA4KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNhKSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg==')] opacity-50"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center gap-5">
            <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white">AI Career Path Generator</h1>
                <p class="text-white/70 text-sm mt-1.5 max-w-xl">Describe your career goal in detail and AI will build a complete, structured learning roadmap — phases, subtasks, videos, resources & keywords.</p>
            </div>
        </div>
    </div>

    {{-- ═══════════ Flash Messages ═══════════ --}}
    @if(session('error'))
    <div class="flex items-start gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30">
        <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <p class="text-sm text-red-700 dark:text-red-400">{{ session('error') }}</p>
    </div>
    @endif

    {{-- ═══════════ Main Grid: Form (left) + Info Panel (right) ═══════════ --}}
    <form action="{{ route('automated-task.career-path.generate') }}" method="POST" id="ai-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── LEFT / FORM (2 cols) ── --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Career Goal Textarea --}}
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-6">
                    <label for="career_goal" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        What career do you want to pursue? <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mb-3">Be as specific and descriptive as possible — the more context you give, the better your roadmap will be.</p>
                    <textarea
                        name="career_goal"
                        id="career_goal"
                        rows="6"
                        placeholder="e.g., I want to become a Full-Stack Web Developer specialising in Laravel and Vue.js. I'm aiming to work at a mid-sized product company and eventually lead a small dev team..."
                        class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none leading-relaxed"
                        required
                        maxlength="1000"
                        autofocus
                    >{{ old('career_goal') }}</textarea>
                    <div class="flex items-center justify-between mt-2">
                        @error('career_goal')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @else
                            <span></span>
                        @enderror
                        <p class="text-xs text-slate-400 dark:text-slate-500 ml-auto" id="char-count">0 / 1000</p>
                    </div>
                </div>

                {{-- Skill Levels --}}
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-6">
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-4">Skill Level</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="current_level" class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Your Current Level</label>
                            <select name="current_level" id="current_level"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="beginner" {{ old('current_level') == 'beginner' ? 'selected' : '' }}>🌱 Beginner</option>
                                <option value="intermediate" {{ old('current_level') == 'intermediate' ? 'selected' : '' }}>⚡ Intermediate</option>
                                <option value="advanced" {{ old('current_level') == 'advanced' ? 'selected' : '' }}>🚀 Advanced</option>
                            </select>
                        </div>
                        <div>
                            <label for="target_level" class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Target Level</label>
                            <select name="target_level" id="target_level"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="beginner" {{ old('target_level') == 'beginner' ? 'selected' : '' }}>🌱 Beginner</option>
                                <option value="intermediate" {{ old('target_level') == 'intermediate' ? 'selected' : '' }}>⚡ Intermediate</option>
                                <option value="advanced" {{ old('target_level') == 'advanced' ? 'selected' : '' }} selected>🚀 Advanced</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Quiz Toggle --}}
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-purple-50 dark:bg-purple-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Include Quiz Questions</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">AI will add self-assessment questions to each subtask to test your understanding</p>
                                </div>
                                <label for="include_quiz" class="relative inline-flex items-center cursor-pointer ml-4 flex-shrink-0">
                                    <input type="hidden" name="include_quiz" value="0">
                                    <input type="checkbox" name="include_quiz" id="include_quiz" value="1"
                                           {{ old('include_quiz') ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-300 dark:bg-slate-600 peer-focus:ring-2 peer-focus:ring-indigo-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" id="submit-btn"
                        class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0 text-base">
                    <svg class="w-5 h-5" id="btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                    <span id="btn-text">Generate Career Path with AI</span>
                </button>

            </div>

            {{-- ── RIGHT / INFO PANEL (1 col) ── --}}
            <div class="space-y-5">

                {{-- What you'll get --}}
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-6">
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-4">What AI will generate</h3>
                    <div class="space-y-3">
                        @php
                        $features = [
                            ['icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7', 'label' => 'Career Path Overview', 'color' => 'text-indigo-500 bg-indigo-50 dark:bg-indigo-500/10'],
                            ['icon' => 'M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'label' => 'Main Tasks (Phases)', 'color' => 'text-purple-500 bg-purple-50 dark:bg-purple-500/10'],
                            ['icon' => 'M4 6h16M4 10h16M4 14h8', 'label' => 'Subtasks with Drill-downs', 'color' => 'text-pink-500 bg-pink-50 dark:bg-pink-500/10'],
                            ['icon' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'YouTube & Reference Videos', 'color' => 'text-red-500 bg-red-50 dark:bg-red-500/10'],
                            ['icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1', 'label' => 'Real Resource Links', 'color' => 'text-blue-500 bg-blue-50 dark:bg-blue-500/10'],
                            ['icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z', 'label' => 'Key Terms & Definitions', 'color' => 'text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10'],
                        ];
                        @endphp
                        @foreach($features as $f)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 {{ $f['color'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}" />
                                </svg>
                            </div>
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ $f['label'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Tips --}}
                <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 rounded-2xl p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-semibold text-amber-700 dark:text-amber-400 uppercase tracking-wide">Pro Tips</span>
                    </div>
                    <ul class="space-y-2 text-xs text-amber-700 dark:text-amber-400 leading-relaxed">
                        <li class="flex items-start gap-1.5"><span class="mt-0.5">•</span> Mention your industry or niche (e.g. fintech, gaming, healthcare)</li>
                        <li class="flex items-start gap-1.5"><span class="mt-0.5">•</span> Include any specific tools or tech stacks you want to learn</li>
                        <li class="flex items-start gap-1.5"><span class="mt-0.5">•</span> State your end goal — job title, freelance, startup, etc.</li>
                        <li class="flex items-start gap-1.5"><span class="mt-0.5">•</span> Generation takes 30–60 seconds — please be patient</li>
                    </ul>
                </div>

                {{-- Estimated time --}}
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Typical Timeline</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="bg-slate-50 dark:bg-slate-900/30 rounded-xl p-3">
                            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">4–6</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Main Phases</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900/30 rounded-xl p-3">
                            <p class="text-lg font-bold text-purple-600 dark:text-purple-400">20+</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Total Tasks</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

</div>

{{-- ═══════════ Full-screen AI Generation Overlay ═══════════ --}}
<div id="ai-overlay" class="hidden fixed inset-0 z-50 flex flex-col items-center justify-center" aria-live="assertive" role="status">
    {{-- Backdrop (adds cross-browser backdrop blur) --}}
    <div class="absolute inset-0 bg-slate-900/70" style="-webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);"></div>

        {{-- Card with stronger border and shadow --}}
        <div class="relative w-full max-w-md mx-4 bg-white dark:bg-slate-800 rounded-2xl overflow-hidden border border-gray-100 dark:border-slate-700/60"
            style="box-shadow: 0 30px 60px rgba(2,6,23,0.12), 0 8px 24px rgba(99,102,241,0.08);">

        {{-- Top progress bar --}}
        <div class="h-1.5 bg-slate-200 dark:bg-slate-700">
            <div id="ai-progress-bar"
                 class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 transition-all duration-700 ease-in-out rounded-full"
                 style="width: 0%"></div>
        </div>

        <div class="p-8">
            {{-- Animated icon --}}
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 relative">
                    {{-- Outer ring --}}
                    <div class="absolute inset-0 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 opacity-20 animate-ping"></div>
                    {{-- Inner circle --}}
                    <div class="relative w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-2xl ring-4 ring-white/60 dark:ring-slate-800/30">
                        <svg id="ai-icon" class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Status text --}}
            <h3 class="text-center text-lg font-bold text-slate-800 dark:text-white mb-1" id="ai-status-title">Preparing your request…</h3>
            <p class="text-center text-sm text-slate-500 dark:text-slate-400 mb-6" id="ai-status-sub">Hang tight — this usually takes 30–60 seconds</p>

            {{-- Progress % --}}
            <div class="flex justify-between items-center text-xs text-slate-400 dark:text-slate-500 mb-2">
                <span id="ai-step-label">Step 1 of 5</span>
                <span id="ai-pct">0%</span>
            </div>

            {{-- Steps --}}
            <div class="space-y-2.5" id="ai-steps">
                @php
                $steps = [
                    ['id' => 'step-1', 'label' => 'Analysing your career goal'],
                    ['id' => 'step-2', 'label' => 'Designing learning phases'],
                    ['id' => 'step-3', 'label' => 'Adding resources & videos'],
                    ['id' => 'step-4', 'label' => 'Building keywords & definitions'],
                    ['id' => 'step-5', 'label' => 'Finalising your roadmap'],
                ];
                @endphp
                @foreach($steps as $i => $step)
                <div id="{{ $step['id'] }}" class="flex items-center gap-3 p-3 rounded-xl transition-all duration-300 bg-slate-50 dark:bg-slate-700/30">
                    {{-- State icon --}}
                    <div class="step-icon w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 transition-all duration-300">
                        <span class="step-num text-xs font-bold">{{ $i + 1 }}</span>
                        <svg class="step-spinner hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg class="step-check hidden w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="step-text text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors duration-300">{{ $step['label'] }}</span>
                </div>
                @endforeach
            </div>

            {{-- Footer note --}}
            <p class="text-center text-xs text-slate-400 dark:text-slate-500 mt-5">Please don't close or refresh this page</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    try {
        // ── Character counter ──────────────────────────────────
        var textarea = document.getElementById('career_goal');
        var counter  = document.getElementById('char-count');

        if (!textarea || !counter) {
            console.warn('AI: career_goal textarea or char-count element not found.');
            return;
        }

        function updateCount() {
            counter.textContent = textarea.value.length + ' / 1000';
        }
        textarea.addEventListener('input', updateCount);
        updateCount();

        // ── Submission guard ───────────────────────────────────
        var submitted = false;

        // ── Overlay references ─────────────────────────────────
        var overlay     = document.getElementById('ai-overlay');
        var progressBar = document.getElementById('ai-progress-bar');
        var statusTitle = document.getElementById('ai-status-title');
        var statusSub   = document.getElementById('ai-status-sub');
        var stepLabel   = document.getElementById('ai-step-label');
        var pctEl       = document.getElementById('ai-pct');
        var formEl      = document.getElementById('ai-form');
        var submitBtn   = document.getElementById('submit-btn');

        if (!formEl || !submitBtn || !overlay || !progressBar || !statusTitle || !statusSub || !stepLabel || !pctEl) {
            console.warn('AI: one or more UI elements for the overlay are missing. Aborting enhanced UX script.');
            return;
        }

        var steps = [
            { id: 'step-1', title: 'Analysing your career goal…',      sub: 'Reading your input and planning the structure',   pct: 12 },
            { id: 'step-2', title: 'Designing learning phases…',        sub: 'Breaking the journey into milestones',            pct: 32 },
            { id: 'step-3', title: 'Adding resources & videos…',        sub: 'Finding real links, YouTube videos & articles',   pct: 56 },
            { id: 'step-4', title: 'Building keywords & definitions…',  sub: 'Mapping key terms at every level',                pct: 78 },
            { id: 'step-5', title: 'Finalising your roadmap…',          sub: 'Almost there — wrapping everything up',           pct: 94 },
        ];

        function setProgress(pct) {
            progressBar.style.width = pct + '%';
            pctEl.textContent = pct + '%';
        }

        function activateStep(index) {
            steps.forEach(function (s, i) {
                var el = document.getElementById(s.id);
                if (!el) return;
                var icon    = el.querySelector('.step-icon');
                var numEl   = el.querySelector('.step-num');
                var spinner = el.querySelector('.step-spinner');
                var check   = el.querySelector('.step-check');
                var text    = el.querySelector('.step-text');

                if (i < index) {
                    el.classList.remove('bg-slate-50', 'dark:bg-slate-700/30', 'bg-indigo-50/60', 'dark:bg-indigo-500/10');
                    el.classList.add('bg-emerald-50', 'dark:bg-emerald-500/10');
                    if (icon) { icon.classList.remove('bg-slate-200', 'dark:bg-slate-700', 'bg-indigo-500'); icon.classList.add('bg-emerald-500'); }
                    if (numEl) numEl.classList.add('hidden');
                    if (spinner) spinner.classList.add('hidden');
                    if (check) { check.classList.remove('hidden'); check.classList.add('text-white'); }
                    if (text) { text.classList.remove('text-slate-500', 'dark:text-slate-400'); text.classList.add('text-emerald-700', 'dark:text-emerald-400'); }
                } else if (i === index) {
                    el.classList.remove('bg-slate-50', 'dark:bg-slate-700/30', 'bg-emerald-50', 'dark:bg-emerald-500/10');
                    el.classList.add('bg-indigo-50/60', 'dark:bg-indigo-500/10');
                    if (icon) { icon.classList.remove('bg-slate-200', 'dark:bg-slate-700', 'bg-emerald-500'); icon.classList.add('bg-indigo-500'); }
                    if (numEl) numEl.classList.add('hidden');
                    if (check) check.classList.add('hidden');
                    if (spinner) { spinner.classList.remove('hidden'); }
                    if (text) { text.classList.remove('text-slate-500', 'dark:text-slate-400'); text.classList.add('text-indigo-700', 'dark:text-indigo-300', 'font-semibold'); }
                } else {
                    el.classList.remove('bg-emerald-50', 'dark:bg-emerald-500/10', 'bg-indigo-50/60', 'dark:bg-indigo-500/10');
                    el.classList.add('bg-slate-50', 'dark:bg-slate-700/30');
                    if (icon) { icon.classList.remove('bg-indigo-500', 'bg-emerald-500'); icon.classList.add('bg-slate-200', 'dark:bg-slate-700'); }
                    if (numEl) numEl.classList.remove('hidden');
                    if (spinner) spinner.classList.add('hidden');
                    if (check) check.classList.add('hidden');
                    if (text) { text.classList.remove('text-indigo-700', 'dark:text-indigo-300', 'font-semibold', 'text-emerald-700', 'dark:text-emerald-400'); text.classList.add('text-slate-500', 'dark:text-slate-400'); }
                }
            });

            if (index < steps.length) {
                statusTitle.textContent = steps[index].title;
                statusSub.textContent   = steps[index].sub;
                stepLabel.textContent   = 'Step ' + (index + 1) + ' of ' + steps.length;
                setProgress(steps[index].pct);
            }
        }

        function showOverlay() {
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            activateStep(0);
        }

        // Auto-advance steps on a realistic timer
        var stepTimings = [0, 6000, 14000, 22000, 30000]; // ms after submit
        var timers = [];

        function scheduleSteps() {
            steps.forEach(function (s, i) {
                if (i === 0) return; // step 0 shown immediately
                var t = setTimeout(function () { activateStep(i); }, stepTimings[i]);
                timers.push(t);
            });
        }

        // Clear scheduled timers (useful if user navigates away)
        function clearTimers() { timers.forEach(function (t) { clearTimeout(t); }); timers = []; }

        // ── Form submit ────────────────────────────────────────
        formEl.addEventListener('submit', function (e) {
            try {
                if (submitted) {
                    e.preventDefault();
                    return;
                }

                if (!textarea.value.trim()) return; // let HTML5 validation handle

                submitted = true;

                // Disable submit button visually and from pointer events
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
                submitBtn.setAttribute('aria-disabled', 'true');

                showOverlay();
                scheduleSteps();

                // allow form to submit to server normally — overlay will persist while request completes
            } catch (err) {
                console.error('AI submit handler error', err);
            }
        });

        // Clear timers when unloading
        window.addEventListener('beforeunload', function () { clearTimers(); });

    } catch (err) {
        console.error('AI overlay script failed to initialize', err);
    }
});
</script>
@endpush
