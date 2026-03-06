{{-- Admin Header - Metronic Tailwind Style --}}
<header class="kt-header">
    <div class="flex items-center justify-between h-full px-5 lg:px-7">
        {{-- Left: Mobile hamburger + Search --}}
        <div class="flex items-center gap-4">
            {{-- Desktop sidebar toggle (Metronic-style) --}}
            <button onclick="KTSidebar.toggle()" id="headerSidebarToggle"
                class="hidden lg:flex w-9 h-9 items-center justify-center rounded-lg hover:bg-gray-100 transition text-gray-500"
                aria-label="Toggle sidebar">
                <svg class="w-[18px] h-[18px] kt-toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                </svg>
            </button>
            {{-- Mobile menu toggle --}}
            <button onclick="KTSidebar.open()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition text-gray-500">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            {{-- Search bar --}}
            <div class="hidden md:flex items-center bg-gray-50 border border-gray-100 rounded-lg px-3.5 py-2 gap-2.5 w-64 focus-within:border-indigo-200 focus-within:bg-white transition-all">
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none text-sm text-gray-600 placeholder-gray-400 w-full" />
            </div>
        </div>

        {{-- Right: Actions --}}
        <div class="flex items-center gap-2">
            {{-- Notifications --}}
            <div class="relative">
                <a href="{{ route('admin.tickets.index') }}" class="w-10 h-10 rounded-lg flex items-center justify-center hover:bg-gray-100 transition relative text-gray-500">
                    <svg class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                    @php $unreadTickets = \App\Models\SupportTicket::open()->count(); @endphp
                    @if($unreadTickets > 0)
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                    @endif
                </a>
            </div>

            {{-- Separator --}}
            <div class="w-px h-7 bg-gray-200 mx-1.5"></div>

            {{-- User dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button class="flex items-center gap-3 pl-1 pr-2 py-1.5 rounded-lg hover:bg-gray-50 transition" onclick="this.parentElement.querySelector('.kt-user-dropdown').classList.toggle('hidden')">
                    <div class="text-right hidden sm:block">
                        <div class="text-[13px] font-semibold text-gray-800 leading-tight">{{ auth('admin')->user()->name }}</div>
                        <div class="text-[11px] text-gray-400 leading-tight">Administrator</div>
                    </div>
                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                        {{ auth('admin')->user()->initials }}
                    </div>
                </button>

                {{-- Dropdown --}}
                <div class="kt-user-dropdown hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-50">
                    <div class="px-4 py-2.5 border-b border-gray-100">
                        <div class="text-sm font-semibold text-gray-800">{{ auth('admin')->user()->name }}</div>
                        <div class="text-xs text-gray-400 truncate">{{ auth('admin')->user()->email }}</div>
                    </div>
                    <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        My Profile
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Settings
                    </a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
