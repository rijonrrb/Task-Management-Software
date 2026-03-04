{{-- LAYOUT: Main Application Layout --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TaskFlow') &mdash; {{ config('app.name') }}</title>
    @include('layouts.style')
</head>
<body class="bg-slate-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100 min-h-screen antialiased">

    {{-- Page loader & progress bar OUTSIDE #app so opacity:0 on #app doesn't hide them --}}
    <div id="page-loader"></div>
    <div id="top-progress-bar" style="position:fixed;top:0;left:0;height:3px;width:0%;background:linear-gradient(90deg,#6366f1,#a855f7);z-index:10000;transition:width .2s ease;"></div>

    {{-- Vue app mount point --}}
    <div id="app" class="min-h-screen flex flex-col opacity-0" style="transition:opacity .3s ease;">

        @include('layouts.header')

        {{-- ═══════════ MAIN CONTENT ═══════════ --}}
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </main>

        @include('layouts.footer')

        {{-- Real-time notification toast (Vue component) --}}
        @auth
            <notification-toast :user-id="{{ Auth::id() }}" />
        @endauth

    </div>

    @include('layouts.script')

</body>
</html>
