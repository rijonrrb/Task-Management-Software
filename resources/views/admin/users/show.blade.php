@extends('admin.layouts.app')

@section('title', $user->name . ' - User Details')
@section('page-title', 'User Details')
@section('page-subtitle', $user->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- User Profile Card --}}
    <div class="admin-card">
        <div class="p-6 text-center border-b border-gray-100">
            <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">
                {{ $user->initials }}
            </div>
            <h3 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h3>
            <p class="text-sm text-gray-400">{{ $user->email }}</p>
            <div class="mt-3 flex items-center justify-center gap-2">
                @if($user->is_banned)
                    <span class="badge badge-danger">Banned</span>
                @elseif($user->isLocked())
                    <span class="badge badge-warning">Locked</span>
                @else
                    <span class="badge badge-success">Active</span>
                @endif
            </div>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Joined</span>
                <span class="text-gray-700 font-medium">{{ $user->created_at->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Last Login</span>
                <span class="text-gray-700 font-medium">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Login Count</span>
                <span class="text-gray-700 font-medium">{{ $user->login_count }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Last IP</span>
                <span class="text-gray-700 font-medium font-mono text-xs">{{ $user->last_ip ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Failed Attempts</span>
                <span class="text-gray-700 font-medium">{{ $user->failed_login_attempts }}</span>
            </div>
            @if($user->last_user_agent)
            <div class="text-sm">
                <span class="text-gray-400 block mb-1">User Agent</span>
                <span class="text-gray-600 text-xs break-all">{{ $user->last_user_agent }}</span>
            </div>
            @endif
            @if($user->ban_reason)
            <div class="text-sm">
                <span class="text-gray-400 block mb-1">Ban Reason</span>
                <span class="text-red-600 text-xs">{{ $user->ban_reason }}</span>
            </div>
            @endif
        </div>
        {{-- Actions --}}
        <div class="p-6 border-t border-gray-100 space-y-2">
            <form method="POST" action="{{ route('admin.users.toggle-ban', $user) }}" id="ban-toggle-{{ $user->id }}">
                @csrf
                @if(!$user->is_banned)
                <input type="hidden" name="ban_reason" value="Account suspended by administrator.">
                @endif
                <button type="button" onclick="confirmAction('ban-toggle-{{ $user->id }}', '{{ $user->is_banned ? 'Unban' : 'Ban' }} User', '{{ $user->is_banned ? 'Restore access for' : 'Block' }} {{ $user->name }}?')"
                    class="btn-admin w-full {{ $user->is_banned ? 'btn-admin-success' : 'btn-admin-danger' }}">
                    {{ $user->is_banned ? 'Unban User' : 'Ban User' }}
                </button>
            </form>
            @if($user->isLocked())
            <form method="POST" action="{{ route('admin.security.unlock-user', $user) }}" id="unlock-{{ $user->id }}">
                @csrf
                <button type="button" onclick="confirmAction('unlock-{{ $user->id }}', 'Unlock User', 'Unlock {{ $user->name }}?')"
                    class="btn-admin btn-admin-outline w-full">
                    Unlock Account
                </button>
            </form>
            @endif
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" id="delete-user-{{ $user->id }}">
                @csrf @method('DELETE')
                <button type="button" onclick="confirmDelete('delete-user-{{ $user->id }}', 'Delete {{ $user->name }}? This will permanently remove all their data.')"
                    class="btn-admin btn-admin-outline w-full text-red-500 hover:bg-red-50">
                    Delete User
                </button>
            </form>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Task Stats --}}
        <div class="admin-card p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Task Statistics</h3>
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $taskStats['total'] }}</div>
                    <div class="text-xs text-gray-400">Total</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-amber-500">{{ $taskStats['pending'] }}</div>
                    <div class="text-xs text-gray-400">Pending</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-500">{{ $taskStats['in_progress'] }}</div>
                    <div class="text-xs text-gray-400">In Progress</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-500">{{ $taskStats['completed'] }}</div>
                    <div class="text-xs text-gray-400">Completed</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-400">{{ $taskStats['cancelled'] }}</div>
                    <div class="text-xs text-gray-400">Cancelled</div>
                </div>
            </div>
        </div>

        {{-- Recent Tasks --}}
        <div class="admin-card">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Recent Tasks</h3>
                <a href="{{ route('admin.users.tasks', $user) }}" class="text-xs text-indigo-500 hover:text-indigo-600 font-medium">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr><th>Task</th><th>Status</th><th>Priority</th><th>Created</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentTasks as $task)
                        <tr>
                            <td>
                                <span class="font-medium text-gray-700">{{ Str::limit($task->title, 40) }}</span>
                                @if($task->category)
                                <span class="text-xs text-gray-400 block">{{ $task->category->name }}</span>
                                @endif
                            </td>
                            <td><span class="badge badge-{{ $task->status === 'completed' ? 'success' : ($task->status === 'pending' ? 'warning' : 'info') }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span></td>
                            <td><span class="badge badge-{{ $task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : 'secondary') }}">{{ ucfirst($task->priority) }}</span></td>
                            <td class="text-xs text-gray-400">{{ $task->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-gray-400 py-6">No tasks</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Active Sessions --}}
        <div class="admin-card">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Active Sessions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr><th>IP Address</th><th>User Agent</th><th>Last Activity</th></tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                        <tr>
                            <td class="font-mono text-xs">{{ $session->ip_address ?? 'Unknown' }}</td>
                            <td class="text-xs text-gray-500 max-w-xs truncate">{{ Str::limit($session->user_agent ?? 'Unknown', 80) }}</td>
                            <td class="text-xs text-gray-400">{{ $session->last_activity_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-gray-400 py-6">No active sessions</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Support Tickets --}}
        @if($tickets->count())
        <div class="admin-card">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Support Tickets</h3>
            </div>
            <div class="p-5 space-y-3">
                @foreach($tickets as $ticket)
                <a href="{{ route('admin.tickets.show', $ticket) }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $ticket->subject }}</p>
                        <p class="text-xs text-gray-400">#{{ $ticket->ticket_number }} &bull; {{ $ticket->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="badge badge-{{ $ticket->status === 'open' ? 'warning' : ($ticket->status === 'closed' ? 'secondary' : 'info') }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
