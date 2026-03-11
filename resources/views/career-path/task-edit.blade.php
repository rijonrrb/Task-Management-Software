@extends('layouts.app')
@section('title', 'Edit Task: ' . $task->title)

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 dark:text-slate-500 mb-6 animate-fade-in">
        <a href="{{ route('career-path.index') }}" class="hover:text-indigo-500 transition">Career Paths</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        <a href="{{ route('career-path.show', $careerPath) }}" class="hover:text-indigo-500 transition">{{ Str::limit($careerPath->title, 20) }}</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        <span class="text-slate-600 dark:text-slate-300">Edit Task</span>
    </div>

    <div class="max-w-4xl animate-fade-in-up">
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                <div class="flex items-center gap-3">
                    @php
                        $depthColors = ['indigo', 'purple', 'cyan'];
                        $dc = $depthColors[$task->depth] ?? 'indigo';
                    @endphp
                    <div class="w-10 h-10 bg-gradient-to-br from-{{ $dc }}-500 to-{{ $dc }}-400 rounded-xl flex items-center justify-center shadow-lg shadow-{{ $dc }}-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-800 dark:text-white">Edit {{ $task->depth_label }}</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Update task details, resources, and keywords</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('career-path.task.update', [$careerPath, $task]) }}" method="POST" class="px-6 py-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Basic Info Section --}}
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-white flex items-center gap-2">
                        <div class="w-6 h-6 rounded bg-{{ $dc }}-50 dark:bg-{{ $dc }}-500/10 flex items-center justify-center">
                            <span class="text-[10px] font-bold text-{{ $dc }}-600 dark:text-{{ $dc }}-400">1</span>
                        </div>
                        Basic Information
                    </h3>

                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $task->title) }}" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all @error('title') border-red-400 @enderror">
                        @error('title')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Description</label>
                        <textarea name="description" rows="2"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">{{ old('description', $task->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Content <span class="text-xs text-slate-400 font-normal">(rich learning content)</span></label>
                        <textarea name="content" rows="5"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all"
                            placeholder="Write detailed learning content, instructions, notes...">{{ old('content', $task->content) }}</textarea>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                <option value="not_started" {{ old('status', $task->status) === 'not_started' ? 'selected' : '' }}>Not Started</option>
                                <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="skipped" {{ old('status', $task->status) === 'skipped' ? 'selected' : '' }}>Skipped</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Priority</label>
                            <select name="priority" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority', $task->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Estimated Hours</label>
                            <input type="number" name="estimated_hours" value="{{ old('estimated_hours', $task->estimated_hours) }}" step="0.5" min="0"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Start Date</label>
                            <input type="date" name="start_date" value="{{ old('start_date', $task->start_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Due Date</label>
                            <input type="date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all @error('due_date') border-red-400 @enderror">
                            @error('due_date')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Video Content Section --}}
                <div class="space-y-4 pt-5 border-t border-slate-100 dark:border-slate-700/50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-white flex items-center gap-2">
                            <div class="w-6 h-6 rounded bg-red-50 dark:bg-red-500/10 flex items-center justify-center">
                                <span class="text-[10px] font-bold text-red-600 dark:text-red-400">2</span>
                            </div>
                            Video Content
                        </h3>
                        <button type="button" onclick="addVideo()" class="text-xs text-red-500 hover:text-red-600 font-medium flex items-center gap-1 transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Video
                        </button>
                    </div>
                    @php
                        $existingVideos = old('videos', $task->resources->where('type', 'video')->values()->toArray());
                    @endphp
                    <div id="videos-container" class="space-y-3">
                        @forelse($existingVideos as $vi => $video)
                        <div class="video-row flex items-center gap-3 p-3 bg-red-50/40 dark:bg-red-500/5 border border-red-100 dark:border-red-500/20 rounded-xl relative group">
                            <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <input type="text" name="videos[{{ $vi }}][title]" value="{{ is_array($video) ? ($video['title'] ?? '') : '' }}" placeholder="Label (optional)"
                                class="w-1/3 px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-red-400 transition-all">
                            <input type="url" name="videos[{{ $vi }}][url]" value="{{ is_array($video) ? ($video['url'] ?? '') : '' }}" placeholder="https://youtube.com/watch?v=..."
                                class="flex-1 px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-red-400 transition-all">
                            <button type="button" onclick="this.closest('.video-row').remove()" class="w-7 h-7 rounded-full bg-red-100 dark:bg-red-500/15 text-red-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs hover:bg-red-200 flex-shrink-0">✕</button>
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 dark:text-slate-500 italic" id="videos-empty">No videos added yet. Click "Add Video" to add one.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Resources Section --}}
                <div class="space-y-4 pt-5 border-t border-slate-100 dark:border-slate-700/50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-white flex items-center gap-2">
                            <div class="w-6 h-6 rounded bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center">
                                <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400">3</span>
                            </div>
                            Reference Links
                        </h3>
                        <button type="button" onclick="addResource()" class="text-xs text-indigo-500 hover:text-indigo-600 font-medium flex items-center gap-1 transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Link
                        </button>
                    </div>
                    <div id="resources-container" class="space-y-3">
                        @foreach(old('resources', $task->resources->where('type', '!=', 'video')->values()->toArray()) as $i => $resource)
                        <div class="resource-row p-4 bg-slate-50/50 dark:bg-slate-700/20 border border-slate-100 dark:border-slate-700/50 rounded-xl relative group" data-index="{{ $i }}">
                            <button type="button" onclick="this.closest('.resource-row').remove()" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs">✕</button>
                            <div class="grid sm:grid-cols-4 gap-3">
                                <div class="sm:col-span-2">
                                    <input type="text" name="resources[{{ $i }}][title]" value="{{ is_array($resource) ? ($resource['title'] ?? '') : '' }}" placeholder="Title"
                                        class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">
                                </div>
                                <div>
                                    <select name="resources[{{ $i }}][type]" class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                        @foreach(['link','video','article','course','book','tool','documentation','other'] as $type)
                                        <option value="{{ $type }}" {{ (is_array($resource) ? ($resource['type'] ?? '') : '') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <select name="resources[{{ $i }}][is_free]" class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                        <option value="1" {{ (is_array($resource) ? ($resource['is_free'] ?? true) : true) ? 'selected' : '' }}>Free</option>
                                        <option value="0" {{ (is_array($resource) && !($resource['is_free'] ?? true)) ? 'selected' : '' }}>Paid</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-3 mt-3">
                                <input type="url" name="resources[{{ $i }}][url]" value="{{ is_array($resource) ? ($resource['url'] ?? '') : '' }}" placeholder="https://..."
                                    class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">
                                <input type="text" name="resources[{{ $i }}][provider]" value="{{ is_array($resource) ? ($resource['provider'] ?? '') : '' }}" placeholder="Provider (e.g., YouTube)"
                                    class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Keywords Section --}}
                <div class="space-y-4 pt-5 border-t border-slate-100 dark:border-slate-700/50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-white flex items-center gap-2">
                            <div class="w-6 h-6 rounded bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                                <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400">4</span>
                            </div>
                            Key Concepts
                        </h3>
                        <button type="button" onclick="addKeyword()" class="text-xs text-indigo-500 hover:text-indigo-600 font-medium flex items-center gap-1 transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Keyword
                        </button>
                    </div>
                    <div id="keywords-container" class="space-y-3">
                        @foreach(old('keywords', $task->keywords->toArray()) as $i => $keyword)
                        <div class="keyword-row p-4 bg-slate-50/50 dark:bg-slate-700/20 border border-slate-100 dark:border-slate-700/50 rounded-xl relative group" data-index="{{ $i }}">
                            <button type="button" onclick="this.closest('.keyword-row').remove()" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs">✕</button>
                            <div class="grid sm:grid-cols-3 gap-3">
                                <input type="text" name="keywords[{{ $i }}][keyword]" value="{{ is_array($keyword) ? ($keyword['keyword'] ?? '') : '' }}" placeholder="Keyword"
                                    class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">
                                <input type="text" name="keywords[{{ $i }}][definition]" value="{{ is_array($keyword) ? ($keyword['definition'] ?? '') : '' }}" placeholder="Definition"
                                    class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">
                                <select name="keywords[{{ $i }}][importance]" class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                                    <option value="essential" {{ (is_array($keyword) ? ($keyword['importance'] ?? '') : '') === 'essential' ? 'selected' : '' }}>🔴 Essential</option>
                                    <option value="important" {{ (is_array($keyword) ? ($keyword['importance'] ?? '') : '') === 'important' ? 'selected' : '' }}>🟡 Important</option>
                                    <option value="good_to_know" {{ (is_array($keyword) ? ($keyword['importance'] ?? 'good_to_know') : 'good_to_know') === 'good_to_know' ? 'selected' : '' }}>🔵 Good to Know</option>
                                </select>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-5 border-t border-slate-100 dark:border-slate-700/50">
                    <button type="submit" class="px-6 py-3 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                        Update Task
                    </button>
                    <a href="{{ route('career-path.task.show', [$careerPath, $task]) }}" class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-medium transition">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let resourceIndex = {{ count(old('resources', $task->resources->where('type', '!=', 'video')->values()->toArray())) }};
        let keywordIndex = {{ count(old('keywords', $task->keywords->toArray())) }};
        let videoIndex = {{ count(old('videos', $task->resources->where('type', 'video')->values()->toArray())) }};

        function addVideo() {
            const emptyNote = document.getElementById('videos-empty');
            if (emptyNote) emptyNote.remove();
            const i = videoIndex++;
            const html = `<div class="video-row flex items-center gap-3 p-3 bg-red-50/40 dark:bg-red-500/5 border border-red-100 dark:border-red-500/20 rounded-xl relative group">
                <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <input type="text" name="videos[${i}][title]" placeholder="Label (optional)" class="w-1/3 px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-red-400 transition-all">
                <input type="url" name="videos[${i}][url]" placeholder="https://youtube.com/watch?v=..." class="flex-1 px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-red-400 transition-all">
                <button type="button" onclick="this.closest('.video-row').remove()" class="w-7 h-7 rounded-full bg-red-100 dark:bg-red-500/15 text-red-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs hover:bg-red-200 flex-shrink-0">✕</button>
            </div>`;
            document.getElementById('videos-container').insertAdjacentHTML('beforeend', html);
        }

        function addResource() {
            const i = resourceIndex++;
            const html = `<div class="resource-row p-4 bg-slate-50/50 dark:bg-slate-700/20 border border-slate-100 dark:border-slate-700/50 rounded-xl relative group" data-index="${i}">
                <button type="button" onclick="this.closest('.resource-row').remove()" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs">✕</button>
                <div class="grid sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2"><input type="text" name="resources[${i}][title]" placeholder="Title" class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all"></div>
                    <div><select name="resources[${i}][type]" class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all"><option value="link">Link</option><option value="video">Video</option><option value="article">Article</option><option value="course">Course</option><option value="book">Book</option><option value="tool">Tool</option><option value="documentation">Docs</option><option value="other">Other</option></select></div>
                    <div><select name="resources[${i}][is_free]" class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all"><option value="1">Free</option><option value="0">Paid</option></select></div>
                </div>
                <div class="grid sm:grid-cols-2 gap-3 mt-3">
                    <input type="url" name="resources[${i}][url]" placeholder="https://..." class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">
                    <input type="text" name="resources[${i}][provider]" placeholder="Provider (e.g., YouTube)" class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">
                </div>
            </div>`;
            document.getElementById('resources-container').insertAdjacentHTML('beforeend', html);
        }

        function addKeyword() {
            const i = keywordIndex++;
            const html = `<div class="keyword-row p-4 bg-slate-50/50 dark:bg-slate-700/20 border border-slate-100 dark:border-slate-700/50 rounded-xl relative group" data-index="${i}">
                <button type="button" onclick="this.closest('.keyword-row').remove()" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs">✕</button>
                <div class="grid sm:grid-cols-3 gap-3">
                    <input type="text" name="keywords[${i}][keyword]" placeholder="Keyword" class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">
                    <input type="text" name="keywords[${i}][definition]" placeholder="Definition" class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">
                    <select name="keywords[${i}][importance]" class="w-full px-3 py-2 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all"><option value="essential">🔴 Essential</option><option value="important">🟡 Important</option><option value="good_to_know" selected>🔵 Good to Know</option></select>
                </div>
            </div>`;
            document.getElementById('keywords-container').insertAdjacentHTML('beforeend', html);
        }
    </script>
    @endpush
@endsection
