{{--
╔══════════════════════════════════════════════════════════════╗
║  VIEW: Login Page                                            ║
║  Learning: Blade forms, @error directive, CSRF protection    ║
╚══════════════════════════════════════════════════════════════╝
--}}
@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-sm">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-[#1c2a2a] border border-teal-700/50 rounded-xl flex items-center justify-center mx-auto mb-4">
                <span class="text-teal-400 text-sm font-bold font-mono">&lt;/&gt;</span>
            </div>
            <h1 class="text-xl font-bold text-white">Laravel Tasks</h1>
            <p class="text-sm text-gray-500 mt-1">Sign in to manage your tasks</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-[#161b22] border border-[#30363d] rounded-xl overflow-hidden">
            {{-- Form --}}
            <form action="{{ route('login') }}" method="POST" class="px-6 py-6 space-y-4">
                @csrf

                {{-- Email Field --}}
                <div>
                    <label for="email" class="block text-xs font-medium text-gray-400 mb-1.5">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-3 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:border-teal-700 transition @error('email') border-red-600 @enderror"
                        placeholder="you@example.com"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div>
                    <label for="password" class="block text-xs font-medium text-gray-400 mb-1.5">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-3 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:border-teal-700 transition @error('password') border-red-600 @enderror"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                        class="w-3.5 h-3.5 rounded border-[#30363d] bg-[#0d1117] text-teal-600 focus:ring-teal-700">
                    <label for="remember" class="ml-2 text-xs text-gray-500">Remember me</label>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full py-2.5 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-500 transition">
                    Sign In
                </button>
            </form>

            {{-- Register Link --}}
            <div class="px-6 pb-5 text-center border-t border-[#30363d] pt-4">
                <p class="text-xs text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-teal-400 hover:text-teal-300 transition">
                        Create one
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
