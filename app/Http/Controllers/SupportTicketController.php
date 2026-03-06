<?php

namespace App\Http\Controllers;

use App\Events\TicketMessageSent;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();

        $tickets = $user->supportTickets()
            ->withCount('messages')
            ->latest()
            ->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
        ]);

        $user = Auth::guard('web')->user();

        $ticket = $user->supportTickets()->create([
            ...$validated,
            'ticket_number' => SupportTicket::generateTicketNumber(),
        ]);

        // Add the description as the first message
        $ticket->messages()->create([
            'user_id' => Auth::id(),
            'message' => $validated['description'],
            'is_admin' => false,
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Support ticket created. Ticket #' . $ticket->ticket_number);
    }

    public function show(SupportTicket $ticket)
    {
        // Ensure user can only view their own tickets
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load('messages.user', 'messages.admin');

        // Mark admin messages as read
        $ticket->messages()
            ->where('is_admin', true)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('tickets.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$ticket->isOpen()) {
            return back()->with('error', 'This ticket is closed. You cannot reply.');
        }

        $request->validate(['message' => ['required', 'string', 'max:5000']]);

        $message = $ticket->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->input('message'),
            'is_admin' => false,
        ]);

        broadcast(new TicketMessageSent($message))->toOthers();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'is_admin' => false,
                    'user_name' => Auth::user()->name,
                    'user_initials' => Auth::user()->initials,
                    'created_at' => $message->created_at->format('M d, H:i'),
                ],
            ]);
        }

        return back()->with('success', 'Reply sent.');
    }
}
