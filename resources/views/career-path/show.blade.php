@extends('layouts.app')
@section('title', $careerPath->title)

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 dark:text-slate-500 mb-6 animate-fade-in">
        <a href="{{ route('career-path.index') }}" class="hover:text-indigo-500 transition">Career Paths</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        <span class="text-slate-600 dark:text-slate-300">{{ Str::limit($careerPath->title, 40) }}</span>
    </div>

    {{-- Header Card --}}
    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden mb-6 animate-fade-in-up">
        <div class="h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
        <div class="p-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $careerPath->title }}</h1>
                        <span class="text-xs px-2.5 py-1 rounded-lg font-semibold
                            {{ $careerPath->source === 'ai' ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400' : 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' }}">
                            {{ $careerPath->source === 'ai' ? '🤖 AI Generated' : '✍️ Manual' }}
                        </span>
                        <span class="text-xs px-2.5 py-1 rounded-lg font-semibold
                            @if($careerPath->status === 'active') bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400
                            @elseif($careerPath->status === 'completed') bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400
                            @elseif($careerPath->status === 'paused') bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400
                            @else bg-slate-100 dark:bg-slate-700 text-slate-500 @endif">{{ ucfirst($careerPath->status) }}</span>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">{{ $careerPath->target_role }}</p>
                    @if($careerPath->description)
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-3">{{ $careerPath->description }}</p>
                    @endif
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-400 dark:text-slate-500">
                        <span class="flex items-center gap-1">
                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 font-medium">{{ ucfirst($careerPath->current_level) }}</span>
                            →
                            <span class="px-2 py-0.5 rounded bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-medium">{{ ucfirst($careerPath->target_level) }}</span>
                        </span>
                        @if($careerPath->estimated_weeks)
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ $careerPath->estimated_weeks }} weeks
                        </span>
                        @endif
                        @if($careerPath->target_date)
                        <span class="flex items-center gap-1 {{ $careerPath->is_overdue ? 'text-red-500' : '' }}">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Target: {{ $careerPath->target_date->format('M d, Y') }}
                        </span>
                        @endif
                    </div>
                    @if($careerPath->tags)
                    <div class="flex flex-wrap gap-1.5 mt-3">
                        @foreach($careerPath->tags as $tag)
                        <span class="text-[10px] px-2 py-0.5 bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 rounded-lg">#{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('career-path.edit', $careerPath) }}" class="px-4 py-2 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-sm font-medium rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-all">Edit</a>
                    <form action="{{ route('career-path.destroy', $careerPath) }}" method="POST" onsubmit="return confirm('Delete this career path and all its tasks?')">
                        @csrf @method('DELETE')
                        <button class="px-4 py-2 bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 text-red-500 dark:text-red-400 text-sm font-medium rounded-xl hover:bg-red-100 transition-all">Delete</button>
                    </form>
                </div>
            </div>

            {{-- Progress Bar --}}
            @php $pct = $careerPath->progress; @endphp
            <div class="mt-5 pt-5 border-t border-slate-100 dark:border-slate-700/50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Overall Progress</span>
                    <span class="text-sm font-bold {{ $pct >= 100 ? 'text-emerald-500' : ($pct > 50 ? 'text-amber-500' : 'text-indigo-500') }}">{{ $pct }}%</span>
                </div>
                <div class="w-full h-3 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700 {{ $pct >= 100 ? 'bg-emerald-500' : ($pct > 50 ? 'bg-amber-500' : 'bg-indigo-500') }}" style="width:{{ $pct }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-3 sm:grid-cols-6 gap-3 mb-6">
        @foreach([
            ['label' => 'Total', 'value' => $stats['total_tasks'], 'color' => 'indigo'],
            ['label' => 'Not Started', 'value' => $stats['not_started'], 'color' => 'blue'],
            ['label' => 'In Progress', 'value' => $stats['in_progress'], 'color' => 'amber'],
            ['label' => 'Completed', 'value' => $stats['completed'], 'color' => 'emerald'],
            ['label' => 'Main Tasks', 'value' => $stats['main_tasks'], 'color' => 'purple'],
            ['label' => 'Subtasks', 'value' => $stats['subtasks'] + $stats['sub_subtasks'], 'color' => 'cyan'],
        ] as $i => $s)
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-3 text-center animate-fade-in-up stagger-{{ $i + 1 }} opacity-0 hover:scale-[1.05] hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all duration-200 cursor-default group">
            <div class="text-xl font-bold text-slate-800 dark:text-white group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors duration-200">{{ $s['value'] }}</div>
            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-medium uppercase tracking-wider mt-0.5">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Task Hierarchy --}}
    <div class="space-y-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl gradient-primary flex items-center justify-center shadow-md shadow-indigo-500/25 flex-shrink-0">
                    <svg class="w-4.5 h-4.5 text-white" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white leading-tight">Learning Tasks</h2>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 leading-tight">{{ $careerPath->mainTasks->count() }} main {{ Str::plural('task', $careerPath->mainTasks->count()) }} &mdash; build your roadmap</p>
                </div>
            </div>
        </div>

        {{-- Add Main Task Button --}}
        <career-path-task-form
            :career-path-id="{{ $careerPath->id }}"
            :parent-id="null"
            :depth="0"
            form-action="{{ route('career-path.tasks.store', $careerPath) }}"
            csrf-token="{{ csrf_token() }}"
        ></career-path-task-form>

        {{-- Task Tree --}}
        @forelse($careerPath->mainTasks as $mainIndex => $mainTask)
            {{-- LEVEL 1: Main Task --}}
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden animate-fade-in-up stagger-{{ min($mainIndex + 1, 6) }} opacity-0 group hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-500/8 dark:hover:shadow-slate-900/50 hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all duration-300">
                <div class="border-l-4 border-indigo-500 group-hover:border-indigo-600 transition-colors duration-300">
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">L1</span>
                                    <span class="text-[10px] font-medium px-2 py-0.5 rounded
                                        @if($mainTask->status === 'completed') bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400
                                        @elseif($mainTask->status === 'in_progress') bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400
                                        @else bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 @endif">{{ ucfirst(str_replace('_', ' ', $mainTask->status)) }}</span>
                                    <span class="text-[10px] font-medium px-2 py-0.5 rounded
                                        @if($mainTask->priority === 'urgent') bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400
                                        @elseif($mainTask->priority === 'high') bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400
                                        @else bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 @endif">{{ ucfirst($mainTask->priority) }}</span>
                                </div>
                                <a href="{{ route('career-path.task.show', [$careerPath, $mainTask]) }}" class="text-base font-semibold text-slate-800 dark:text-white hover:text-indigo-500 dark:hover:text-indigo-400 transition">{{ $mainTask->title }}</a>
                                @if($mainTask->description)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">{{ $mainTask->description }}</p>
                                @endif
                                <div class="flex items-center gap-3 mt-2 text-[10px] text-slate-400 dark:text-slate-500">
                                    @if($mainTask->due_date)
                                    <span class="{{ $mainTask->is_overdue ? 'text-red-500' : '' }}">Due: {{ $mainTask->due_date->format('M d, Y') }}</span>
                                    @endif
                                    @if($mainTask->estimated_hours)
                                    <span>{{ $mainTask->estimated_hours }}h estimated</span>
                                    @endif
                                    <span>{{ $mainTask->children_count }} subtasks</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 flex-shrink-0 opacity-30 group-hover:opacity-100 transition-opacity duration-200">
                                <a href="{{ route('career-path.task.show', [$careerPath, $mainTask]) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all" title="View"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                <a href="{{ route('career-path.task.edit', [$careerPath, $mainTask]) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-all" title="Edit"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></a>
                                <form action="{{ route('career-path.tasks.destroy', [$careerPath, $mainTask]) }}" method="POST" onsubmit="return confirm('Delete this task and all its subtasks?')">@csrf @method('DELETE')
                                    <button class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all" title="Delete"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </form>
                            </div>
                        </div>

                        {{-- Progress --}}
                        @php $mPct = $mainTask->progress; @endphp
                        <div class="mt-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] text-slate-400">Progress</span>
                                <span class="text-[10px] font-semibold {{ $mPct >= 100 ? 'text-emerald-500' : ($mPct > 0 ? 'text-amber-500' : 'text-slate-400') }}">{{ $mPct }}%</span>
                            </div>
                            <div class="h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $mPct >= 100 ? 'bg-emerald-500' : ($mPct > 0 ? 'bg-amber-500' : 'bg-blue-400') }}" style="width:{{ $mPct }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Task Tree: L2 subtasks + Add Subtask --}}
                    <div class="border-t border-slate-100 dark:border-slate-700/50 px-5 pt-5 pb-4">
                        <div class="relative">
                            {{-- Vertical trunk line --}}
                            <div class="absolute left-[5px] top-0 bottom-0 w-0.5 rounded-full bg-gradient-to-b from-indigo-300 via-purple-200 to-indigo-100/50 dark:from-indigo-500/35 dark:via-purple-500/20 dark:to-indigo-500/5"></div>

                            @foreach($mainTask->children as $subTask)
                            {{-- L2 item --}}
                            <div class="relative mb-4 last:mb-3">
                                {{-- L2 horizontal branch --}}
                                <div class="absolute left-[5px] top-[22px] w-5 h-0.5 rounded-full bg-purple-300 dark:bg-purple-500/40"></div>

                                {{-- L2 card --}}
                                <div class="ml-10 bg-white dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-600/40 rounded-xl overflow-hidden shadow-sm group/l2 hover:shadow-md hover:border-purple-200 dark:hover:border-purple-500/30 transition-all duration-200">
                                    <div class="border-l-2 border-purple-400 dark:border-purple-500/60">
                                        <div class="px-4 py-3">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400">L2</span>
                                                        <span class="text-[10px] font-medium px-1.5 py-0.5 rounded
                                                            @if($subTask->status === 'completed') bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400
                                                            @elseif($subTask->status === 'in_progress') bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400
                                                            @else bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 @endif">{{ ucfirst(str_replace('_', ' ', $subTask->status)) }}</span>
                                                    </div>
                                                    <a href="{{ route('career-path.task.show', [$careerPath, $subTask]) }}" class="text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-purple-500 dark:hover:text-purple-400 transition">{{ $subTask->title }}</a>
                                                    @if($subTask->description)
                                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 line-clamp-1">{{ $subTask->description }}</p>
                                                    @endif
                                                    <div class="flex items-center gap-3 mt-1.5 text-[10px] text-slate-400 dark:text-slate-500">
                                                        @if($subTask->due_date)<span class="{{ $subTask->is_overdue ? 'text-red-500' : '' }}">Due: {{ $subTask->due_date->format('M d') }}</span>@endif
                                                        @if($subTask->video_url)<span class="text-red-400">📹 Video</span>@endif
                                                        <span>{{ $subTask->children_count }} sub-subtasks</span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-1 flex-shrink-0 opacity-30 group-hover/l2:opacity-100 transition-opacity duration-200">
                                                    <a href="{{ route('career-path.task.show', [$careerPath, $subTask]) }}" class="p-1 rounded-lg text-slate-400 hover:text-purple-500 hover:bg-purple-50 dark:hover:bg-purple-500/10 transition-all"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                                    <a href="{{ route('career-path.task.edit', [$careerPath, $subTask]) }}" class="p-1 rounded-lg text-slate-400 hover:text-amber-500 transition-all"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></a>
                                                    <form action="{{ route('career-path.tasks.destroy', [$careerPath, $subTask]) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                                        <button class="p-1 rounded-lg text-slate-400 hover:text-red-500 transition-all"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                                    </form>
                                                </div>
                                            </div>

                                            {{-- L3 tree inside L2 --}}
                                            <div class="relative mt-3">
                                                {{-- L3 vertical trunk --}}
                                                <div class="absolute left-[5px] top-0 bottom-0 w-0.5 rounded-full bg-gradient-to-b from-cyan-300 via-cyan-200 to-transparent dark:from-cyan-500/30 dark:via-cyan-500/15 dark:to-transparent"></div>

                                                @foreach($subTask->children as $subSubTask)
                                                <div class="relative mb-2">
                                                    {{-- L3 branch --}}
                                                    <div class="absolute left-[5px] top-[14px] w-4 h-0.5 rounded-full bg-cyan-300 dark:bg-cyan-500/40"></div>
                                                    {{-- L3 chip --}}
                                                    <div class="ml-9 flex items-center gap-2.5 px-3 py-2 rounded-xl bg-slate-50/80 dark:bg-slate-700/25 border border-slate-100/80 dark:border-slate-700/30 hover:bg-cyan-50/60 dark:hover:bg-cyan-500/5 hover:border-cyan-200 dark:hover:border-cyan-500/20 transition-all duration-150 group/l3">
                                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex-shrink-0">L3</span>
                                                        <a href="{{ route('career-path.task.show', [$careerPath, $subSubTask]) }}" class="text-xs text-slate-600 dark:text-slate-300 hover:text-cyan-500 transition flex-1 truncate">{{ $subSubTask->title }}</a>
                                                        <span class="text-[9px] px-1.5 py-0.5 rounded font-medium flex-shrink-0
                                                            @if($subSubTask->status === 'completed') bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400
                                                            @elseif($subSubTask->status === 'in_progress') bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400
                                                            @else bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 @endif">{{ ucfirst(str_replace('_', ' ', $subSubTask->status)) }}</span>
                                                        <div class="flex items-center gap-0.5 flex-shrink-0 opacity-30 group-hover/l3:opacity-100 transition-opacity duration-200">
                                                            <a href="{{ route('career-path.task.show', [$careerPath, $subSubTask]) }}" class="p-1 rounded text-slate-400 hover:text-cyan-500 transition-all"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                                            <form action="{{ route('career-path.tasks.destroy', [$careerPath, $subSubTask]) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                                                <button class="p-1 rounded text-slate-400 hover:text-red-500 transition-all"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach

                                                {{-- Add Sub-subtask with branch --}}
                                                <div class="relative pb-1">
                                                    <div class="absolute left-[5px] top-[12px] w-4 h-0.5 rounded-full bg-cyan-200/60 dark:bg-cyan-500/15"></div>
                                                    <div class="ml-9">
                                                        <career-path-task-form
                                                            :career-path-id="{{ $careerPath->id }}"
                                                            :parent-id="{{ $subTask->id }}"
                                                            :depth="2"
                                                            form-action="{{ route('career-path.tasks.store', $careerPath) }}"
                                                            csrf-token="{{ csrf_token() }}"
                                                        ></career-path-task-form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            {{-- Add Subtask with branch --}}
                            <div class="relative">
                                <div class="absolute left-[5px] top-[12px] w-5 h-0.5 rounded-full bg-indigo-200/60 dark:bg-indigo-500/15"></div>
                                <div class="ml-10">
                                    <career-path-task-form
                                        :career-path-id="{{ $careerPath->id }}"
                                        :parent-id="{{ $mainTask->id }}"
                                        :depth="1"
                                        form-action="{{ route('career-path.tasks.store', $careerPath) }}"
                                        csrf-token="{{ csrf_token() }}"
                                    ></career-path-task-form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white dark:bg-slate-800/50 border border-dashed border-slate-200 dark:border-slate-700 rounded-2xl">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                </div>
                <p class="text-slate-600 dark:text-slate-400 text-sm font-semibold">No tasks yet</p>
                <p class="text-slate-400 dark:text-slate-500 text-xs mt-1">Add your first main task to start building your learning roadmap</p>
            </div>
        @endforelse
    </div>

@endsection
