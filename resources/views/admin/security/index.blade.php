@extends('admin.layouts.app')

@section('title', 'Security Settings')
@section('page-title', 'Security Settings')
@section('page-subtitle', 'Brute force protection, login security, and access control')

@section('content')
<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="admin-card p-4 text-center">
            <p class="text-2xl font-bold text-red-500">{{ $stats['failed_today'] ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-1">Failed Logins Today</p>
        </div>
        <div class="admin-card p-4 text-center">
            <p class="text-2xl font-bold text-orange-500">{{ $stats['locked_accounts'] ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-1">Locked Accounts</p>
        </div>
        <div class="admin-card p-4 text-center">
            <p class="text-2xl font-bold text-purple-500">{{ $stats['banned_users'] ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-1">Banned Users</p>
        </div>
        <div class="admin-card p-4 text-center">
            <p class="text-2xl font-bold text-green-500">{{ $stats['successful_today'] ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-1">Successful Logins Today</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Security Settings Form --}}
        <div class="lg:col-span-2">
            <form action="{{ route('admin.security.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="admin-card">
                    <div class="p-5 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700">Security Configuration</h3>
                    </div>
                    <div class="p-5 space-y-5">
                        {{-- Login Protection --}}
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Login Protection</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="admin-label">Max Login Attempts</label>
                                <input type="number" name="max_login_attempts" value="{{ old('max_login_attempts', $settings['max_login_attempts']) }}" class="admin-input" min="3" max="20">
                                <p class="text-xs text-gray-400 mt-1">Account locked after this many failed attempts</p>
                            </div>
                            <div>
                                <label class="admin-label">Lockout Duration (minutes)</label>
                                <input type="number" name="lockout_duration" value="{{ old('lockout_duration', $settings['lockout_duration']) }}" class="admin-input" min="5" max="120">
                            </div>
                            <div>
                                <label class="admin-label">Minimum Password Length</label>
                                <input type="number" name="min_password_length" value="{{ old('min_password_length', $settings['min_password_length']) }}" class="admin-input" min="6" max="30">
                            </div>
                            <div>
                                <label class="admin-label">Session Lifetime (minutes)</label>
                                <input type="number" name="session_lifetime" value="{{ old('session_lifetime', $settings['session_lifetime']) }}" class="admin-input" min="15" max="1440">
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        {{-- Registration --}}
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Registration Security</h4>
                        <div class="space-y-3">
                            <label class="flex items-center gap-2">
                                <input type="hidden" name="registration_enabled" value="0">
                                <input type="checkbox" name="registration_enabled" value="1" {{ old('registration_enabled', $settings['registration_enabled']) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-600">Allow new registrations</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="hidden" name="require_email_verification" value="0">
                                <input type="checkbox" name="require_email_verification" value="1" {{ old('require_email_verification', $settings['require_email_verification']) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-600">Require email verification</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="hidden" name="force_https" value="0">
                                <input type="checkbox" name="force_https" value="1" {{ old('force_https', $settings['force_https']) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-600">Force HTTPS</span>
                            </label>
                        </div>

                        <hr class="border-gray-100">

                        {{-- Blocked Email Domains --}}
                        <div>
                            <label class="admin-label">Blocked Email Domains</label>
                            <textarea name="blocked_email_domains" class="admin-input font-mono text-sm" rows="4" placeholder="tempmail.com&#10;throwaway.email&#10;guerrillamail.com">{{ old('blocked_email_domains', $settings['blocked_email_domains']) }}</textarea>
                            <p class="text-xs text-gray-400 mt-1">One domain per line. Users with these email domains cannot register.</p>
                        </div>

                        {{-- Blocked IPs --}}
                        <div>
                            <label class="admin-label">Blocked IP Addresses</label>
                            <textarea name="blocked_ips" class="admin-input font-mono text-sm" rows="3" placeholder="192.168.1.1&#10;10.0.0.1">{{ old('blocked_ips', $settings['blocked_ips']) }}</textarea>
                            <p class="text-xs text-gray-400 mt-1">One IP per line. Blocked IPs cannot access the site.</p>
                        </div>
                    </div>
                    <div class="p-5 border-t border-gray-100 bg-gray-50/50 flex gap-3">
                        <button type="submit" class="btn-admin btn-admin-primary">Save Security Settings</button>
                    </div>
                </div>
            </form>

            <form action="{{ route('admin.security.clear-attempts') }}" method="POST" class="mt-3" onsubmit="return confirmAction(event, 'Clear all login attempt records?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-admin btn-admin-outline btn-admin-sm">Clear Login History</button>
            </form>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            {{-- Suspicious IPs --}}
            <div class="admin-card">
                <div class="p-4 border-b border-gray-100">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Suspicious IPs (24h)</h4>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($suspiciousIps as $ip)
                    <div class="p-3 flex items-center justify-between">
                        <div>
                            <span class="text-sm font-mono text-gray-700">{{ $ip->ip_address }}</span>
                            <span class="text-xs text-red-400 ml-2">{{ $ip->attempt_count }} attempts</span>
                        </div>
                        <form action="{{ route('admin.security.block-ip') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="ip" value="{{ $ip->ip_address }}">
                            <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">Block</button>
                        </form>
                    </div>
                    @empty
                    <div class="p-4 text-center text-xs text-gray-400">No suspicious activity detected</div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Failed Attempts --}}
            <div class="admin-card">
                <div class="p-4 border-b border-gray-100">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Recent Failed Logins</h4>
                </div>
                <div class="divide-y divide-gray-50 max-h-80 overflow-y-auto">
                    @forelse($recentAttempts->take(10) as $attempt)
                    <div class="p-3">
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600">{{ $attempt->email }}</span>
                            <span class="text-[10px] text-gray-400">{{ $attempt->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $attempt->ip_address }}</p>
                    </div>
                    @empty
                    <div class="p-4 text-center text-xs text-gray-400">No failed login attempts</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
