<?php

namespace App\Events;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  EVENT: TaskStatusChanged                                    ║
 * ║  Purpose: Broadcast when a task's status changes             ║
 * ║  Learning: Event data, broadcast channels, real-time updates ║
 * ╚══════════════════════════════════════════════════════════════╝
 *
 * This event fires when a task moves between statuses:
 * pending → in_progress → completed
 *
 * The Vue.js frontend listens for this and updates the UI
 * without needing to refresh the page!
 */

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Task $task;
    public string $oldStatus;

    /**
     * Create a new event instance.
     *
     * @param Task   $task      The task that changed
     * @param string $oldStatus The previous status (for comparison)
     */
    public function __construct(Task $task, string $oldStatus)
    {
        $this->task = $task;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Broadcast on the task owner's private channel.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('tasks.' . $this->task->user_id),
        ];
    }

    /**
     * Custom event name for Echo to listen to.
     */
    public function broadcastAs(): string
    {
        return 'task.status.changed';
    }

    /**
     * Data included in the broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id'         => $this->task->id,
            'title'      => $this->task->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->task->status,
            'priority'   => $this->task->priority,
            'user_name'  => $this->task->user->name,
            'updated_at' => $this->task->updated_at->diffForHumans(),
        ];
    }
}
