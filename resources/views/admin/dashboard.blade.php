@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Central Hub')

@section('breadcrumb')
    <span class="text-gray-600 font-medium">Dashboard</span>
@endsection

@section('content')
<div class="kt-fade-in">
    {{-- Welcome banner --}}
    <div class="kt-card mb-6 overflow-hidden">
        <div class="relative px-6 py-5 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Welcome back, {{ auth('admin')->user()->name }}!</h2>
                <p class="text-sm text-gray-400 mt-0.5">Here's a summary of what's happening across your platform.</p>
            </div>
            <div class="hidden sm:block">
                <span class="text-xs text-gray-400">{{ now()->format('l, F j, Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Stats Cards Row 1 --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
        {{-- Total Users --}}
        <div class="kt-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Users</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['total_users']) }}</h3>
                    <p class="text-xs mt-1.5">
                        <span class="inline-flex items-center gap-0.5 text-emerald-500 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>
                            +{{ $stats['new_users_today'] }}
                        </span>
                        <span class="text-gray-400 ml-1">today</span>
                    </p>
                </div>
                <div class="kt-stat-icon primary">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                </div>
            </div>
        </div>

        {{-- Total Tasks --}}
        <div class="kt-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Tasks</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['total_tasks']) }}</h3>
                    <p class="text-xs text-gray-400 mt-1.5">{{ $stats['completed_tasks'] }} completed</p>
                </div>
                <div class="kt-stat-icon success">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
        </div>

        {{-- Open Tickets --}}
        <div class="kt-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Open Tickets</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['open_tickets'] }}</h3>
                    <p class="text-xs text-gray-400 mt-1.5">{{ $stats['total_tickets'] }} total</p>
                </div>
                <div class="kt-stat-icon warning">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" /></svg>
                </div>
            </div>
        </div>

        {{-- Failed Logins --}}
        <div class="kt-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Failed Logins Today</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $securityStats['failed_logins_today'] }}</h3>
                    <p class="text-xs {{ $stats['banned_users'] > 0 ? 'text-red-500' : 'text-gray-400' }} mt-1.5">{{ $stats['banned_users'] }} banned users</p>
                </div>
                <div class="kt-stat-icon danger">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row 2 --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="kt-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">New Users This Week</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['new_users_week'] }}</h3>
                </div>
                <div class="kt-stat-icon info">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                </div>
            </div>
        </div>
        <div class="kt-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Pending Tasks</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['pending_tasks'] }}</h3>
                </div>
                <div class="kt-stat-icon purple">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
        </div>
        <div class="kt-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Published Pages</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['published_pages'] }}</h3>
                </div>
                <div class="kt-stat-icon success">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Recent Users --}}
        <div class="xl:col-span-2 kt-card">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Recent Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-indigo-500 hover:text-indigo-600 font-medium transition">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="kt-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        {{ $user->initials }}
                                    </div>
                                    <span class="font-medium text-gray-700">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="text-gray-500">{{ $user->email }}</td>
                            <td>
                                @if($user->is_banned)
                                    <span class="kt-badge kt-badge-danger">Banned</span>
                                @else
                                    <span class="kt-badge kt-badge-success">Active</span>
                                @endif
                            </td>
                            <td class="text-gray-400 text-xs">{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-gray-400 py-8">No users yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Tickets --}}
        <div class="kt-card">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Recent Tickets</h3>
                <a href="{{ route('admin.tickets.index') }}" class="text-xs text-indigo-500 hover:text-indigo-600 font-medium transition">View All</a>
            </div>
            <div class="p-5 space-y-3">
                @forelse($recentTickets as $ticket)
                <a href="{{ route('admin.tickets.show', $ticket) }}" class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition group">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" /></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 group-hover:text-indigo-600 truncate transition">{{ $ticket->subject }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $ticket->user->name }} &bull; {{ $ticket->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="kt-badge {{ $ticket->status === 'open' ? 'kt-badge-warning' : ($ticket->status === 'closed' ? 'kt-badge-secondary' : 'kt-badge-info') }} flex-shrink-0">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                </a>
                @empty
                <div class="text-center text-gray-400 py-8">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" /></svg>
                    <p class="text-sm">No tickets yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
        <a href="{{ route('admin.users.index') }}" class="kt-card p-5 text-center group hover:border-indigo-200 transition">
            <div class="w-11 h-11 bg-indigo-50 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-indigo-100 transition">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
            </div>
            <p class="text-[13px] font-medium text-gray-600">Manage Users</p>
        </a>
        <a href="{{ route('admin.pages.create') }}" class="kt-card p-5 text-center group hover:border-emerald-200 transition">
            <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-emerald-100 transition">
                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            </div>
            <p class="text-[13px] font-medium text-gray-600">New Page</p>
        </a>
        <a href="{{ route('admin.seo.index') }}" class="kt-card p-5 text-center group hover:border-amber-200 transition">
            <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-amber-100 transition">
                <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            </div>
            <p class="text-[13px] font-medium text-gray-600">SEO Setup</p>
        </a>
        <a href="{{ route('admin.security.index') }}" class="kt-card p-5 text-center group hover:border-red-200 transition">
            <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-red-100 transition">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
            </div>
            <p class="text-[13px] font-medium text-gray-600">Security</p>
        </a>
    </div>
</div>
@endsection
