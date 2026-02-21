<!--
╔══════════════════════════════════════════════════════════════╗
║  VUE COMPONENT: StatsCards                                   ║
║  Purpose: Animated dashboard statistics cards                ║
║  Learning: Vue props, computed properties, animations        ║
╚══════════════════════════════════════════════════════════════╝

Usage in Blade:
  <stats-cards :stats='@json($stats)' />
-->

<template>
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
    <div
      v-for="(card, index) in cards"
      :key="card.label"
      class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 cursor-default"
    >
      <!-- Icon & Value -->
      <div class="flex items-center justify-between mb-3">
        <span class="text-2xl">{{ card.icon }}</span>
        <span
          class="text-2xl font-black"
          :class="card.color"
        >
          {{ animatedValues[index] ?? card.value }}
        </span>
      </div>

      <!-- Label -->
      <p class="text-sm font-medium text-gray-500">{{ card.label }}</p>

      <!-- Progress Bar -->
      <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
        <div
          class="h-full rounded-full transition-all duration-1000 ease-out"
          :class="card.barColor"
          :style="{ width: getPercentage(card.value) + '%' }"
        ></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'StatsCards',

  props: {
    stats: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      animatedValues: [],
    };
  },

  computed: {
    /**
     * Transform stats object into card display data.
     * Each card has an icon, label, value, and colors.
     */
    cards() {
      return [
        {
          icon: '📋',
          label: 'Total Tasks',
          value: this.stats.total_tasks || 0,
          color: 'text-gray-800',
          barColor: 'bg-gray-400',
        },
        {
          icon: '⏳',
          label: 'Pending',
          value: this.stats.pending_tasks || 0,
          color: 'text-blue-600',
          barColor: 'bg-blue-500',
        },
        {
          icon: '🔄',
          label: 'In Progress',
          value: this.stats.in_progress || 0,
          color: 'text-yellow-600',
          barColor: 'bg-yellow-500',
        },
        {
          icon: '✅',
          label: 'Completed',
          value: this.stats.completed_tasks || 0,
          color: 'text-green-600',
          barColor: 'bg-green-500',
        },
        {
          icon: '⚠️',
          label: 'Overdue',
          value: this.stats.overdue_tasks || 0,
          color: 'text-red-600',
          barColor: 'bg-red-500',
        },
        {
          icon: '🔥',
          label: 'Urgent',
          value: this.stats.urgent_tasks || 0,
          color: 'text-orange-600',
          barColor: 'bg-orange-500',
        },
      ];
    },
  },

  mounted() {
    // Animate numbers counting up on mount
    this.animateNumbers();
  },

  methods: {
    /**
     * Animate each stat number from 0 to its value.
     * Creates a smooth counting-up effect when the page loads.
     */
    animateNumbers() {
      this.cards.forEach((card, index) => {
        this.animatedValues[index] = 0;
        const target = card.value;
        const duration = 1000; // 1 second
        const steps = 30;
        const increment = target / steps;
        let current = 0;
        let step = 0;

        const interval = setInterval(() => {
          step++;
          current = Math.min(Math.round(increment * step), target);
          // Use Vue's reactivity system
          this.animatedValues = [...this.animatedValues.slice(0, index), current, ...this.animatedValues.slice(index + 1)];

          if (step >= steps) {
            clearInterval(interval);
          }
        }, duration / steps);
      });
    },

    /**
     * Calculate percentage relative to total tasks.
     * Used for the progress bar width.
     */
    getPercentage(value) {
      const total = this.stats.total_tasks || 1;
      return Math.min(Math.round((value / total) * 100), 100);
    },
  },
};
</script>
