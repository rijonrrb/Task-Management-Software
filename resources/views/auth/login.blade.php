@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-8">
    <div class="w-full max-w-md animate-fade-in-up">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 gradient-primary rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-xl shadow-indigo-500/20 animate-float">
                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Welcome to TaskFlow</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Sign in to continue</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 overflow-hidden">

            {{-- Social Login Buttons --}}
            <div class="px-8 pt-8 pb-4 space-y-3">
                {{-- Google --}}
                <button type="button" class="social-btn w-full flex items-center justify-center gap-3 px-4 py-3 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 btn-press">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </button>

                {{-- Microsoft --}}
                <button type="button" class="social-btn w-full flex items-center justify-center gap-3 px-4 py-3 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 btn-press">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <rect fill="#F25022" x="1" y="1" width="10" height="10"/>
                        <rect fill="#7FBA00" x="13" y="1" width="10" height="10"/>
                        <rect fill="#00A4EF" x="1" y="13" width="10" height="10"/>
                        <rect fill="#FFB900" x="13" y="13" width="10" height="10"/>
                    </svg>
                    Continue with Microsoft
                </button>

                {{-- Facebook --}}
                <button type="button" class="social-btn w-full flex items-center justify-center gap-3 px-4 py-3 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 btn-press">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <circle fill="#1877F2" cx="12" cy="12" r="11"/>
                        <path fill="#fff" d="M16.5 12.5h-2.7v8h-3.3v-8H8.5v-3h2v-1.9c0-2 1.2-3.1 3-3.1.9 0 1.6.1 1.8.1v2.1h-1.2c-1 0-1.2.5-1.2 1.2v1.6h2.4l-.3 3z"/>
                    </svg>
                    Continue with Facebook
                </button>
            </div>

            {{-- Divider --}}
            <div class="px-8 py-2">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-4 bg-white dark:bg-slate-800/50 text-slate-400 dark:text-slate-500 uppercase tracking-wider font-medium">OR</span>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('login') }}" method="POST" class="px-8 pb-6 space-y-5">
                @csrf

                {{-- Email Field --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all @error('email') border-red-400 dark:border-red-500 @enderror"
                            placeholder="you@example.com"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all @error('password') border-red-400 dark:border-red-500 @enderror"
                            placeholder="••••••••"
                        >
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="remember" name="remember"
                            class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Remember me</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full py-3 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                    Sign in
                </button>
            </form>

            {{-- Footer Links --}}
            <div class="px-8 pb-6 flex items-center justify-between">
                <a href="#" class="text-sm text-indigo-500 hover:text-indigo-400 font-medium transition">Forgot password?</a>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Need an account?
                    <a href="{{ route('register') }}" class="text-indigo-500 hover:text-indigo-400 font-semibold transition">
                        Sign up
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
