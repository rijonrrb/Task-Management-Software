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

    <title>@yield('title', 'TaskFlow') — {{ config('app.name') }}</title>

    {{-- Vite: Compiles our CSS (Tailwind) and JS (Vue + Echo) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    {{--
    ╔══════════════════════════════════════╗
    ║  #app — Vue.js mounts here!         ║
    ║  All Vue components work inside this ║
    ╚══════════════════════════════════════╝
    --}}
    <div id="app" class="min-h-screen flex flex-col">

        {{-- ═══════════ NAVIGATION BAR ═══════════ --}}
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    {{-- Logo & Brand --}}
                    <div class="flex items-center gap-8">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                TaskFlow
                            </span>
                        </a>

                        {{-- Navigation Links (only show when logged in) --}}
                        @auth
                        <div class="hidden md:flex items-center gap-1">
                            <a href="{{ route('dashboard') }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                📊 Dashboard
                            </a>
                            <a href="{{ route('tasks.index') }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('tasks.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                ✅ Tasks
                            </a>
                            <a href="{{ route('categories.index') }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('categories.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                🏷️ Categories
                            </a>
                            <a href="{{ route('redis.demo') }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('redis.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                🔴 Redis Lab
                            </a>
                        </div>
                        @endauth
                    </div>

                    {{-- Right Side: User Menu --}}
                    <div class="flex items-center gap-4">
                        @auth
                            {{-- User Avatar & Name --}}
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ Auth::user()->initials }}
                                </div>
                                <span class="hidden sm:block text-sm font-medium text-gray-700">
                                    {{ Auth::user()->first_name }}
                                </span>
                            </div>

                            {{-- Logout Button --}}
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 text-sm text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 transition">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                Sign Up
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Mobile Navigation --}}
            @auth
            <div class="md:hidden border-t border-gray-100 px-4 py-2 flex gap-1 overflow-x-auto">
                <a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600' }}">📊 Dashboard</a>
                <a href="{{ route('tasks.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap {{ request()->routeIs('tasks.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600' }}">✅ Tasks</a>
                <a href="{{ route('categories.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap {{ request()->routeIs('categories.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600' }}">🏷️ Categories</a>
                <a href="{{ route('redis.demo') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap {{ request()->routeIs('redis.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600' }}">🔴 Redis</a>
            </div>
            @endauth
        </nav>

        {{-- ═══════════ FLASH MESSAGES ═══════════ --}}
        @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        </div>
        @endif

        {{-- ═══════════ MAIN CONTENT ═══════════ --}}
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </main>

        {{-- ═══════════ FOOTER ═══════════ --}}
        <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-500">
                        🚀 <span class="font-medium">TaskFlow</span> — A Laravel + Redis + Vue.js Learning Project
                    </p>
                    <div class="flex items-center gap-4 text-sm text-gray-400">
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
