@extends('layouts.app')
@section('title', 'My Tickets')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Support Tickets</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Get help from our support team</p>
        </div>
        <a href="{{ route('tickets.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            New Ticket
        </a>
    </div>

    <div class="space-y-3">
        @forelse($tickets as $ticket)
        <a href="{{ route('tickets.show', $ticket) }}" class="block bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5 hover:border-indigo-300 dark:hover:border-indigo-500 hover:shadow-md transition-all group">
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-mono text-slate-400">#{{ $ticket->ticket_number }}</span>
                        <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold rounded-full
                            {{ $ticket->status === 'open' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $ticket->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $ticket->status === 'closed' ? 'bg-slate-100 text-slate-500' : '' }}
                        ">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                        <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold rounded-full
                            {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $ticket->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $ticket->priority === 'medium' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $ticket->priority === 'low' ? 'bg-slate-100 text-slate-500' : '' }}
                        ">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition truncate">{{ $ticket->subject }}</h3>
                    <p class="text-xs text-slate-400 mt-1">{{ $ticket->created_at->diffForHumans() }} · {{ $ticket->messages_count }} message{{ $ticket->messages_count !== 1 ? 's' : '' }}</p>
                </div>
                <svg class="w-5 h-5 text-slate-300 group-hover:text-indigo-400 transition ml-4 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </div>
        </a>
        @empty
        <div class="text-center py-16">
            <svg class="w-16 h-16 mx-auto mb-4 text-slate-200 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" /></svg>
            <h3 class="text-lg font-semibold text-slate-600 dark:text-slate-300">No tickets yet</h3>
            <p class="text-sm text-slate-400 mt-1">Create a ticket to get support from our team</p>
            <a href="{{ route('tickets.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-indigo-500 text-white text-sm rounded-lg hover:bg-indigo-600 transition">Create Ticket</a>
        </div>
        @endforelse
    </div>

    @if($tickets->hasPages())
    <div class="mt-6">{{ $tickets->links() }}</div>
    @endif
</div>
@endsection
