/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  BOOTSTRAP.JS                                                ║
 * ║  Purpose: Setup Axios and Laravel Echo (Pusher)              ║
 * ║  Learning: HTTP client config, real-time WebSocket setup     ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

import axios from 'axios';
window.axios = axios;

// Set default headers for AJAX requests
// This tells Laravel the request is AJAX (not a browser navigation)
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Laravel Echo + Pusher Setup
 *
 * Echo is Laravel's JavaScript library for subscribing to channels
 * and listening for events broadcast by your Laravel application.
 *
 * Flow:
 * 1. Laravel fires an event (e.g., TaskCreated)
 * 2. Event is sent to Pusher via broadcasting
 * 3. Pusher sends it to all subscribed clients via WebSocket
 * 4. Echo receives it and triggers your callback function
 */
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Make Pusher available globally (required by Echo)
window.Pusher = Pusher;

// Create the Echo instance — this connects to Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,      // Your Pusher app key
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // e.g., "ap2"
    forceTLS: true,                                     // Always use HTTPS
    // Enable logging in development to see what's happening
    // enabledTransports: ['ws', 'wss'],
});

