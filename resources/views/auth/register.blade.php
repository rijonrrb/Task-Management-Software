{{--
╔══════════════════════════════════════════════════════════════╗
║  VIEW: Registration Page                                     ║
║  Learning: Form validation, password confirmation, old()     ║
╚══════════════════════════════════════════════════════════════╝
--}}
@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-sm">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-[#1c2a2a] border border-teal-700/50 rounded-xl flex items-center justify-center mx-auto mb-4">
                <span class="text-teal-400 text-sm font-bold font-mono">&lt;/&gt;</span>
            </div>
            <h1 class="text-xl font-bold text-white">Create Account</h1>
            <p class="text-sm text-gray-500 mt-1">Start managing your tasks today</p>
        </div>

        {{-- Register Card --}}
        <div class="bg-[#161b22] border border-[#30363d] rounded-xl overflow-hidden">
            <form action="{{ route('register') }}" method="POST" class="px-6 py-6 space-y-4">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-xs font-medium text-gray-400 mb-1.5">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="w-full px-3 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:border-teal-700 transition @error('name') border-red-600 @enderror"
                        placeholder="John Doe"
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-medium text-gray-400 mb-1.5">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full px-3 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:border-teal-700 transition @error('email') border-red-600 @enderror"
                        placeholder="you@example.com"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-medium text-gray-400 mb-1.5">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-3 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:border-teal-700 transition @error('password') border-red-600 @enderror"
                        placeholder="Min 6 characters"
                    >
                    @error('password')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-gray-400 mb-1.5">Confirm Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="w-full px-3 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:border-teal-700 transition"
                        placeholder="Repeat your password"
                    >
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-2.5 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-500 transition">
                    Create Account
                </button>
            </form>

            {{-- Login Link --}}
            <div class="px-6 pb-5 text-center border-t border-[#30363d] pt-4">
                <p class="text-xs text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-teal-400 hover:text-teal-300 transition">
                        Sign in
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
