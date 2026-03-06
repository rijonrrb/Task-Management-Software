@extends('admin.layouts.app')

@section('title', 'Ticket #' . $ticket->ticket_number)
@section('page-title', 'Ticket #' . $ticket->ticket_number)
@section('page-subtitle', $ticket->subject)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Chat Panel (left) --}}
    <div class="lg:col-span-2">
        <div class="admin-card flex flex-col" style="height: 600px;">
            {{-- Chat Header --}}
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">{{ $ticket->subject }}</h3>
                    <p class="text-xs text-gray-400">Submitted by {{ $ticket->user->name }} &middot; {{ $ticket->created_at->format('M d, Y H:i') }}</p>
                </div>
                <span class="badge badge-{{ $ticket->status === 'open' ? 'warning' : ($ticket->status === 'in_progress' ? 'info' : ($ticket->status === 'resolved' ? 'success' : 'secondary')) }}">
                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                </span>
            </div>

            {{-- Messages Area --}}
            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50/50">
                {{-- Initial Ticket Description --}}
                <div class="flex justify-start">
                    <div class="max-w-[75%]">
                        <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-none p-3.5 shadow-sm">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
                        </div>
                        <span class="text-[10px] text-gray-400 mt-1 ml-2 block">{{ $ticket->user->name }} — {{ $ticket->created_at->format('M d, H:i') }}</span>
                    </div>
                </div>

                {{-- Messages --}}
                @foreach($ticket->messages as $message)
                <div class="flex {{ $message->is_admin ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%]">
                        <div class="{{ $message->is_admin ? 'bg-indigo-600 text-white rounded-2xl rounded-tr-none' : 'bg-white border border-gray-200 rounded-2xl rounded-tl-none' }} p-3.5 shadow-sm">
                            <p class="text-sm whitespace-pre-wrap">{{ $message->message }}</p>
                        </div>
                        <span class="text-[10px] text-gray-400 mt-1 {{ $message->is_admin ? 'text-right mr-2' : 'ml-2' }} block">
                            {{ $message->is_admin ? ($message->admin?->name ?? 'Support') : ($message->user?->name ?? 'User') }} — {{ $message->created_at->format('M d, H:i') }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Reply Form --}}
            @if($ticket->status !== 'closed')
            <div class="p-4 border-t border-gray-100 bg-white">
                <form id="reply-form" action="{{ route('admin.tickets.reply', $ticket) }}" method="POST" class="flex gap-3">
                    @csrf
                    <textarea name="message" id="reply-message" rows="1" placeholder="Type your reply..." class="admin-input flex-1 resize-none" required></textarea>
                    <button type="submit" class="btn-admin btn-admin-primary self-end">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.769 59.769 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                    </button>
                </form>
            </div>
            @else
            <div class="p-4 text-center text-sm text-gray-400 bg-gray-50 border-t border-gray-100">
                This ticket is closed. Reopen to reply.
            </div>
            @endif
        </div>
    </div>

    {{-- Sidebar (right) --}}
    <div class="space-y-5">
        {{-- Ticket Info Card --}}
        <div class="admin-card p-5">
            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Ticket Info</h4>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Ticket #</dt>
                    <dd class="text-sm font-mono font-medium text-gray-700">{{ $ticket->ticket_number }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Priority</dt>
                    <dd>
                        <span class="badge badge-{{ $ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : ($ticket->priority === 'medium' ? 'info' : 'secondary')) }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Created</dt>
                    <dd class="text-sm text-gray-700">{{ $ticket->created_at->format('M d, Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Messages</dt>
                    <dd class="text-sm text-gray-700">{{ $ticket->messages->count() }}</dd>
                </div>
                @if($ticket->closed_at)
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Closed</dt>
                    <dd class="text-sm text-gray-700">{{ $ticket->closed_at->format('M d, Y') }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- User Card --}}
        <div class="admin-card p-5">
            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Submitted By</h4>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    {{ $ticket->user->initials }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">{{ $ticket->user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $ticket->user->email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.users.show', $ticket->user) }}" class="text-xs text-indigo-500 hover:text-indigo-600 font-medium">View Profile →</a>
        </div>

        {{-- Status Update --}}
        <div class="admin-card p-5">
            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Update Status</h4>
            <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST" class="space-y-3">
                @csrf
                @method('PUT')
                <select name="status" class="admin-input text-sm">
                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <button type="submit" class="btn-admin btn-admin-primary btn-admin-sm w-full">Update Status</button>
            </form>
        </div>

        {{-- Assign Admin --}}
        <div class="admin-card p-5">
            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Assign Admin</h4>
            <form action="{{ route('admin.tickets.assign', $ticket) }}" method="POST" class="space-y-3">
                @csrf
                @method('PUT')
                <select name="assigned_admin_id" class="admin-input text-sm">
                    <option value="">Unassigned</option>
                    @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" {{ $ticket->assigned_admin_id == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-admin btn-admin-sm btn-admin-outline w-full">Assign</button>
            </form>
        </div>

        {{-- Back --}}
        <a href="{{ route('admin.tickets.index') }}" class="btn-admin btn-admin-outline btn-admin-sm w-full text-center block">← Back to Tickets</a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    const replyForm = document.getElementById('reply-form');

    // Scroll to bottom on load
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Auto-resize textarea
    const textarea = document.getElementById('reply-message');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }

    // Submit reply via AJAX
    if (replyForm) {
        replyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const messageEl = document.getElementById('reply-message');
            const message = messageEl.value.trim();
            if (!message) return;

            const btn = replyForm.querySelector('button[type="submit"]');
            btn.disabled = true;

            fetch(replyForm.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: message })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    appendMessage(data.message, true);
                    messageEl.value = '';
                    messageEl.style.height = 'auto';
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Failed to send message.', 'error');
            })
            .finally(() => {
                btn.disabled = false;
            });
        });
    }

    function appendMessage(msg, isAdmin) {
        const wrapper = document.createElement('div');
        wrapper.className = 'flex ' + (isAdmin ? 'justify-end' : 'justify-start');
        wrapper.innerHTML = `
            <div class="max-w-[75%]">
                <div class="${isAdmin ? 'bg-indigo-600 text-white rounded-2xl rounded-tr-none' : 'bg-white border border-gray-200 rounded-2xl rounded-tl-none'} p-3.5 shadow-sm">
                    <p class="text-sm whitespace-pre-wrap">${escapeHtml(msg.message || msg)}</p>
                </div>
                <span class="text-[10px] text-gray-400 mt-1 ${isAdmin ? 'text-right mr-2' : 'ml-2'} block">
                    ${msg.user_name || 'You'} — Just now
                </span>
            </div>
        `;
        chatMessages.appendChild(wrapper);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Real-time listener via Laravel Echo
    if (typeof Echo !== 'undefined') {
        Echo.private('ticket.{{ $ticket->id }}')
            .listen('.message.sent', function(e) {
                if (e.admin_id !== {{ auth('admin')->id() }}) {
                    appendMessage(e, e.is_admin);
                }
            });
    }
});
</script>
@endpush
@endsection
