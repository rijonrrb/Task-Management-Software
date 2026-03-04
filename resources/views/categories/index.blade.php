@extends('layouts.app')
@section('title', 'Categories')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Categories</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Organize your tasks into color-coded categories</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
            <span class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-700 dark:text-white">{{ $categories->count() }}</span>
            <span>categories total</span>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- LEFT: Categories List (2 cols) --}}
        <div class="lg:col-span-2 animate-fade-in-up stagger-1 opacity-0">
            <div class="space-y-2">
                @forelse($categories as $category)
                    <div class="group bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-4 hover:border-indigo-300 dark:hover:border-indigo-500/40 hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 min-w-0">
                                <div style="width:18px;height:18px;border-radius:50%;background-color:{{ $category->color ?? '#6366f1' }};flex-shrink:0;box-shadow:0 0 0 3px {{ $category->color ?? '#6366f1' }}25;"></div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $category->name }}</h3>
                                    @if($category->description)
                                        <p class="text-xs text-slate-400 dark:text-slate-500 truncate">{{ $category->description }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-3 flex-shrink-0">
                                <a href="{{ route('tasks.index', ['category' => $category->id]) }}"
                                   class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-indigo-500 transition px-2.5 py-1 rounded-lg bg-slate-50 dark:bg-slate-700/50 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 border border-slate-200 dark:border-slate-600">
                                    {{ $category->tasks_count }} {{ Str::plural('task', $category->tasks_count) }}
                                </a>

                                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                      onsubmit="return confirm('Delete this category? Tasks will become uncategorized.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-300 dark:text-slate-600 hover:text-red-500 transition p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10 opacity-0 group-hover:opacity-100">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Mini progress bar showing task share --}}
                        @if($categories->sum('tasks_count') > 0)
                        @php $pct = $categories->sum('tasks_count') > 0 ? round(($category->tasks_count / $categories->sum('tasks_count')) * 100) : 0; @endphp
                        <div class="mt-3 flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700" style="width:{{ $pct }}%;background-color:{{ $category->color ?? '#6366f1' }};"></div>
                            </div>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium w-8 text-right">{{ $pct }}%</span>
                        </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-14 bg-white dark:bg-slate-800/30 border border-dashed border-slate-300 dark:border-slate-700/50 rounded-2xl">
                        <div class="w-14 h-14 mx-auto bg-slate-100 dark:bg-slate-700/50 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">No categories yet</p>
                        <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Create your first category using the form.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT: Create Form + Stats (1 col) --}}
        <div class="space-y-5 animate-fade-in-up stagger-2 opacity-0">

            {{-- Create Category Form --}}
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                    <h2 class="text-sm font-bold text-slate-700 dark:text-white flex items-center gap-2">
                        <div class="w-6 h-6 gradient-primary rounded-lg flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        New Category
                    </h2>
                </div>
                <form action="{{ route('categories.store') }}" method="POST" class="px-5 py-4 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g., Feature, Bug, Docs"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5">Color</label>
                        <div class="flex items-center gap-3">
                            <div id="color-preview" style="width:36px;height:36px;border-radius:50%;background:#6366f1;flex-shrink:0;border:3px solid white;box-shadow:0 0 0 2px #6366f155;transition:all 0.2s;cursor:pointer;" onclick="document.getElementById('coloris-input').click()"></div>
                            <input type="text" id="coloris-input" name="color" value="{{ old('color', '#6366f1') }}"
                                data-coloris
                                class="flex-1 px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all font-mono"
                                readonly>
                        </div>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach(['#6366f1','#8b5cf6','#ec4899','#ef4444','#f97316','#eab308','#10b981','#06b6d4','#3b82f6','#84cc16'] as $swatch)
                            <button type="button" onclick="setColor('{{ $swatch }}')"
                                style="width:20px;height:20px;border-radius:50%;background:{{ $swatch }};border:2px solid white;box-shadow:0 0 0 1px {{ $swatch }}60;transition:transform 0.15s;"
                                class="hover:scale-125 focus:outline-none"></button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5">Description <span class="text-slate-400">(optional)</span></label>
                        <input type="text" name="description" value="{{ old('description') }}" placeholder="Brief description..."
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 transition-all">
                    </div>

                    <button type="submit" class="w-full px-5 py-2.5 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                        Create Category
                    </button>
                </form>
            </div>

            {{-- Stats --}}
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 shadow-sm">
                <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">Overview</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-3 text-center">
                        <div class="text-2xl font-bold text-slate-800 dark:text-white">{{ $categories->count() }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Categories</div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-3 text-center">
                        <div class="text-2xl font-bold text-indigo-500">{{ $categories->sum('tasks_count') }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total Tasks</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
function setColor(hex) {
    document.getElementById('coloris-input').value = hex;
    document.getElementById('color-preview').style.background = hex;
    document.getElementById('color-preview').style.boxShadow = '0 0 0 2px ' + hex + '55';
}

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('coloris-input');
    const preview = document.getElementById('color-preview');
    if (input && preview) {
        input.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                preview.style.background = this.value;
                preview.style.boxShadow = '0 0 0 2px ' + this.value + '55';
            }
        });
        input.addEventListener('change', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                preview.style.background = this.value;
                preview.style.boxShadow = '0 0 0 2px ' + this.value + '55';
            }
        });
    }
});
</script>
@endsection
