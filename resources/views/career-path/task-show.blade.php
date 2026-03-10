@extends('layouts.app')
@section('title', $task->title)

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 dark:text-slate-500 mb-6 animate-fade-in">
        <a href="{{ route('career-path.index') }}" class="hover:text-indigo-500 transition">Career Paths</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        <a href="{{ route('career-path.show', $careerPath) }}" class="hover:text-indigo-500 transition">{{ Str::limit($careerPath->title, 25) }}</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        <span class="text-slate-600 dark:text-slate-300">{{ Str::limit($task->title, 30) }}</span>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 animate-fade-in-up">

        {{-- ═══════════ LEFT: Main Content Area ═══════════ --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Task Header --}}
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
                @php
                    $depthColors = ['indigo', 'purple', 'cyan'];
                    $dc = $depthColors[$task->depth] ?? 'indigo';
                @endphp
                <div class="h-1.5 bg-gradient-to-r from-{{ $dc }}-500 to-{{ $dc }}-400"></div>
                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-{{ $dc }}-50 dark:bg-{{ $dc }}-500/10 text-{{ $dc }}-600 dark:text-{{ $dc }}-400">{{ $task->depth_label }}</span>
                        <span class="text-[10px] font-medium px-2 py-0.5 rounded
                            @if($task->status === 'completed') bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400
                            @elseif($task->status === 'in_progress') bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400
                            @elseif($task->status === 'skipped') bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400
                            @else bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 @endif">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                        <span class="text-[10px] font-medium px-2 py-0.5 rounded
                            @if($task->priority === 'urgent') bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400
                            @elseif($task->priority === 'high') bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400
                            @elseif($task->priority === 'medium') bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400
                            @else bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 @endif">{{ ucfirst($task->priority) }} priority</span>
                        @if($task->is_overdue)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 animate-pulse">⚠ Overdue</span>
                        @endif
                        @if($task->source === 'ai')
                        <span class="text-[10px] font-medium px-2 py-0.5 rounded bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400">🤖 AI Generated</span>
                        @endif
                    </div>
                    <h1 class="text-xl font-bold text-slate-800 dark:text-white mb-2 {{ $task->status === 'completed' ? 'line-through opacity-60' : '' }}">{{ $task->title }}</h1>
                    @if($task->parent)
                    <p class="text-xs text-slate-400 dark:text-slate-500 mb-3">
                        Part of: <a href="{{ route('career-path.task.show', [$careerPath, $task->parent]) }}" class="text-indigo-500 hover:underline">{{ $task->parent->title }}</a>
                    </p>
                    @endif
                    @if($task->description)
                    <div class="p-4 bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-100 dark:border-slate-700/50 mb-3">
                        <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $task->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Video / Content Area --}}
            @if($task->video_url)
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h2 class="text-sm font-semibold text-slate-700 dark:text-white">Learning Video</h2>
                        @if($task->duration_minutes)
                        <span class="text-[10px] px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 ml-auto">{{ $task->duration_minutes }} min</span>
                        @endif
                    </div>
                </div>
                <div class="aspect-video bg-slate-900">
                    @if($task->video_embed_url)
                    <iframe src="{{ $task->video_embed_url }}" class="w-full h-full" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <a href="{{ $task->video_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            Open Video
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Rich Content --}}
            @if($task->content)
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                    <h2 class="text-sm font-semibold text-slate-700 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Learning Content
                    </h2>
                </div>
                <div class="p-6">
                    <div class="prose prose-sm dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $task->content }}</div>
                </div>
            </div>
            @endif

            {{-- Child Tasks (if any) --}}
            @if($task->children->isNotEmpty())
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                    <h2 class="text-sm font-semibold text-slate-700 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                        {{ $task->depth === 0 ? 'Subtasks' : 'Sub-subtasks' }} ({{ $task->children->count() }})
                    </h2>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @foreach($task->children as $child)
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-slate-50 dark:hover:bg-slate-700/20 transition group">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                            @if($child->status === 'completed') bg-emerald-50 dark:bg-emerald-500/10
                            @elseif($child->status === 'in_progress') bg-amber-50 dark:bg-amber-500/10
                            @else bg-blue-50 dark:bg-blue-500/10 @endif">
                            @if($child->status === 'completed')
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            @elseif($child->status === 'in_progress')
                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            @else
                            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" /></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('career-path.task.show', [$careerPath, $child]) }}" class="text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-indigo-500 transition truncate block">{{ $child->title }}</a>
                            <div class="flex items-center gap-2 mt-0.5 text-[10px] text-slate-400">
                                @if($child->due_date)<span>Due: {{ $child->due_date->format('M d') }}</span>@endif
                                @if($child->duration_minutes)<span>{{ $child->duration_minutes }}min</span>@endif
                                @if($child->video_url)<span class="text-red-400">📹</span>@endif
                            </div>
                        </div>
                        <span class="text-[10px] px-2 py-0.5 rounded font-medium flex-shrink-0
                            @if($child->status === 'completed') bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400
                            @elseif($child->status === 'in_progress') bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400
                            @else bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 @endif">{{ ucfirst(str_replace('_', ' ', $child->status)) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Quick Actions Bar --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('career-path.task.edit', [$careerPath, $task]) }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-sm font-medium rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Edit Task
                </a>
                <a href="{{ route('career-path.show', $careerPath) }}" class="text-sm text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    Back to Roadmap
                </a>
            </div>
        </div>

        {{-- ═══════════ RIGHT: Sidebar ═══════════ --}}
        <div class="space-y-4">

            {{-- Reference Links --}}
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                    <h2 class="text-sm font-semibold text-slate-700 dark:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                        Reference Links
                    </h2>
                </div>
                <div class="p-4">
                    @if($task->resources->isNotEmpty())
                    <div class="space-y-2.5">
                        @foreach($task->resources as $resource)
                        <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer"
                           class="flex items-start gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-700/50 hover:border-indigo-200 dark:hover:border-indigo-500/30 hover:bg-indigo-50/50 dark:hover:bg-indigo-500/5 transition-all group">
                            <span class="text-base flex-shrink-0 mt-0.5">{{ $resource->type_icon }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition truncate">{{ $resource->title }}</p>
                                @if($resource->provider)
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $resource->provider }}</p>
                                @endif
                                @if($resource->description)
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 line-clamp-2">{{ $resource->description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                @if($resource->is_free)
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-medium">Free</span>
                                @else
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 font-medium">Paid</span>
                                @endif
                                <svg class="w-3 h-3 text-slate-400 group-hover:text-indigo-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-xs text-slate-400 dark:text-slate-500 text-center py-4">No resources added yet</p>
                    @endif
                </div>
            </div>

            {{-- Important Keywords --}}
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                    <h2 class="text-sm font-semibold text-slate-700 dark:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                        Key Concepts
                    </h2>
                </div>
                <div class="p-4">
                    @if($task->keywords->isNotEmpty())
                    <div class="space-y-2.5">
                        @foreach($task->keywords as $keyword)
                        <div class="p-3 rounded-xl border border-slate-100 dark:border-slate-700/50 bg-slate-50/30 dark:bg-slate-700/20">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $keyword->keyword }}</span>
                                <span class="text-[9px] px-1.5 py-0.5 rounded font-medium
                                    @if($keyword->importance === 'essential') bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400
                                    @elseif($keyword->importance === 'important') bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400
                                    @else bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 @endif">{{ $keyword->importance_label }}</span>
                            </div>
                            @if($keyword->definition)
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">{{ $keyword->definition }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-xs text-slate-400 dark:text-slate-500 text-center py-4">No keywords added yet</p>
                    @endif
                </div>
            </div>

            {{-- Duration & Info --}}
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                    <h2 class="text-sm font-semibold text-slate-700 dark:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Task Details
                    </h2>
                </div>
                <div class="p-4 space-y-0">
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Status</span>
                        <span class="text-xs font-semibold
                            @if($task->status === 'completed') text-emerald-500
                            @elseif($task->status === 'in_progress') text-amber-500
                            @else text-blue-500 @endif">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Priority</span>
                        <span class="text-xs font-semibold
                            @if($task->priority === 'urgent') text-red-500
                            @elseif($task->priority === 'high') text-orange-500
                            @elseif($task->priority === 'medium') text-yellow-500
                            @else text-slate-500 @endif">{{ ucfirst($task->priority) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Depth Level</span>
                        <span class="text-xs font-semibold text-{{ $dc }}-500">{{ $task->depth_label }}</span>
                    </div>
                    @if($task->estimated_hours)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Estimated Hours</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $task->estimated_hours }}h</span>
                    </div>
                    @endif
                    @if($task->duration_minutes)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Video Duration</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $task->duration_minutes }} min</span>
                    </div>
                    @endif
                    @if($task->start_date)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Start Date</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $task->start_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($task->due_date)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Due Date</span>
                        <span class="text-xs font-semibold {{ $task->is_overdue ? 'text-red-500' : 'text-slate-700 dark:text-slate-300' }}">{{ $task->due_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($task->completed_at)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Completed</span>
                        <span class="text-xs font-semibold text-emerald-500">{{ $task->completed_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between py-2.5">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Created</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $task->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Status Update --}}
            @if(!in_array($task->status, ['completed', 'skipped']))
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                    <h2 class="text-sm font-semibold text-slate-700 dark:text-white">Quick Status Update</h2>
                </div>
                <div class="p-4">
                    <career-path-status-updater
                        :task-id="{{ $task->id }}"
                        current-status="{{ $task->status }}"
                        csrf-token="{{ csrf_token() }}"
                    ></career-path-status-updater>
                </div>
            </div>
            @endif
        </div>
    </div>

@endsection
