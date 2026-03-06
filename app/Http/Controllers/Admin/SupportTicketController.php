<?php

namespace App\Http\Controllers\Admin;

use App\Events\TicketMessageSent;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $statusCounts = [
            'all'         => SupportTicket::count(),
            'open'        => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved'    => SupportTicket::where('status', 'resolved')->count(),
            'closed'      => SupportTicket::where('status', 'closed')->count(),
        ];

        if ($request->ajax()) {
            $query = SupportTicket::with('user', 'assignedAdmin');

            // Optional status filter (passed from view via DataTables ajax data)
            if ($status = $request->input('status')) {
                $query->where('status', $status);
            }

            return DataTables::eloquent($query)
                ->addColumn('ticket_html', function (SupportTicket $ticket) {
                    $subject       = e(\Illuminate\Support\Str::limit($ticket->subject, 45));
                    $ticketNumber  = e($ticket->ticket_number);
                    $url           = route('admin.tickets.show', $ticket);
                    return '<a href="' . $url . '" class="font-medium text-gray-700 hover:text-indigo-600 transition">' . $subject . '</a>'
                        . '<p class="text-xs text-gray-400 font-mono">#' . $ticketNumber . '</p>';
                })
                ->addColumn('user_html', function (SupportTicket $ticket) {
                    $initials = e($ticket->user->initials ?? '?');
                    $name     = e($ticket->user->name ?? 'Unknown');
                    return '<div class="flex items-center gap-2">'
                        . '<div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">' . $initials . '</div>'
                        . '<span class="text-sm text-gray-600">' . $name . '</span></div>';
                })
                ->addColumn('priority_html', function (SupportTicket $ticket) {
                    $class = match($ticket->priority) {
                        'urgent' => 'badge-danger',
                        'high'   => 'badge-warning',
                        'medium' => 'badge-info',
                        default  => 'badge-secondary',
                    };
                    return '<span class="badge ' . $class . '">' . ucfirst($ticket->priority) . '</span>';
                })
                ->addColumn('status_html', function (SupportTicket $ticket) {
                    $class = match($ticket->status) {
                        'open'        => 'badge-warning',
                        'in_progress' => 'badge-info',
                        'resolved'    => 'badge-success',
                        default       => 'badge-secondary',
                    };
                    return '<span class="badge ' . $class . '">' . ucfirst(str_replace('_', ' ', $ticket->status)) . '</span>';
                })
                ->addColumn('assigned_html', function (SupportTicket $ticket) {
                    return $ticket->assignedAdmin ? e($ticket->assignedAdmin->name) : '<span class="text-gray-400">—</span>';
                })
                ->editColumn('created_at', function (SupportTicket $ticket) {
                    return $ticket->created_at->diffForHumans();
                })
                ->addColumn('actions', function (SupportTicket $ticket) {
                    $url = route('admin.tickets.show', $ticket);
                    return '<a href="' . $url . '" class="btn-admin btn-admin-primary btn-admin-sm">View</a>';
                })
                ->rawColumns(['ticket_html', 'user_html', 'priority_html', 'status_html', 'assigned_html', 'actions'])
                ->toJson();
        }

        return view('admin.tickets.index', compact('statusCounts'));
    }


    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user', 'assignedAdmin', 'messages.user', 'messages.admin']);

        // Mark unread messages as read
        $ticket->messages()
            ->where('is_admin', false)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $admins = Admin::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.tickets.show', compact('ticket', 'admins'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $message = $ticket->messages()->create([
            'admin_id' => auth('admin')->id(),
            'message' => $request->input('message'),
            'is_admin' => true,
        ]);

        // Update ticket status to in_progress if it was open
        if ($ticket->status === 'open') {
            $ticket->update([
                'status' => 'in_progress',
                'assigned_admin_id' => auth('admin')->id(),
            ]);
        }

        // Broadcast via Pusher for live chat
        broadcast(new TicketMessageSent($message))->toOthers();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'is_admin' => true,
                    'user_name' => auth('admin')->user()->name,
                    'user_initials' => auth('admin')->user()->initials,
                    'created_at' => $message->created_at->format('M d, H:i'),
                ],
            ]);
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
        ]);

        $ticket->update([
            'status' => $request->input('status'),
            'closed_at' => in_array($request->input('status'), ['closed', 'resolved']) ? now() : null,
        ]);

        Cache::forget('admin_dashboard_stats');

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Ticket status updated.');
    }

    public function assign(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'assigned_admin_id' => ['nullable', 'exists:admins,id'],
        ]);

        $ticket->update(['assigned_admin_id' => $request->input('assigned_admin_id')]);

        return back()->with('success', 'Ticket assigned successfully.');
    }
}
