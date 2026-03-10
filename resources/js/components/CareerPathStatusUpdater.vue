<template>
    <div class="space-y-2">
        <div class="flex flex-wrap gap-2">
            <button
                v-for="status in availableStatuses"
                :key="status.value"
                @click="updateStatus(status.value)"
                :disabled="isUpdating"
                class="flex items-center gap-1.5 px-3 py-2 border rounded-lg text-[11px] font-medium transition-all duration-200 disabled:opacity-50"
                :class="status.value === currentStatusLocal ? status.activeClass : status.inactiveClass"
            >
                <span v-html="status.icon"></span>
                {{ status.label }}
            </button>
        </div>
        <p v-if="message" class="text-[10px] font-medium" :class="messageClass">{{ message }}</p>
    </div>
</template>

<script>
export default {
    name: 'CareerPathStatusUpdater',
    props: {
        taskId: { type: Number, required: true },
        currentStatus: { type: String, required: true },
        csrfToken: { type: String, required: true }
    },
    data() {
        return {
            currentStatusLocal: this.currentStatus,
            isUpdating: false,
            message: '',
            messageClass: ''
        };
    },
    computed: {
        availableStatuses() {
            return [
                {
                    value: 'in_progress',
                    label: 'In Progress',
                    icon: '⚡',
                    activeClass: 'border-amber-300 dark:border-amber-500/40 bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                    inactiveClass: 'border-slate-200 dark:border-slate-600 text-slate-500 dark:text-slate-400 hover:border-amber-300 dark:hover:border-amber-500/40 hover:text-amber-600'
                },
                {
                    value: 'completed',
                    label: 'Completed',
                    icon: '✅',
                    activeClass: 'border-emerald-300 dark:border-emerald-500/40 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                    inactiveClass: 'border-slate-200 dark:border-slate-600 text-slate-500 dark:text-slate-400 hover:border-emerald-300 dark:hover:border-emerald-500/40 hover:text-emerald-600'
                },
                {
                    value: 'skipped',
                    label: 'Skip',
                    icon: '⏭️',
                    activeClass: 'border-slate-300 dark:border-slate-500 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300',
                    inactiveClass: 'border-slate-200 dark:border-slate-600 text-slate-400 dark:text-slate-500 hover:border-slate-400 hover:text-slate-600'
                }
            ];
        }
    },
    methods: {
        async updateStatus(newStatus) {
            if (newStatus === this.currentStatusLocal) return;
            this.isUpdating = true;
            this.message = '';

            try {
                const response = await fetch(`/api/career-path-tasks/${this.taskId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                if (response.ok) {
                    this.currentStatusLocal = newStatus;
                    this.message = 'Status updated!';
                    this.messageClass = 'text-emerald-500';
                    setTimeout(() => { this.message = ''; }, 2000);
                    // Reload after delay to reflect changes
                    setTimeout(() => { window.location.reload(); }, 1000);
                } else {
                    this.message = 'Failed to update status.';
                    this.messageClass = 'text-red-500';
                }
            } catch {
                this.message = 'Network error.';
                this.messageClass = 'text-red-500';
            } finally {
                this.isUpdating = false;
            }
        }
    }
};
</script>
