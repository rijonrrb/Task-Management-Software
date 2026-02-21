<!--
╔══════════════════════════════════════════════════════════════╗
║  VUE COMPONENT: NotificationToast                            ║
║  Purpose: Real-time toast notifications via Pusher/Echo      ║
║  Learning: Laravel Echo, private channels, WebSocket events  ║
╚══════════════════════════════════════════════════════════════╝

Usage in Blade:
  <notification-toast :user-id="{{ auth()->id() }}" />

This component listens for real-time events from Pusher
and shows toast notifications when tasks are created or updated.
-->

<template>
  <!-- Notification Container (fixed position, top-right) -->
  <div class="fixed top-4 right-4 z-50 space-y-3 max-w-sm w-full">
    <transition-group name="toast">
      <div
        v-for="notification in notifications"
        :key="notification.id"
        class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 transform transition-all duration-300"
        :class="notificationBorderClass(notification.type)"
      >
        <div class="flex items-start gap-3">
          <!-- Icon -->
          <div
            class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-lg"
            :class="notificationIconClass(notification.type)"
          >
            {{ notificationIcon(notification.type) }}
          </div>

          <!-- Content -->
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900">
              {{ notification.title }}
            </p>
            <p class="text-sm text-gray-500 mt-0.5">
              {{ notification.message }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
              {{ notification.time }}
            </p>
          </div>

          <!-- Close Button -->
          <button
            @click="removeNotification(notification.id)"
            class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition"
          >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </transition-group>
  </div>
</template>

<script>
/**
 * NotificationToast Component
 *
 * 🟢 PUSHER/ECHO LEARNING POINT:
 *
 * This component demonstrates real-time broadcasting:
 *
 * 1. When mounted, it subscribes to a PRIVATE channel using Echo
 * 2. It listens for specific events (task.created, task.status.changed)
 * 3. When an event arrives via WebSocket, it creates a toast notification
 * 4. The toast auto-disappears after 5 seconds
 *
 * The flow:
 *   Laravel Controller → Event → Pusher → WebSocket → Echo → This Component → Toast
 */
export default {
  name: 'NotificationToast',

  props: {
    // The authenticated user's ID (determines which channel to listen to)
    userId: {
      type: Number,
      required: true,
    },
  },

  data() {
    return {
      // Array of active notifications
      notifications: [],
      // Counter for unique IDs
      nextId: 1,
    };
  },

  mounted() {
    /**
     * 🟢 ECHO SETUP: Subscribe to the user's private channel.
     *
     * Echo.private() subscribes to a private channel.
     * "tasks.{userId}" matches the channel defined in channels.php
     *
     * .listen('.event.name', callback) listens for broadcast events.
     * The dot prefix tells Echo to use the exact event name
     * (without the App\Events namespace prefix).
     */
    if (window.Echo) {
      window.Echo.private(`tasks.${this.userId}`)
        // Listen for new task creation
        .listen('.task.created', (event) => {
          this.addNotification({
            type: 'created',
            title: 'New Task Created! 🎉',
            message: `"${event.title}" (${event.priority} priority)`,
          });
        })
        // Listen for task status changes
        .listen('.task.status.changed', (event) => {
          this.addNotification({
            type: 'status',
            title: 'Task Status Updated',
            message: `"${event.title}": ${event.old_status} → ${event.new_status}`,
          });
        });
    }
  },

  methods: {
    /**
     * Add a new notification toast.
     * Auto-removes after 5 seconds.
     */
    addNotification(data) {
      const notification = {
        id: this.nextId++,
        ...data,
        time: 'Just now',
      };

      this.notifications.push(notification);

      // Auto-remove after 5 seconds
      setTimeout(() => {
        this.removeNotification(notification.id);
      }, 5000);
    },

    /**
     * Remove a notification by ID.
     */
    removeNotification(id) {
      this.notifications = this.notifications.filter(n => n.id !== id);
    },

    /**
     * Get the border color class based on notification type.
     */
    notificationBorderClass(type) {
      return {
        created: 'border-l-4 border-l-green-500',
        status: 'border-l-4 border-l-blue-500',
        error: 'border-l-4 border-l-red-500',
      }[type] || 'border-l-4 border-l-gray-500';
    },

    /**
     * Get the icon background class.
     */
    notificationIconClass(type) {
      return {
        created: 'bg-green-100',
        status: 'bg-blue-100',
        error: 'bg-red-100',
      }[type] || 'bg-gray-100';
    },

    /**
     * Get the emoji icon for the notification type.
     */
    notificationIcon(type) {
      return {
        created: '✨',
        status: '🔄',
        error: '⚠️',
      }[type] || '📢';
    },
  },
};
</script>

<style scoped>
/* Toast enter/leave animations */
.toast-enter-active {
  transition: all 0.4s ease-out;
}
.toast-leave-active {
  transition: all 0.3s ease-in;
}
.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}
.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
</style>
