{{-- Admin Layout - Metronic Tailwind Theme --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') &mdash; {{ config('app.name') }}</title>
    @include('admin.layouts.styles')
</head>
<body class="kt-body font-sans antialiased text-gray-700">
    {{-- Mobile overlay --}}
    <div class="kt-sidebar-overlay" id="sidebarOverlay" onclick="KTSidebar.close()"></div>

    <div id="app" class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

        {{-- Main wrapper --}}
        <div class="kt-main flex-1 flex flex-col min-h-screen transition-all duration-300" id="main-content">
            {{-- Top Header --}}
            @include('admin.layouts.header')

            {{-- Page Content --}}
            <main class="flex-1 p-5 lg:p-7">
                {{-- Breadcrumb --}}
                @hasSection('breadcrumb')
                <div class="mb-5 flex items-center text-sm text-gray-400">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-500 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                    </a>
                    <svg class="w-4 h-4 mx-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    @yield('breadcrumb')
                </div>
                @endif

                {{-- Page Header --}}
                @hasSection('page-header')
                <div class="mb-6">
                    @yield('page-header')
                </div>
                @endif

                @yield('content')
            </main>

            {{-- Footer --}}
            @include('admin.layouts.footer')
        </div>
    </div>

    @include('admin.layouts.scripts')
</body>
</html>
