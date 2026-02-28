{{--
╔══════════════════════════════════════════════════════════════╗
║  LAYOUT: Main Application Layout                             ║
║  Purpose: Base layout with navigation, sidebar, flash msgs   ║
║  Learning: Blade layouts, @yield, @section, @auth directives ║
╚══════════════════════════════════════════════════════════════╝

All pages extend this layout using:
  @extends('layouts.app')
  @section('content') ... @endsection
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF Token — Required for all POST/PUT/DELETE forms --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Laravel Tasks') — {{ config('app.name') }}</title>

    {{-- Vite: Compiles our CSS (Tailwind) and JS (Vue + Echo) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0d1117] text-white min-h-screen">
    {{--
    ╔══════════════════════════════════════╗
    ║  #app — Vue.js mounts here!         ║
    ║  All Vue components work inside this ║
    ╚══════════════════════════════════════╝
    --}}
    <div id="app" class="min-h-screen flex flex-col">

        {{-- ═══════════ NAVIGATION BAR ═══════════ --}}
        <nav class="bg-[#161b22] border-b border-[#30363d] sticky top-0 z-40">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-14">
                    {{-- Logo & Brand --}}
                    <div class="flex items-center gap-8">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-[#1c2a2a] border border-teal-700/50 rounded-lg flex items-center justify-center">
                                <span class="text-teal-400 text-xs font-bold font-mono">&lt;/&gt;</span>
                            </div>
                            <div class="leading-tight">
                                <div class="text-sm font-bold text-white">Laravel Tasks</div>
                                <div class="text-[10px] text-gray-500">Project task manager</div>
                            </div>
                        </a>

                        {{-- Navigation Links (only show when logged in) --}}
                        @auth
                        <div class="hidden md:flex items-center gap-1">
                            <a href="{{ route('dashboard') }}"
                               class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-[#21262d] text-teal-400' : 'text-gray-400 hover:text-gray-200 hover:bg-[#21262d]' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('tasks.index') }}"
                               class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('tasks.*') ? 'bg-[#21262d] text-teal-400' : 'text-gray-400 hover:text-gray-200 hover:bg-[#21262d]' }}">
                                Tasks
                            </a>
                            <a href="{{ route('categories.index') }}"
                               class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('categories.*') ? 'bg-[#21262d] text-teal-400' : 'text-gray-400 hover:text-gray-200 hover:bg-[#21262d]' }}">
                                Categories
                            </a>
                            <a href="{{ route('redis.demo') }}"
                               class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('redis.*') ? 'bg-[#21262d] text-teal-400' : 'text-gray-400 hover:text-gray-200 hover:bg-[#21262d]' }}">
                                Redis Lab
                            </a>
                        </div>
                        @endauth
                    </div>

                    {{-- Right Side: User Menu --}}
                    <div class="flex items-center gap-3">
                        @auth
                            {{-- User Avatar & Name --}}
                            <span class="hidden sm:block text-sm text-gray-400">
                                {{ Auth::user()->first_name }}
                            </span>
                            <div class="w-7 h-7 bg-teal-700 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ Auth::user()->initials }}
                            </div>

                            {{-- Logout Button --}}
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 text-xs text-gray-500 hover:text-red-400 border border-[#30363d] hover:border-red-800 rounded-md transition">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-1.5 bg-teal-600 text-white text-sm font-medium rounded-md hover:bg-teal-500 transition">
                                Sign Up
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Mobile Navigation --}}
            @auth
            <div class="md:hidden border-t border-[#30363d] px-4 py-2 flex gap-1 overflow-x-auto">
                <a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded-md text-xs font-medium whitespace-nowrap {{ request()->routeIs('dashboard') ? 'bg-[#21262d] text-teal-400' : 'text-gray-500' }}">Dashboard</a>
                <a href="{{ route('tasks.index') }}" class="px-3 py-1.5 rounded-md text-xs font-medium whitespace-nowrap {{ request()->routeIs('tasks.*') ? 'bg-[#21262d] text-teal-400' : 'text-gray-500' }}">Tasks</a>
                <a href="{{ route('categories.index') }}" class="px-3 py-1.5 rounded-md text-xs font-medium whitespace-nowrap {{ request()->routeIs('categories.*') ? 'bg-[#21262d] text-teal-400' : 'text-gray-500' }}">Categories</a>
                <a href="{{ route('redis.demo') }}" class="px-3 py-1.5 rounded-md text-xs font-medium whitespace-nowrap {{ request()->routeIs('redis.*') ? 'bg-[#21262d] text-teal-400' : 'text-gray-500' }}">Redis</a>
            </div>
            @endauth
        </nav>

        {{-- ═══════════ FLASH MESSAGES ═══════════ --}}
        @if(session('success'))
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-teal-900/30 border border-teal-700/50 text-teal-300 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                <svg class="w-4 h-4 text-teal-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-900/30 border border-red-700/50 text-red-300 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        </div>
        @endif

        {{-- ═══════════ MAIN CONTENT ═══════════ --}}
        <main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </main>

        {{-- ═══════════ FOOTER ═══════════ --}}
        <footer class="border-t border-[#30363d] py-5 mt-auto">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-2">
                    <p class="text-xs text-gray-600">
                        <span class="font-mono text-teal-600">&lt;/&gt;</span> Laravel Tasks — Learning Project
                    </p>
                    <div class="flex items-center gap-3 text-xs text-gray-700">
                        <span>Laravel {{ app()->version() }}</span>
                        <span>•</span>
                        <span>Redis + Pusher + Vue 3</span>
                    </div>
                </div>
            </div>
        </footer>

        {{-- ═══════════ VUE: Real-time Notification Toast ═══════════ --}}
        @auth
            {{-- This Vue component listens for Pusher events and shows toasts --}}
            <notification-toast :user-id="{{ Auth::id() }}" />
        @endauth
    </div>
</body>
</html>
