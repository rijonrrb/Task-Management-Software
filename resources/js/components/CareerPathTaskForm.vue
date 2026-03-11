<template>
    <div class="mt-4">
        <!-- Toggle Button -->
        <button
            v-if="!isOpen"
            @click="isOpen = true"
            class="group inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold border transition-all duration-200 hover:scale-105 hover:shadow-sm active:scale-95 mt-3"
            :class="depthClasses.button"
        >
            <span class="w-3.5 h-3.5 rounded-full flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:rotate-90" :class="depthClasses.iconBg">
                <svg class="w-2 h-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            </span>
            {{ buttonLabel }}
        </button>

        <!-- Inline Form -->
        <div v-if="isOpen" class="border rounded-2xl overflow-hidden" :class="depthClasses.border">
            <div class="px-5 py-3 flex items-center justify-between" :class="depthClasses.header">
                <h4 class="text-xs font-semibold" :class="depthClasses.headerText">{{ buttonLabel }}</h4>
                <button @click="resetForm" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form @submit.prevent="submitForm" class="p-5 space-y-4">
                <!-- Title -->
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Title <span class="text-red-500">*</span></label>
                    <input v-model="form.title" type="text" required
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all"
                        :placeholder="titlePlaceholder" ref="titleInput">
                    <p v-if="errors.title" class="mt-1 text-xs text-red-500">{{ errors.title[0] }}</p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Description</label>
                    <textarea v-model="form.description" rows="2"
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all"
                        placeholder="Brief description..."></textarea>
                </div>

                <!-- Row: Priority + Estimated Hours -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Priority</label>
                        <select v-model="form.priority"
                            class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-xs text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Est. Hours</label>
                        <input v-model="form.estimated_hours" type="number" step="0.5" min="0"
                            class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-xs text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all"
                            placeholder="e.g., 2">
                    </div>
                </div>

                <!-- Expandable: Video & Content -->
                <div>
                    <button type="button" @click="showAdvanced = !showAdvanced"
                        class="text-[11px] text-indigo-500 hover:text-indigo-600 font-medium flex items-center gap-1 transition">
                        <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-90': showAdvanced }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        {{ showAdvanced ? 'Hide' : 'Show' }} Video, Content & Dates
                    </button>
                </div>

                <template v-if="showAdvanced">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Content</label>
                        <textarea v-model="form.content" rows="3"
                            class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all"
                            placeholder="Detailed learning content..."></textarea>
                    </div>
                    <!-- Multiple Videos -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-medium text-slate-600 dark:text-slate-300 flex items-center gap-1">
                                <svg class="w-3 h-3 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Videos
                            </label>
                            <button type="button" @click="addVideo" class="text-[10px] text-red-500 hover:text-red-600 font-medium transition">+ Add Video</button>
                        </div>
                        <div v-for="(video, idx) in form.videos" :key="idx" class="flex items-center gap-2 mb-2">
                            <div class="flex-1 grid grid-cols-2 gap-2">
                                <input v-model="video.title" type="text" placeholder="Label (optional)"
                                    class="px-2.5 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-[11px] text-slate-800 dark:text-slate-200 focus:border-red-400 transition-all">
                                <input v-model="video.url" type="url" placeholder="https://youtube.com/..."
                                    class="px-2.5 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-[11px] text-slate-800 dark:text-slate-200 focus:border-red-400 transition-all">
                            </div>
                            <button type="button" @click="form.videos.splice(idx, 1)" class="w-5 h-5 text-red-400 hover:text-red-500 transition flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <button v-if="form.videos.length === 0" type="button" @click="addVideo"
                            class="text-[11px] text-slate-400 hover:text-red-500 font-medium transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Add video links
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Start Date</label>
                            <input v-model="form.start_date" type="date"
                                class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-xs text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Due Date</label>
                            <input v-model="form.due_date" type="date"
                                class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-xs text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                        </div>
                    </div>
                </template>

                <!-- Dynamic Resources -->
                <div v-if="form.resources.length > 0 || showResources">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-medium text-slate-600 dark:text-slate-300">Reference Links</label>
                        <button type="button" @click="addResource" class="text-[10px] text-indigo-500 hover:text-indigo-600 font-medium">+ Add</button>
                    </div>
                    <div v-for="(resource, idx) in form.resources" :key="idx" class="flex items-start gap-2 mb-2">
                        <div class="flex-1 grid grid-cols-2 gap-2">
                            <input v-model="resource.title" type="text" placeholder="Title"
                                class="px-2.5 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-[11px] text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">
                            <input v-model="resource.url" type="url" placeholder="https://..."
                                class="px-2.5 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-[11px] text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">
                        </div>
                        <button type="button" @click="form.resources.splice(idx, 1)" class="mt-1 w-5 h-5 text-red-400 hover:text-red-500 transition flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
                <button v-else type="button" @click="showResources = true; addResource()"
                    class="text-[11px] text-slate-400 hover:text-indigo-500 font-medium transition flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101" /></svg>
                    Add reference links
                </button>

                <!-- Dynamic Keywords -->
                <div v-if="form.keywords.length > 0 || showKeywords">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-medium text-slate-600 dark:text-slate-300">Keywords</label>
                        <button type="button" @click="addKeyword" class="text-[10px] text-indigo-500 hover:text-indigo-600 font-medium">+ Add</button>
                    </div>
                    <div v-for="(kw, idx) in form.keywords" :key="idx" class="flex items-center gap-2 mb-2">
                        <input v-model="kw.keyword" type="text" placeholder="Keyword"
                            class="flex-1 px-2.5 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-[11px] text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">
                        <select v-model="kw.importance"
                            class="px-2 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-[11px] text-slate-700 dark:text-slate-300 focus:border-indigo-500 transition-all">
                            <option value="essential">Essential</option>
                            <option value="important">Important</option>
                            <option value="good_to_know">Nice to Know</option>
                        </select>
                        <button type="button" @click="form.keywords.splice(idx, 1)" class="w-5 h-5 text-red-400 hover:text-red-500 transition flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
                <button v-else type="button" @click="showKeywords = true; addKeyword()"
                    class="text-[11px] text-slate-400 hover:text-indigo-500 font-medium transition flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" /></svg>
                    Add keywords
                </button>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                    <button type="submit" :disabled="isSubmitting"
                        class="px-5 py-2.5 bg-gradient-to-r text-white text-xs font-semibold rounded-xl transition-all duration-200 disabled:opacity-50"
                        :class="depthClasses.submitBtn">
                        <span v-if="isSubmitting" class="flex items-center gap-2">
                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Saving...
                        </span>
                        <span v-else>Create {{ depthLabel }}</span>
                    </button>
                    <button type="button" @click="resetForm" class="text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition">Cancel</button>
                </div>

                <!-- Error Message -->
                <div v-if="errorMessage" class="p-3 bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 rounded-xl">
                    <p class="text-xs text-red-600 dark:text-red-400">{{ errorMessage }}</p>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CareerPathTaskForm',
    props: {
        careerPathId: { type: Number, required: true },
        parentId: { type: Number, default: null },
        depth: { type: Number, default: 0 },
        formAction: { type: String, required: true },
        csrfToken: { type: String, required: true }
    },
    data() {
        return {
            isOpen: false,
            isSubmitting: false,
            showAdvanced: false,
            showResources: false,
            showKeywords: false,
            errorMessage: '',
            errors: {},
            form: this.getEmptyForm()
        };
    },
    computed: {
        depthLabel() {
            return ['Task', 'Subtask', 'Sub-subtask'][this.depth] || 'Task';
        },
        buttonLabel() {
            return `Add ${this.depthLabel}`;
        },
        titlePlaceholder() {
            const placeholders = [
                'e.g., Frontend Fundamentals',
                'e.g., Learn HTML5 Semantic Tags',
                'e.g., Build a Contact Form'
            ];
            return placeholders[this.depth] || 'Task title...';
        },
        depthClasses() {
            const configs = [
                {
                    button: 'border-indigo-300 dark:border-indigo-500/40 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:border-indigo-400',
                    iconBg: 'bg-indigo-100 dark:bg-indigo-500/25 text-indigo-600 dark:text-indigo-300 group-hover:bg-indigo-500 group-hover:text-white',
                    border: 'border-indigo-100 dark:border-indigo-500/20',
                    header: 'bg-indigo-50/50 dark:bg-indigo-500/5',
                    headerText: 'text-indigo-600 dark:text-indigo-400',
                    submitBtn: 'from-indigo-500 to-indigo-600 hover:shadow-lg hover:shadow-indigo-500/30'
                },
                {
                    button: 'border-purple-300 dark:border-purple-500/40 text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-500/10 hover:border-purple-400',
                    iconBg: 'bg-purple-100 dark:bg-purple-500/25 text-purple-600 dark:text-purple-300 group-hover:bg-purple-500 group-hover:text-white',
                    border: 'border-purple-100 dark:border-purple-500/20',
                    header: 'bg-purple-50/50 dark:bg-purple-500/5',
                    headerText: 'text-purple-600 dark:text-purple-400',
                    submitBtn: 'from-purple-500 to-purple-600 hover:shadow-lg hover:shadow-purple-500/30'
                },
                {
                    button: 'border-cyan-300 dark:border-cyan-500/40 text-cyan-600 dark:text-cyan-400 hover:bg-cyan-50 dark:hover:bg-cyan-500/10 hover:border-cyan-400',
                    iconBg: 'bg-cyan-100 dark:bg-cyan-500/25 text-cyan-600 dark:text-cyan-300 group-hover:bg-cyan-500 group-hover:text-white',
                    border: 'border-cyan-100 dark:border-cyan-500/20',
                    header: 'bg-cyan-50/50 dark:bg-cyan-500/5',
                    headerText: 'text-cyan-600 dark:text-cyan-400',
                    submitBtn: 'from-cyan-500 to-cyan-600 hover:shadow-lg hover:shadow-cyan-500/30'
                }
            ];
            return configs[this.depth] || configs[0];
        }
    },
    methods: {
        getEmptyForm() {
            return {
                title: '',
                description: '',
                content: '',
                priority: 'medium',
                estimated_hours: '',
                videos: [],
                start_date: '',
                due_date: '',
                resources: [],
                keywords: []
            };
        },
        addResource() {
            this.form.resources.push({ title: '', url: '', type: 'link', is_free: true });
        },
        addVideo() {
            this.form.videos.push({ title: '', url: '' });
        },
        addKeyword() {
            this.form.keywords.push({ keyword: '', definition: '', importance: 'good_to_know' });
        },
        resetForm() {
            this.isOpen = false;
            this.showAdvanced = false;
            this.showResources = false;
            this.showKeywords = false;
            this.errorMessage = '';
            this.errors = {};
            this.form = this.getEmptyForm();
        },
        async submitForm() {
            this.isSubmitting = true;
            this.errorMessage = '';
            this.errors = {};

            const payload = {
                ...this.form,
                parent_id: this.parentId
            };

            // Transform videos: set video_url to first, store all as video resources
            const videosArr = payload.videos || [];
            payload.video_url = videosArr.length > 0 ? videosArr[0].url : '';
            const videoResources = videosArr
                .filter(v => v.url)
                .map(v => ({ title: v.title || 'Video', url: v.url, type: 'video', is_free: true }));
            delete payload.videos;

            // Filter out empty resources and keywords
            payload.resources = [...payload.resources.filter(r => r.title || r.url), ...videoResources];
            payload.keywords = payload.keywords.filter(k => k.keyword);

            try {
                const response = await fetch(this.formAction, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (response.ok) {
                    // Reload page to show the new task in the tree
                    window.location.reload();
                } else if (response.status === 422) {
                    const data = await response.json();
                    this.errors = data.errors || {};
                    this.errorMessage = data.message || 'Validation failed. Please check the form.';
                } else {
                    this.errorMessage = 'Something went wrong. Please try again.';
                }
            } catch {
                this.errorMessage = 'Network error. Please check your connection.';
            } finally {
                this.isSubmitting = false;
            }
        }
    },
    watch: {
        isOpen(val) {
            if (val) {
                this.$nextTick(() => {
                    this.$refs.titleInput?.focus();
                });
            }
        }
    }
};
</script>
