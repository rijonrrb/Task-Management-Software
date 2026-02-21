<!--
╔══════════════════════════════════════════════════════════════╗
║  VUE COMPONENT: TaskBoard                                    ║
║  Purpose: Interactive task list with status updates via AJAX ║
║  Learning: Vue reactivity, Axios API calls, event handling   ║
╚══════════════════════════════════════════════════════════════╝

Usage in Blade:
  <task-board :initial-tasks='@json($tasks)' />
-->

<template>
  <div class="space-y-4">
    <!-- Filter Tabs -->
    <div class="flex flex-wrap gap-2 mb-6">
      <button
        v-for="filter in filters"
        :key="filter.value"
        @click="activeFilter = filter.value"
        :class="[
          'px-4 py-2 rounded-full text-sm font-medium transition-all duration-200',
          activeFilter === filter.value
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200'
            : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'
        ]"
      >
        {{ filter.label }}
        <span
          v-if="getCount(filter.value) > 0"
          class="ml-1.5 px-2 py-0.5 rounded-full text-xs"
          :class="activeFilter === filter.value ? 'bg-indigo-500' : 'bg-gray-200'"
        >
          {{ getCount(filter.value) }}
        </span>
      </button>
    </div>

    <!-- Task Cards -->
    <div class="grid gap-4">
      <transition-group name="task-list" tag="div" class="space-y-3">
        <div
          v-for="task in filteredTasks"
          :key="task.id"
          class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-all duration-200"
          :class="{ 'opacity-60 line-through': task.status === 'completed' }"
        >
          <div class="flex items-start justify-between gap-4">
            <!-- Left: Task Info -->
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1">
                <!-- Priority Badge -->
                <span
                  class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold"
                  :class="priorityClass(task.priority)"
                >
                  {{ task.priority }}
                </span>
                <!-- Category Badge -->
                <span
                  v-if="task.category"
                  class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600"
                >
                  {{ task.category.name }}
                </span>
              </div>

              <!-- Task Title -->
              <a
                :href="'/tasks/' + task.id"
                class="text-lg font-semibold text-gray-800 hover:text-indigo-600 transition-colors"
              >
                {{ task.title }}
              </a>

              <!-- Due Date -->
              <p v-if="task.due_date" class="text-sm text-gray-500 mt-1">
                📅 Due: {{ formatDate(task.due_date) }}
                <span v-if="isOverdue(task)" class="text-red-500 font-medium ml-1">
                  (Overdue!)
                </span>
              </p>
            </div>

            <!-- Right: Status Dropdown -->
            <div class="flex-shrink-0">
              <select
                :value="task.status"
                @change="updateStatus(task, $event.target.value)"
                class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                :disabled="updating === task.id"
              >
                <option value="pending">⏳ Pending</option>
                <option value="in_progress">🔄 In Progress</option>
                <option value="completed">✅ Completed</option>
                <option value="cancelled">❌ Cancelled</option>
              </select>

              <!-- Loading spinner while updating -->
              <div v-if="updating === task.id" class="mt-1 text-center">
                <svg class="animate-spin h-4 w-4 text-indigo-600 inline" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </transition-group>

      <!-- Empty State -->
      <div
        v-if="filteredTasks.length === 0"
        class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300"
      >
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks found</h3>
        <p class="mt-1 text-sm text-gray-500">
          {{ activeFilter === 'all' ? 'Create your first task to get started!' : 'No tasks match this filter.' }}
        </p>
        <a href="/tasks/create" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
          + New Task
        </a>
      </div>
    </div>
  </div>
</template>

<script>
/**
 * TaskBoard Component
 *
 * Props:
 *   initialTasks - Array of task objects from Laravel (passed via Blade)
 *
 * Features:
 *   - Filter tasks by status (tabs)
 *   - Update status via AJAX (no page reload)
 *   - Priority color coding
 *   - Overdue detection
 */
export default {
  name: 'TaskBoard',

  props: {
    // Initial tasks passed from Blade template as JSON
    initialTasks: {
      type: Array,
      default: () => [],
    },
  },

  data() {
    return {
      // Reactive copy of tasks (so we can update without reload)
      tasks: [...this.initialTasks],

      // Currently active filter tab
      activeFilter: 'all',

      // ID of task currently being updated (shows spinner)
      updating: null,

      // Available filter options
      filters: [
        { label: 'All', value: 'all' },
        { label: 'Pending', value: 'pending' },
        { label: 'In Progress', value: 'in_progress' },
        { label: 'Completed', value: 'completed' },
      ],
    };
  },

  computed: {
    /**
     * Computed property: filter tasks based on active tab.
     * Recomputes automatically when tasks or activeFilter changes.
     */
    filteredTasks() {
      if (this.activeFilter === 'all') {
        return this.tasks;
      }
      return this.tasks.filter(task => task.status === this.activeFilter);
    },
  },

  methods: {
    /**
     * Update a task's status via AJAX.
     *
     * 🟢 AXIOS LEARNING:
     * We send a PATCH request to our Laravel API endpoint.
     * Laravel processes it, updates the DB, and returns the updated task.
     * We then update our local Vue data — no page reload needed!
     */
    async updateStatus(task, newStatus) {
      this.updating = task.id;

      try {
        const response = await axios.patch(`/api/tasks/${task.id}/status`, {
          status: newStatus,
        });

        // Update the local task data with the response
        const index = this.tasks.findIndex(t => t.id === task.id);
        if (index !== -1) {
          this.tasks[index] = response.data.task;
        }
      } catch (error) {
        // Revert on error
        console.error('Failed to update task status:', error);
        alert('Failed to update status. Please try again.');
      } finally {
        this.updating = null;
      }
    },

    /**
     * Get count of tasks for a filter tab.
     */
    getCount(filter) {
      if (filter === 'all') return this.tasks.length;
      return this.tasks.filter(t => t.status === filter).length;
    },

    /**
     * Get CSS classes for priority badges.
     */
    priorityClass(priority) {
      const classes = {
        urgent: 'bg-red-100 text-red-700',
        high: 'bg-orange-100 text-orange-700',
        medium: 'bg-blue-100 text-blue-700',
        low: 'bg-gray-100 text-gray-600',
      };
      return classes[priority] || classes.medium;
    },

    /**
     * Format a date string to a human-readable format.
     */
    formatDate(dateStr) {
      if (!dateStr) return '';
      const date = new Date(dateStr);
      return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
      });
    },

    /**
     * Check if a task is overdue.
     */
    isOverdue(task) {
      if (!task.due_date || ['completed', 'cancelled'].includes(task.status)) {
        return false;
      }
      return new Date(task.due_date) < new Date();
    },
  },
};
</script>

<style scoped>
/* Vue transition animations for task list */
.task-list-enter-active,
.task-list-leave-active {
  transition: all 0.3s ease;
}
.task-list-enter-from,
.task-list-leave-to {
  opacity: 0;
  transform: translateX(-20px);
}
</style>
