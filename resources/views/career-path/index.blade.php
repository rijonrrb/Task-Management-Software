@extends('layouts.app')
@section('title', 'Career Paths')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">Career Path Builder</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Build structured learning roadmaps to master new skills and advance your career.</p>
        </div>
        <a href="{{ route('career-path.create') }}"
           class="inline-flex items-center gap-2 px-5 py-3 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            New Career Path
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 hover-lift card-shine animate-fade-in-up stagger-1 opacity-0">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Paths</span>
            </div>
            <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $counts['total'] }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 hover-lift card-shine animate-fade-in-up stagger-2 opacity-0">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Active</span>
            </div>
            <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $counts['active'] }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 hover-lift card-shine animate-fade-in-up stagger-3 opacity-0">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Completed</span>
            </div>
            <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $counts['completed'] }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 hover-lift card-shine animate-fade-in-up stagger-4 opacity-0">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-purple-50 dark:bg-purple-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" /></svg>
                </div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">AI Generated</span>
            </div>
            <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $counts['ai'] }}</div>
        </div>
    </div>

    {{-- Source Tabs --}}
    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-6 mb-6 space-y-5 animate-fade-in-up stagger-5 opacity-0">
        <div>
            <p class="text-xs font-semibold tracking-widest text-slate-400 dark:text-slate-500 uppercase mb-3">Filter by</p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('career-path.index') }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ !request('status') && !request('source') ? 'gradient-primary text-white shadow-md shadow-indigo-500/20' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                    All {{ $counts['total'] }}
                </a>
                <a href="{{ route('career-path.index', ['status' => 'active']) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ request('status') === 'active' ? 'bg-emerald-500 text-white shadow-md' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                    Active {{ $counts['active'] }}
                </a>
                <a href="{{ route('career-path.index', ['status' => 'completed']) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ request('status') === 'completed' ? 'bg-blue-500 text-white shadow-md' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                    Completed {{ $counts['completed'] }}
                </a>
                <span class="w-px h-8 bg-slate-200 dark:bg-slate-700 mx-1 self-center"></span>
                <a href="{{ route('career-path.index', ['source' => 'manual']) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ request('source') === 'manual' ? 'bg-amber-500 text-white shadow-md' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                    ✍️ Manual {{ $counts['manual'] }}
                </a>
                <a href="{{ route('career-path.index', ['source' => 'ai']) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ request('source') === 'ai' ? 'bg-purple-500 text-white shadow-md' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                    🤖 AI Generated {{ $counts['ai'] }}
                </a>
            </div>
        </div>

        {{-- Search --}}
        <form action="{{ route('career-path.index') }}" method="GET" class="flex gap-3">
            @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            @if(request('source'))<input type="hidden" name="source" value="{{ request('source') }}">@endif
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search career paths..."
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-all btn-press">Search</button>
        </form>
    </div>

    {{-- Career Paths Grid --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($careerPaths as $index => $path)
            @php
                $totalTasks = $path->tasks->count();
                $completedTasks = $path->tasks->where('status', 'completed')->count();
                $pct = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                $barColor = $pct >= 100 ? '#10b981' : ($pct > 50 ? '#f59e0b' : '#6366f1');
            @endphp
            <div class="block bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden hover-lift hover-glow card-shine group animate-fade-in-up stagger-{{ min($index + 1, 8) }} opacity-0">
                {{-- Top bar --}}
                <div class="h-1.5" style="background: linear-gradient(90deg, {{ $barColor }}, {{ $barColor }}80);"></div>
                <div class="p-5">
                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('career-path.show', $path) }}" class="block">
                                <h3 class="font-semibold text-sm text-slate-800 dark:text-white leading-snug group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition truncate">{{ $path->title }}</h3>
                            </a>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $path->target_role }}</p>
                            @if($path->is_pinned)
                                <p class="text-[11px] text-amber-500 font-medium mt-1">Pinned path</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] px-2 py-0.5 rounded-lg font-semibold flex-shrink-0
                                {{ $path->source === 'ai' ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400' : 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' }}">
                                {{ $path->source === 'ai' ? '🤖 AI' : '✍️ Manual' }}
                            </span>
                            <form action="{{ route('career-path.pin', $path) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="p-1.5 rounded-lg {{ $path->is_pinned ? 'text-amber-500 bg-amber-50 dark:bg-amber-500/10' : 'text-slate-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10' }} transition-colors" title="{{ $path->is_pinned ? 'Unpin career path' : 'Pin career path' }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.5 2.5a1 1 0 011 0l4 2.3a1 1 0 01.5.87V9l1.4 1.4a1 1 0 01-.7 1.7H11v4.5a1 1 0 11-2 0V12.1H5.3a1 1 0 01-.7-1.7L6 9V5.67a1 1 0 01.5-.87l2-1.15z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($path->description)
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-3 line-clamp-2">{{ $path->description }}</p>
                    @endif

                    {{-- Level badges --}}
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-[10px] px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 font-medium">{{ ucfirst($path->current_level) }}</span>
                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        <span class="text-[10px] px-2 py-0.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-medium">{{ ucfirst($path->target_level) }}</span>
                    </div>

                    {{-- Tags --}}
                    @if($path->tags)
                    <div class="flex flex-wrap gap-1 mb-3">
                        @foreach(array_slice($path->tags, 0, 4) as $tag)
                        <span class="text-[10px] px-1.5 py-0.5 bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 rounded">#{{ $tag }}</span>
                        @endforeach
                        @if(count($path->tags) > 4)
                        <span class="text-[10px] px-1.5 py-0.5 text-slate-400">+{{ count($path->tags) - 4 }}</span>
                        @endif
                    </div>
                    @endif

                    {{-- Progress --}}
                    <div class="mb-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] text-slate-400 dark:text-slate-500">Progress</span>
                            <span class="text-[10px] font-semibold" style="color: {{ $barColor }}">{{ $pct }}%</span>
                        </div>
                        <div class="h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500" style="width:{{ $pct }}%; background-color:{{ $barColor }}"></div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-700/50">
                        <div class="flex items-center gap-3 text-xs text-slate-400 dark:text-slate-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" /></svg>
                                {{ $totalTasks }} tasks
                            </span>
                            @if($path->estimated_weeks)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ $path->estimated_weeks }}w
                            </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex px-2 py-0.5 rounded-lg text-[10px] font-semibold
                            @if($path->status === 'active') bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400
                            @elseif($path->status === 'completed') bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400
                            @elseif($path->status === 'paused') bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400
                            @else bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400
                            @endif">{{ ucfirst($path->status) }}</span>
                            <a href="{{ route('career-path.show', $path) }}" class="text-[11px] px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-medium hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-colors">Open</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 lg:col-span-3 text-center py-20 bg-white dark:bg-slate-800/50 border border-dashed border-slate-200 dark:border-slate-700 rounded-2xl animate-fade-in-up">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>
                </div>
                <p class="text-slate-600 dark:text-slate-400 text-sm font-semibold">No career paths yet</p>
                <p class="text-slate-400 dark:text-slate-500 text-xs mt-1">Create your first learning roadmap to start building skills</p>
                <a href="{{ route('career-path.create') }}" class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 gradient-primary text-white text-sm font-medium rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all btn-press">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Create Career Path
                </a>
            </div>
        @endforelse
    </div>

    @if($careerPaths->hasPages())
    <div class="mt-8">{{ $careerPaths->links() }}</div>
    @endif

@endsection
