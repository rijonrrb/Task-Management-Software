@extends('layouts.app')
@section('title', 'My Profile')

@section('content')

{{-- Page Header (outside grid so both columns align at the same start) --}}
<div class="mb-4">
    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">My Profile</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage your account information and settings</p>
</div>

{{-- Tab Navigation (outside grid) --}}
<div class="flex gap-1 bg-slate-100 dark:bg-slate-800/50 rounded-xl p-1 mb-5 w-fit" id="profile-tabs">
    <button onclick="switchTab('info')" id="tab-info"
        class="tab-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm">
        Profile Info
    </button>
    <button onclick="switchTab('password')" id="tab-password"
        class="tab-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
        Password
    </button>
    <button onclick="switchTab('danger')" id="tab-danger"
        class="tab-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-slate-500 dark:text-slate-400 hover:text-red-500">
        Danger Zone
    </button>
</div>

<div class="grid lg:grid-cols-3 gap-6 animate-fade-in-up items-start"><div class="lg:col-span-2">

    {{-- ══════ TAB: Profile Info ══════ --}}
    <div id="panel-info" class="tab-panel space-y-5">

        {{-- Avatar Card --}}
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 gradient-primary rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-indigo-500/25 flex-shrink-0">
                    {{ $user->initials }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $user->email }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Active Account
                        </span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">
                            Member since {{ $user->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Profile Form --}}
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                <h3 class="text-sm font-bold text-slate-700 dark:text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Profile Information
                </h3>
            </div>
            <form action="{{ route('profile.update') }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all @error('name') border-red-400 @enderror">
                    @error('name')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all @error('email') border-red-400 @enderror">
                    @error('email')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-6 py-2.5 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-3">
            @php
                $totalTasks = $user->tasks()->count();
                $completedTasks = $user->tasks()->where('status', 'completed')->count();
                $pendingTasks = $user->tasks()->whereIn('status', ['pending', 'in_progress'])->count();
            @endphp
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-4 text-center shadow-sm">
                <div class="text-2xl font-bold text-slate-800 dark:text-white">{{ $totalTasks }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Total Tasks</div>
            </div>
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-4 text-center shadow-sm">
                <div class="text-2xl font-bold text-emerald-500">{{ $completedTasks }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Completed</div>
            </div>
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-4 text-center shadow-sm">
                <div class="text-2xl font-bold text-amber-500">{{ $pendingTasks }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">In Progress</div>
            </div>
        </div>
    </div>

    {{-- ══════ TAB: Change Password ══════ --}}
    <div id="panel-password" class="tab-panel hidden">
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                <h3 class="text-sm font-bold text-slate-700 dark:text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Change Password
                </h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Use a strong password with at least 8 characters, letters and numbers.</p>
            </div>
            <form action="{{ route('profile.password') }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Current Password</label>
                    <input type="password" name="current_password" required placeholder="Enter your current password"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all @error('current_password') border-red-400 @enderror">
                    @error('current_password')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">New Password</label>
                    <input type="password" name="password" required placeholder="Min 8 chars, letters & numbers"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all @error('password') border-red-400 @enderror">
                    @error('password')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required placeholder="Re-enter new password"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 transition-all">
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-6 py-2.5 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════ TAB: Danger Zone ══════ --}}
    <div id="panel-danger" class="tab-panel hidden">
        <div class="bg-white dark:bg-slate-800/50 border border-red-200 dark:border-red-500/30 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-red-100 dark:border-red-500/20 bg-red-50/50 dark:bg-red-500/5">
                <h3 class="text-sm font-bold text-red-600 dark:text-red-400 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    Danger Zone
                </h3>
                <p class="text-xs text-red-500 dark:text-red-400/70 mt-1">These actions are irreversible. Please proceed with caution.</p>
            </div>
            <div class="px-6 py-5 space-y-5">
                <div class="p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl">
                    <h4 class="text-sm font-semibold text-red-700 dark:text-red-300 mb-1">Delete Account</h4>
                    <p class="text-xs text-red-500 dark:text-red-400/80 mb-4">
                        Once you delete your account, all of your tasks, categories, and data will be permanently removed. This action cannot be undone.
                    </p>

                    <form action="{{ route('profile.destroy') }}" method="POST"
                          onsubmit="return confirm('Are you absolutely sure? This will permanently delete your account and ALL your tasks.')">
                        @csrf
                        @method('DELETE')

                        <div class="mb-3">
                            <label class="block text-xs font-medium text-red-600 dark:text-red-400 mb-1.5">
                                Confirm your password to proceed
                            </label>
                            <input type="password" name="password" required placeholder="Enter your password"
                                class="w-full px-4 py-3 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-red-500/30 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:border-red-400 transition-all @error('password') border-red-500 @enderror">
                            @error('password')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit"
                            class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 btn-press shadow-sm hover:shadow-red-500/30 hover:shadow-lg">
                            Delete My Account Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="space-y-4 animate-fade-in-up sticky top-24">

    {{-- Profile Identity Card --}}
    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-sm">
        {{-- Header: gradient banner with centered avatar only --}}
        <div class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 py-3 flex flex-col items-center gap-2">
            <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold shadow-xl"
                 style="background: #7573ff;border:3px solid rgba(255,255,255,0.5);backdrop-filter:blur(4px);">
                <span class="text-white">{{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1)) }}</span>
            </div>
        </div>
        {{-- Body: name, email, member since --}}
        <div class="flex flex-col items-center py-4 px-5 border-t border-slate-100 dark:border-slate-700/50">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white text-center">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 text-center mt-0.5">{{ auth()->user()->email }}</p>
            <span class="mt-2 inline-flex items-center gap-1 text-[10px] font-medium text-slate-400 dark:text-slate-500">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Member since {{ auth()->user()->created_at->format('M Y') }}
            </span>
        </div>
    </div>

    @php
        $userTasks = auth()->user()->tasks ?? collect();
        $totalTasks = $userTasks->count();
        $completedTasks = $userTasks->where('status','completed')->count();
        $pendingTasks = $userTasks->where('status','pending')->count();
        $inProgressTasks = $userTasks->where('status','in_progress')->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    @endphp
    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-5 shadow-sm">
        <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">My Task Stats</h3>
        <div class="flex items-center justify-between mb-1">
            <span class="text-xs text-slate-500 dark:text-slate-400">Completion</span>
            <span class="text-xs font-bold text-indigo-500">{{ $completionRate }}%</span>
        </div>
        <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden mb-4">
            <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full" style="width:{{ $completionRate }}%"></div>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <div class="bg-emerald-50 dark:bg-emerald-500/10 rounded-xl p-2.5 text-center">
                <div class="text-lg font-bold text-emerald-500">{{ $completedTasks }}</div>
                <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">Done</div>
            </div>
            <div class="bg-amber-50 dark:bg-amber-500/10 rounded-xl p-2.5 text-center">
                <div class="text-lg font-bold text-amber-500">{{ $inProgressTasks }}</div>
                <div class="text-[10px] text-amber-600 dark:text-amber-400 font-medium">Active</div>
            </div>
            <div class="bg-blue-50 dark:bg-blue-500/10 rounded-xl p-2.5 text-center">
                <div class="text-lg font-bold text-blue-500">{{ $pendingTasks }}</div>
                <div class="text-[10px] text-blue-600 dark:text-blue-400 font-medium">To Do</div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-5 py-3 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
            <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Quick Links</h3>
        </div>
        <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
            <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 px-5 py-3 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/30 hover:text-indigo-500 transition">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                My Tasks
            </a>
            <a href="{{ route('tasks.create') }}" class="flex items-center gap-3 px-5 py-3 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/30 hover:text-indigo-500 transition">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Task
            </a>
            <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-5 py-3 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/30 hover:text-indigo-500 transition">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                Categories
            </a>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-5 py-3 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/30 hover:text-indigo-500 transition">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>
        </div>
    </div>
</div>
</div>


<script>
function switchTab(tab) {
    // Hide all panels
    document.querySelectorAll('.tab-panel').forEach(function(p) { p.classList.add('hidden'); });
    // Reset all tab buttons
    document.querySelectorAll('.tab-btn').forEach(function(b) {
        b.className = 'tab-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200';
    });
    // Show selected panel
    var panel = document.getElementById('panel-' + tab);
    if (panel) panel.classList.remove('hidden');
    // Highlight active tab
    var activeBtn = document.getElementById('tab-' + tab);
    if (activeBtn) {
        if (tab === 'danger') {
            activeBtn.className = 'tab-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 shadow-sm';
        } else {
            activeBtn.className = 'tab-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm';
        }
    }
}

// Auto-switch to the correct tab if there are validation errors
@if($errors->hasAny(['current_password', 'password']) && !$errors->has('name') && !$errors->has('email'))
    switchTab('password');
@endif
</script>
@endsection
