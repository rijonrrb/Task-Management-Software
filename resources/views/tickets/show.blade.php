@extends('layouts.app')
@section('title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6">
    <div class="mb-6">
        <a href="{{ route('tickets.index') }}" class="text-sm text-indigo-500 hover:text-indigo-600 font-medium">← Back to Tickets</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Chat Panel --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex flex-col" style="height: 550px;">
                {{-- Header --}}
                <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-800 dark:text-white">{{ $ticket->subject }}</h2>
                            <p class="text-xs text-slate-400 font-mono">#{{ $ticket->ticket_number }}</p>
                        </div>
                        <span class="inline-flex px-2.5 py-1 text-[10px] font-semibold rounded-full
                            {{ $ticket->status === 'open' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $ticket->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $ticket->status === 'closed' ? 'bg-slate-100 text-slate-500' : '' }}
                        ">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                    </div>
                </div>

                {{-- Messages --}}
                <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50 dark:bg-slate-900/50">
                    {{-- Ticket description --}}
                    <div class="flex justify-end">
                        <div class="max-w-[75%]">
                            <div class="bg-indigo-500 text-white rounded-2xl rounded-tr-none p-3.5 shadow-sm">
                                <p class="text-sm whitespace-pre-wrap">{{ $ticket->description }}</p>
                            </div>
                            <span class="text-[10px] text-slate-400 mt-1 text-right mr-2 block">You — {{ $ticket->created_at->format('M d, H:i') }}</span>
                        </div>
                    </div>

                    @foreach($ticket->messages as $message)
                    <div class="flex {{ $message->is_admin ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-[75%]">
                            <div class="{{ $message->is_admin ? 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl rounded-tl-none' : 'bg-indigo-500 text-white rounded-2xl rounded-tr-none' }} p-3.5 shadow-sm">
                                <p class="text-sm whitespace-pre-wrap">{{ $message->message }}</p>
                            </div>
                            <span class="text-[10px] text-slate-400 mt-1 {{ $message->is_admin ? 'ml-2' : 'text-right mr-2' }} block">
                                {{ $message->is_admin ? (($message->admin?->name ?? 'Support') . ' (Support)') : 'You' }} — {{ $message->created_at->format('M d, H:i') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Reply --}}
                @if($ticket->isOpen())
                <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                    <form id="reply-form" action="{{ route('tickets.reply', $ticket) }}" method="POST" class="flex gap-3">
                        @csrf
                        <textarea name="message" id="reply-message" rows="1" placeholder="Type your message..." class="flex-1 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 resize-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required></textarea>
                        <button type="submit" class="self-end px-4 py-2.5 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.769 59.769 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                        </button>
                    </form>
                </div>
                @else
                <div class="p-4 text-center text-sm text-slate-400 bg-slate-50 dark:bg-slate-900/30 border-t border-slate-200 dark:border-slate-700">
                    This ticket is closed.
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
                <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Ticket Details</h4>
                <dl class="space-y-2.5">
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500">Status</dt>
                        <dd class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500">Priority</dt>
                        <dd class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ ucfirst($ticket->priority) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500">Created</dt>
                        <dd class="text-sm text-slate-700 dark:text-slate-300">{{ $ticket->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500">Messages</dt>
                        <dd class="text-sm text-slate-700 dark:text-slate-300">{{ $ticket->messages->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    const replyForm = document.getElementById('reply-form');

    if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;

    const textarea = document.getElementById('reply-message');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }

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
                    appendMessage(data.message, false);
                    messageEl.value = '';
                    messageEl.style.height = 'auto';
                }
            })
            .catch(() => alert('Failed to send message.'))
            .finally(() => btn.disabled = false);
        });
    }

    function appendMessage(msg, isAdmin) {
        const wrapper = document.createElement('div');
        wrapper.className = 'flex ' + (isAdmin ? 'justify-start' : 'justify-end');
        wrapper.innerHTML = `
            <div class="max-w-[75%]">
                <div class="${isAdmin ? 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl rounded-tl-none' : 'bg-indigo-500 text-white rounded-2xl rounded-tr-none'} p-3.5 shadow-sm">
                    <p class="text-sm whitespace-pre-wrap">${escapeHtml(msg.message || msg)}</p>
                </div>
                <span class="text-[10px] text-slate-400 mt-1 ${isAdmin ? 'ml-2' : 'text-right mr-2'} block">
                    ${isAdmin ? (msg.user_name + ' (Support)') : 'You'} — Just now
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

    if (typeof Echo !== 'undefined') {
        Echo.private('ticket.{{ $ticket->id }}')
            .listen('.message.sent', function(e) {
                if (e.user_id !== {{ auth()->id() }}) {
                    appendMessage(e, e.is_admin);
                }
            });
    }
});
</script>
@endpush
@endsection
