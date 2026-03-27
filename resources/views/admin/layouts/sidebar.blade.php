{{-- Admin Sidebar - Metronic Tailwind Collapsible --}}
<aside class="kt-sidebar" id="adminSidebar">

    {{-- Logo --}}
    <div class="kt-sidebar-logo">
        <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <span class="kt-logo-text text-white font-bold text-lg tracking-tight">TaskFlow</span>
    </div>

    {{-- Scrollable navigation --}}
    <div class="kt-sidebar-scroll">
        <nav class="py-3">
            {{-- DASHBOARDS --}}
            <div class="kt-nav-section">
                <span class="kt-nav-section-text">Dashboards</span>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="kt-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                <svg class="kt-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                <span class="kt-nav-text">Dashboard</span>
            </a>

            {{-- USER --}}
            <div class="kt-nav-section">
                <span class="kt-nav-section-text">User</span>
            </div>
            <a href="{{ route('admin.users.index') }}" class="kt-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" data-tooltip="Users">
                <svg class="kt-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                <span class="kt-nav-text">Users</span>
            </a>
            <a href="{{ route('admin.profile.index') }}" class="kt-nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" data-tooltip="My Profile">
                <svg class="kt-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="kt-nav-text">My Profile</span>
            </a>

            {{-- APPS --}}
            <div class="kt-nav-section">
                <span class="kt-nav-section-text">Apps</span>
            </div>
            <a href="{{ route('admin.tickets.index') }}" class="kt-nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}" data-tooltip="Support Tickets">
                <svg class="kt-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                </svg>
                <span class="kt-nav-text">Support Tickets</span>
                @php $openTicketCount = \App\Models\SupportTicket::open()->count(); @endphp
                @if($openTicketCount > 0)
                    <span class="kt-nav-badge ml-auto bg-red-500/20 text-red-400 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $openTicketCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.pages.index') }}" class="kt-nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}" data-tooltip="Custom Pages">
                <svg class="kt-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span class="kt-nav-text">Custom Pages</span>
            </a>

            {{-- AI --}}
            <div class="kt-nav-section">
                <span class="kt-nav-section-text">Artificial Intelligence</span>
            </div>
            <a href="{{ route('admin.ai.settings') }}" class="kt-nav-link {{ request()->routeIs('admin.ai.settings') ? 'active' : '' }}" data-tooltip="AI Settings">
                <svg class="kt-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                </svg>
                <span class="kt-nav-text">AI Settings</span>
            </a>
            <a href="{{ route('admin.ai.prompts.index') }}" class="kt-nav-link {{ request()->routeIs('admin.ai.prompts.*') ? 'active' : '' }}" data-tooltip="AI Prompts">
                <svg class="kt-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                </svg>
                <span class="kt-nav-text">AI Prompts</span>
            </a>

            {{-- SETTINGS --}}
            <div class="kt-nav-section">
                <span class="kt-nav-section-text">Settings</span>
            </div>
            <a href="{{ route('admin.settings.index') }}" class="kt-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" data-tooltip="Site Settings">
                <svg class="kt-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="kt-nav-text">Site Settings</span>
            </a>
            <a href="{{ route('admin.seo.index') }}" class="kt-nav-link {{ request()->routeIs('admin.seo.*') ? 'active' : '' }}" data-tooltip="SEO Setup">
                <svg class="kt-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <span class="kt-nav-text">SEO Setup</span>
            </a>
            <a href="{{ route('admin.security.index') }}" class="kt-nav-link {{ request()->routeIs('admin.security.*') ? 'active' : '' }}" data-tooltip="Security">
                <svg class="kt-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
                <span class="kt-nav-text">Security</span>
            </a>

            {{-- Divider + Back to Site --}}
            <div class="border-t border-white/[0.06] my-4 mx-4"></div>
            <a href="{{ route('dashboard') }}" class="kt-nav-link" data-tooltip="Back to Site">
                <svg class="kt-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
                <span class="kt-nav-text">Back to Site</span>
            </a>
        </nav>
    </div>

    {{-- User panel at bottom --}}
    <div class="kt-user-panel">
        <div class="kt-user-avatar w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
            {{ auth('admin')->user()->initials }}
        </div>
        <div class="kt-user-info flex-1 min-w-0">
            <div class="text-sm text-white font-medium truncate">{{ auth('admin')->user()->name }}</div>
            <div class="text-[11px] text-gray-500 truncate">{{ auth('admin')->user()->email }}</div>
        </div>
        <form action="{{ route('admin.logout') }}" method="POST" class="kt-logout-btn">
            @csrf
            <button type="submit" class="p-1.5 text-gray-500 hover:text-red-400 transition" title="Logout">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </form>
    </div>
</aside>
