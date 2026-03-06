@extends('admin.layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('page-subtitle', 'Update your account information')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Profile Card --}}
    <div class="admin-card p-6 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto">
            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name)[1] ?? '', 0, 1)) }}
        </div>
        <h3 class="mt-4 text-lg font-semibold text-gray-700">{{ $user->name }}</h3>
        <p class="text-sm text-gray-400">{{ $user->email }}</p>
        <div class="mt-3">
            <span class="admin-badge admin-badge-primary">Admin</span>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-400 space-y-1">
            <p>Joined {{ $user->created_at->format('M d, Y') }}</p>
            @if($user->last_login_at)
            <p>Last login {{ $user->last_login_at->diffForHumans() }}</p>
            @endif
        </div>
    </div>

    {{-- Profile & Password Forms --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Update Profile --}}
        <div class="admin-card">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Profile Information</h3>
            </div>
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-5 space-y-4">
                    <div>
                        <label class="admin-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="admin-input @error('name') border-red-400 @enderror" required>
                        @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="admin-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="admin-input @error('email') border-red-400 @enderror" required>
                        @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="p-5 border-t border-gray-100 bg-gray-50/50">
                    <button type="submit" class="btn-admin btn-admin-primary">Update Profile</button>
                </div>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="admin-card">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Change Password</h3>
            </div>
            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-5 space-y-4">
                    <div>
                        <label class="admin-label">Current Password</label>
                        <input type="password" name="current_password" class="admin-input @error('current_password') border-red-400 @enderror" required>
                        @error('current_password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="admin-label">New Password</label>
                        <input type="password" name="password" class="admin-input @error('password') border-red-400 @enderror" required>
                        <p class="text-xs text-gray-400 mt-1">Min 8 characters, mixed case, letters and numbers</p>
                        @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="admin-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="admin-input" required>
                    </div>
                </div>
                <div class="p-5 border-t border-gray-100 bg-gray-50/50">
                    <button type="submit" class="btn-admin btn-admin-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
