<?php

namespace App\Events;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  EVENT: TaskCreated                                          ║
 * ║  Purpose: Broadcast when a new task is created               ║
 * ║  Learning: Laravel Events, Broadcasting, Pusher channels     ║
 * ╚══════════════════════════════════════════════════════════════╝
 *
 * HOW BROADCASTING WORKS:
 * 1. Controller calls: broadcast(new TaskCreated($task))
 * 2. Laravel serializes this event and sends it to Pusher
 * 3. Pusher broadcasts it to all connected clients
 * 4. Vue.js (via Laravel Echo) receives it and updates the UI
 *
 * Channel types:
 * - Public channel:  Anyone can listen (e.g., "tasks")
 * - Private channel: Only authenticated users (e.g., "private-tasks.{userId}")
 * - Presence channel: Shows who's online (e.g., "presence-room.{id}")
 */

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The task that was created.
     * Public properties are automatically included in the broadcast payload.
     */
    public Task $task;

    /**
     * Create a new event instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * We use a Private channel so only the task owner receives the notification.
     * Format: "private-tasks.{userId}"
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('tasks.' . $this->task->user_id),
        ];
    }

    /**
     * The event's broadcast name.
     * This is what Vue.js/Echo listens for: .listen('task.created', ...)
     */
    public function broadcastAs(): string
    {
        return 'task.created';
    }

    /**
     * Data to broadcast with the event.
     * This is what the JavaScript client receives.
     */
    public function broadcastWith(): array
    {
        return [
            'id'        => $this->task->id,
            'title'     => $this->task->title,
            'priority'  => $this->task->priority,
            'status'    => $this->task->status,
            'category'  => $this->task->category?->name,
            'user_name' => $this->task->user->name,
            'created_at' => $this->task->created_at->diffForHumans(),
        ];
    }
}
