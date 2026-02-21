/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  APP.JS — Main JavaScript Entry Point                        ║
 * ║  Purpose: Initialize Vue.js app and mount components         ║
 * ║  Learning: Vue 3 setup, component registration, mounting     ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

// Import bootstrap (Axios + Echo/Pusher setup)
import './bootstrap';

// Import Vue 3
import { createApp } from 'vue';

// Import our Vue components
import TaskBoard from './components/TaskBoard.vue';
import NotificationToast from './components/NotificationToast.vue';
import RedisDemo from './components/RedisDemo.vue';
import StatsCards from './components/StatsCards.vue';

/**
 * Create and mount the Vue application.
 *
 * We register components globally so they can be used
 * in any Blade template with their tag name:
 *
 *   <task-board :tasks="..." />
 *   <notification-toast :user-id="..." />
 *   <redis-demo />
 *   <stats-cards :stats="..." />
 */
const app = createApp({});

// Register components globally
app.component('task-board', TaskBoard);
app.component('notification-toast', NotificationToast);
app.component('redis-demo', RedisDemo);
app.component('stats-cards', StatsCards);

// Mount Vue to the #app element in our Blade layout
// Any Vue component tags inside #app will now work!
app.mount('#app');
