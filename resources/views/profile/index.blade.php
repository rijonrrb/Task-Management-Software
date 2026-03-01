@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl animate-fade-in-up">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">My Profile</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage your account information and settings</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex gap-1 bg-slate-100 dark:bg-slate-800/50 rounded-xl p-1 mb-6 w-fit" id="profile-tabs">
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
