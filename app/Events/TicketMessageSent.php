<?php

namespace App\Events;

use App\Models\TicketMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TicketMessage $ticketMessage;

    public function __construct(TicketMessage $ticketMessage)
    {
        $this->ticketMessage = $ticketMessage;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('ticket.' . $this->ticketMessage->ticket_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        $sender = $this->ticketMessage->is_admin
            ? $this->ticketMessage->admin
            : $this->ticketMessage->user;

        return [
            'id' => $this->ticketMessage->id,
            'ticket_id' => $this->ticketMessage->ticket_id,
            'user_id' => $this->ticketMessage->user_id,
            'admin_id' => $this->ticketMessage->admin_id,
            'message' => $this->ticketMessage->message,
            'is_admin' => $this->ticketMessage->is_admin,
            'user_name' => $sender?->name ?? 'System',
            'user_initials' => $sender?->initials ?? 'SY',
            'created_at' => $this->ticketMessage->created_at->format('M d, H:i'),
        ];
    }
}
