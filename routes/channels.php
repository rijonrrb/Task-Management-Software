<?php

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  BROADCAST CHANNELS                                          ║
 * ║  Purpose: Define authorization for private broadcast channels║
 * ║  Learning: Channel authorization, private channels, security ║
 * ╚══════════════════════════════════════════════════════════════╝
 *
 * Private channels require authorization.
 * The callback returns TRUE if the user is allowed to listen.
 */

use Illuminate\Support\Facades\Broadcast;

/**
 * Private channel: tasks.{userId}
 *
 * Only the user who owns the tasks can listen to this channel.
 * This prevents User A from seeing User B's real-time updates.
 *
 * How it works:
 * 1. Vue.js tries to subscribe to "private-tasks.5"
 * 2. Echo sends a POST to /broadcasting/auth
 * 3. Laravel checks this callback: is user_id == 5?
 * 4. If TRUE → allowed to listen. If FALSE → rejected.
 */
Broadcast::channel('tasks.{userId}', function ($user, $userId) {
    // Only allow if the authenticated user's ID matches the channel ID
    return (int) $user->id === (int) $userId;
});

/**
 * Private channel: ticket.{ticketId}
 *
 * Allows the ticket owner and admin users to listen for live chat messages.
 */
Broadcast::channel('ticket.{ticketId}', function ($user, $ticketId) {
    $ticket = \App\Models\SupportTicket::find($ticketId);
    if (!$ticket) {
        return false;
    }

    if ($user instanceof \App\Models\Admin) {
        return true;
    }

    return $user instanceof \App\Models\User && $user->id === $ticket->user_id;
}, ['guards' => ['web', 'admin']]);
