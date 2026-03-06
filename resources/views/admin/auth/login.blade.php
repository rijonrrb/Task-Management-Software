@extends('layouts.app')
@section('title', 'Admin Login')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-8">
    <div class="w-full max-w-md animate-fade-in-up">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 gradient-primary rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-xl shadow-indigo-500/20 animate-float">
                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Admin Panel</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Sign in with your admin account</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 overflow-hidden">

            {{-- Form --}}
            <form action="{{ route('admin.login.post') }}" method="POST" class="px-8 pt-8 pb-6 space-y-5">
                @csrf

                @if($errors->any())
                    <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-600 dark:text-red-400 text-sm rounded-xl p-3 flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm rounded-xl p-3">
                        {{ session('success') }}
                    </div>
                @endif

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
                            placeholder="admin@example.com"
                        >
                    </div>
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
                </div>

                {{-- Remember Me + User Login --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Remember me</span>
                    </label>
                    <a href="{{ route('login') }}" class="text-sm text-indigo-500 hover:text-indigo-400 font-medium transition">User Login</a>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full py-3 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                    Sign In
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
