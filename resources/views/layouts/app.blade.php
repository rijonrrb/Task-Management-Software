{{-- LAYOUT: Main Application Layout --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TaskFlow') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Theme initializer (prevents flash of wrong theme) --}}
    <script>
        (function() {
            var stored = localStorage.getItem('theme');
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var shouldDark = stored === 'dark' || (stored !== 'light' && prefersDark);
            document.documentElement.classList.toggle('dark', shouldDark);
        })();
    </script>
</head>
<body class="bg-slate-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100 min-h-screen antialiased">

    {{-- ═══════ PAGE LOADER ═══════ --}}
    <div id="page-loader" class="dark-loader">
        <div class="flex flex-col items-center gap-4">
            <div class="loader-spinner"></div>
            <span class="text-sm text-slate-400 font-medium tracking-wide animate-pulse">Loading...</span>
        </div>
    </div>
    <div id="top-progress-bar" style="width: 0%;"></div>

    {{-- ═══════ VUE APP MOUNT ═══════ --}}
    <div id="app" class="min-h-screen flex flex-col opacity-0" style="transition: opacity 0.3s ease;">

        {{-- ═══════════ NAVIGATION BAR ═══════════ --}}
        <nav class="bg-white/80 dark:bg-slate-900/80 border-b border-slate-200 dark:border-slate-700/50 sticky top-0 z-40 glass">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    {{-- Logo & Brand --}}
                    <div class="flex items-center gap-8">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                            <div class="w-9 h-9 gradient-primary rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:shadow-indigo-500/40 transition-shadow">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-lg font-bold bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">
                                TaskFlow
                            </span>
                        </a>

                        {{-- Navigation Links --}}
                        @auth
                        <div class="hidden md:flex items-center gap-1 bg-slate-100 dark:bg-slate-800/50 rounded-xl p-1">
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                               {{ request()->routeIs('dashboard') ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('tasks.index') }}"
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                               {{ request()->routeIs('tasks.*') ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                Tasks
                            </a>
                            <a href="{{ route('categories.index') }}"
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                               {{ request()->routeIs('categories.*') ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Categories
                            </a>
                            <a href="{{ route('redis.demo') }}"
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                               {{ request()->routeIs('redis.*') ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2" />
                                </svg>
                                Redis Lab
                            </a>
                            <a href="{{ route('profile.show') }}"
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                               {{ request()->routeIs('profile.*') ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profile
                            </a>
                        </div>
                        @endauth
                    </div>

                    {{-- Right Side --}}
                    <div class="flex items-center gap-3">
                        {{-- Theme Toggle --}}
                        <div class="relative" id="theme-toggle-wrapper">
                            <button id="theme-toggle-btn"
                                    class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-indigo-500 dark:hover:text-indigo-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-200"
                                    title="Toggle theme">
                                {{-- Sun icon (shown in dark mode) --}}
                                <svg class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                {{-- Moon icon (shown in light mode) --}}
                                <svg class="w-5 h-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                            </button>
                            {{-- Theme dropdown --}}
                            <div id="theme-dropdown" class="hidden absolute right-0 mt-2 w-44 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 py-1 z-50 animate-scale-in">
                                <button onclick="setTheme('light')" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Light
                                    <span id="theme-check-light" class="ml-auto text-indigo-500 hidden">✓</span>
                                </button>
                                <button onclick="setTheme('dark')" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                    </svg>
                                    Dark
                                    <span id="theme-check-dark" class="ml-auto text-indigo-500 hidden">✓</span>
                                </button>
                                <button onclick="setTheme('system')" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    System
                                    <span id="theme-check-system" class="ml-auto text-indigo-500 hidden">✓</span>
                                </button>
                            </div>
                        </div>

                        @auth
                            {{-- User Avatar & Name --}}
                            <a href="{{ route('profile.show') }}" class="hidden sm:flex items-center gap-2 hover:opacity-80 transition">
                                <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                                    {{ Auth::user()->first_name }}
                                </span>
                            </a>
                            <a href="{{ route('profile.show') }}" class="w-9 h-9 gradient-primary rounded-xl flex items-center justify-center text-white text-xs font-bold shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 hover:scale-105 transition-all duration-200 {{ request()->routeIs('profile.*') ? 'ring-2 ring-indigo-400 ring-offset-2 dark:ring-offset-slate-900' : '' }}">
                                {{ Auth::user()->initials }}
                            </a>

                            {{-- Logout --}}
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="p-2 rounded-xl text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all duration-200 btn-press"
                                    title="Sign out">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-slate-500 dark:text-slate-400 hover:text-indigo-500 dark:hover:text-indigo-400 font-medium transition">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 gradient-primary text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 btn-press">
                                Sign Up
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Mobile Navigation --}}
            @auth
            <div class="md:hidden border-t border-slate-200 dark:border-slate-700/50 px-4 py-2 flex gap-1 overflow-x-auto bg-white/80 dark:bg-slate-900/80">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium whitespace-nowrap transition-all
                    {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" /></svg>
                    Dashboard
                </a>
                <a href="{{ route('tasks.index') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium whitespace-nowrap transition-all
                    {{ request()->routeIs('tasks.*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                    Tasks
                </a>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium whitespace-nowrap transition-all
                    {{ request()->routeIs('categories.*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                    Categories
                </a>
                <a href="{{ route('redis.demo') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium whitespace-nowrap transition-all
                    {{ request()->routeIs('redis.*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2" /></svg>
                    Redis
                </a>
                <a href="{{ route('profile.show') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium whitespace-nowrap transition-all
                    {{ request()->routeIs('profile.*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    Profile
                </a>
            </div>
            @endauth
        </nav>

        {{-- ═══════════ FLASH MESSAGES ═══════════ --}}
        @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 animate-fade-in-up">
            <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl flex items-center gap-3 text-sm shadow-sm">
                <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 animate-fade-in-up">
            <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl flex items-center gap-3 text-sm shadow-sm">
                <div class="w-8 h-8 bg-red-100 dark:bg-red-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        {{-- ═══════════ MAIN CONTENT ═══════════ --}}
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </main>

        {{-- ═══════════ FOOTER ═══════════ --}}
        <footer class="border-t border-slate-200 dark:border-slate-800 py-6 mt-auto bg-white/50 dark:bg-slate-900/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 gradient-primary rounded-lg flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs text-slate-500 dark:text-slate-500 font-medium">TaskFlow</span>
                        <span class="text-xs text-slate-300 dark:text-slate-700">•</span>
                        <span class="text-xs text-slate-400 dark:text-slate-600">Built with Laravel {{ app()->version() }}</span>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-slate-400 dark:text-slate-600">
                        <span>Redis</span>
                        <span>•</span>
                        <span>Pusher</span>
                        <span>•</span>
                        <span>Vue 3</span>
                    </div>
                </div>
            </div>
        </footer>

        {{-- Vue: Real-time Notification Toast --}}
        @auth
            <notification-toast :user-id="{{ Auth::id() }}" />
        @endauth
    </div>

    {{-- ═══════════ THEME & LOADER SCRIPTS ═══════════ --}}
    <script>
        // === Page Loader ===
        (function() {
            const loader = document.getElementById('page-loader');
            const progressBar = document.getElementById('top-progress-bar');
            const appEl = document.getElementById('app');

            // Set loader theme
            if (document.documentElement.classList.contains('dark')) {
                loader.className = 'dark-loader';
                loader.style.background = '#0f172a';
            } else {
                loader.className = 'light-loader';
                loader.style.background = '#f8fafc';
            }
            loader.style.position = 'fixed';
            loader.style.top = '0';
            loader.style.left = '0';
            loader.style.width = '100%';
            loader.style.height = '100%';
            loader.style.zIndex = '9999';
            loader.style.display = 'flex';
            loader.style.alignItems = 'center';
            loader.style.justifyContent = 'center';

            // Progress bar animation
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 15 + 5;
                if (progress > 90) progress = 90;
                progressBar.style.width = progress + '%';
            }, 150);

            window.addEventListener('load', () => {
                clearInterval(progressInterval);
                progressBar.style.width = '100%';
                setTimeout(() => {
                    loader.classList.add('loaded');
                    progressBar.style.opacity = '0';
                    appEl.style.opacity = '1';
                    setTimeout(() => {
                        progressBar.style.display = 'none';
                    }, 400);
                }, 300);
            });

            // Show loader on navigation
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a[href]');
                if (link && !link.target && !link.getAttribute('href').startsWith('#') && !link.getAttribute('href').startsWith('javascript') && !e.ctrlKey && !e.metaKey) {
                    const href = link.getAttribute('href');
                    if (href && href.startsWith('/') || href.startsWith(window.location.origin)) {
                        progressBar.style.display = 'block';
                        progressBar.style.opacity = '1';
                        progressBar.style.width = '0%';
                        let navProgress = 0;
                        const navInterval = setInterval(() => {
                            navProgress += Math.random() * 20 + 10;
                            if (navProgress > 85) { navProgress = 85; clearInterval(navInterval); }
                            progressBar.style.width = navProgress + '%';
                        }, 100);
                    }
                }
            });

            // Show loader on form submit
            document.addEventListener('submit', () => {
                progressBar.style.display = 'block';
                progressBar.style.opacity = '1';
                progressBar.style.width = '30%';
            });
        })();

        // === Theme Toggle ===
        var _themeDropdownOpen = false;

        function setTheme(mode) {
            document.documentElement.classList.add('theme-transitioning');
            localStorage.setItem('theme', mode);
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var shouldDark = mode === 'dark' || (mode === 'system' && prefersDark);
            document.documentElement.classList.toggle('dark', shouldDark);
            updateThemeChecks();
            setTimeout(function() {
                document.documentElement.classList.remove('theme-transitioning');
            }, 300);
            closeThemeDropdown();
        }

        function updateThemeChecks() {
            var current = localStorage.getItem('theme') || 'system';
            ['light', 'dark', 'system'].forEach(function(t) {
                var el = document.getElementById('theme-check-' + t);
                if (el) el.classList.toggle('hidden', t !== current);
            });
        }

        function closeThemeDropdown() {
            _themeDropdownOpen = false;
            var dd = document.getElementById('theme-dropdown');
            if (dd) dd.classList.add('hidden');
        }

        // Use mousedown on document to detect outside clicks BEFORE click fires
        document.addEventListener('mousedown', function(e) {
            var wrapper = document.getElementById('theme-toggle-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                closeThemeDropdown();
            }
        });

        var toggleBtn = document.getElementById('theme-toggle-btn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                var dd = document.getElementById('theme-dropdown');
                if (!dd) return;
                _themeDropdownOpen = !_themeDropdownOpen;
                dd.classList.toggle('hidden', !_themeDropdownOpen);
                if (_themeDropdownOpen) updateThemeChecks();
            });
        }

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            if (localStorage.getItem('theme') === 'system' || !localStorage.getItem('theme')) {
                document.documentElement.classList.toggle('dark', e.matches);
            }
        });

        // Initial check marks
        updateThemeChecks();
    </script>
</body>
</html>
