{{-- Admin Styles - Metronic Tailwind Theme --}}
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />

{{-- SweetAlert2 --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"/>

{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.min.css"/>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
    body { font-family: 'Inter', sans-serif; }

    /* ========== METRONIC BODY ========== */
    .kt-body { background: #f9f9f9; }

    /* ========== SIDEBAR ========== */
    .kt-sidebar {
        background: #1c1c2b;
        width: 264px;
        min-width: 264px;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), min-width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 50;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .kt-sidebar-scroll {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.08) transparent;
    }
    .kt-sidebar-scroll::-webkit-scrollbar { width: 4px; }
    .kt-sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
    .kt-sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 4px; }

    /* Sidebar - Expanded state (default on desktop) */
    .kt-sidebar .kt-logo-text,
    .kt-sidebar .kt-nav-text,
    .kt-sidebar .kt-nav-badge,
    .kt-sidebar .kt-nav-section-text,
    .kt-sidebar .kt-user-info,
    .kt-sidebar .kt-nav-arrow { opacity: 1; transition: opacity 0.2s ease 0.1s; white-space: nowrap; }

    /* Sidebar - Collapsed state */
    .kt-sidebar.collapsed {
        width: 74px;
        min-width: 74px;
    }
    .kt-sidebar.collapsed .kt-logo-text,
    .kt-sidebar.collapsed .kt-nav-text,
    .kt-sidebar.collapsed .kt-nav-badge,
    .kt-sidebar.collapsed .kt-nav-section-text,
    .kt-sidebar.collapsed .kt-user-info,
    .kt-sidebar.collapsed .kt-nav-arrow { opacity: 0; pointer-events: none; transition: opacity 0.15s ease; width: 0; }

    .kt-sidebar.collapsed .kt-nav-section { height: 0; padding: 0; margin: 8px 0; border-top: 1px solid rgba(255,255,255,0.06); overflow: hidden; }
    .kt-sidebar.collapsed .kt-sidebar-logo { justify-content: center; padding-left: 0; padding-right: 0; }
    .kt-sidebar.collapsed .kt-nav-link { justify-content: center; padding-left: 0; padding-right: 0; margin-left: 8px; margin-right: 8px; }
    .kt-sidebar.collapsed .kt-nav-link .kt-nav-icon { margin: 0; }
    .kt-sidebar.collapsed .kt-user-panel { justify-content: center; padding: 12px 8px; }
    .kt-sidebar.collapsed .kt-user-panel .kt-user-avatar { margin: 0; }
    .kt-sidebar.collapsed .kt-user-panel .kt-logout-btn { display: none; }

    /* Main content transition */
    .kt-main { margin-left: 264px; transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    body.sidebar-collapsed .kt-main { margin-left: 74px; }

    /* Header sidebar toggle button (desktop) */
    #headerSidebarToggle { border: none; outline: none; }
    #headerSidebarToggle:hover { background: #f3f4f6; }
    /* Rotate icon when collapsed */
    body.sidebar-collapsed #headerSidebarToggle .kt-toggle-icon { transform: scaleX(-1); }
    #headerSidebarToggle .kt-toggle-icon { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }

    /* Sidebar nav items */
    .kt-nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 14px;
        color: #9899ac;
        font-size: 13px;
        font-weight: 500;
        border-radius: 6px;
        margin: 1px 12px;
        transition: all 0.15s ease;
        position: relative;
        overflow: hidden;
    }
    .kt-nav-link:hover { background: rgba(255,255,255,0.04); color: #c8c8db; }
    .kt-nav-link.active { background: rgba(99, 102, 241, 0.12); color: #818cf8; }
    .kt-nav-link .kt-nav-icon { width: 18px; height: 18px; flex-shrink: 0; }

    .kt-nav-section {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #565674;
        padding: 20px 14px 6px 26px;
        transition: all 0.2s;
        overflow: hidden;
    }

    /* Sidebar logo area */
    .kt-sidebar-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 20px;
        height: 70px;
        min-height: 70px;
        transition: all 0.3s;
    }

    /* User panel at bottom */
    .kt-user-panel {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        border-top: 1px solid rgba(255,255,255,0.06);
        transition: all 0.3s;
    }

    /* Sidebar submenu */
    .kt-submenu { overflow: hidden; max-height: 0; transition: max-height 0.3s ease; }
    .kt-submenu.open { max-height: 300px; }
    .kt-submenu .kt-nav-link { padding-left: 44px; font-size: 12.5px; color: #787896; }
    .kt-submenu .kt-nav-link:hover { color: #b5b5c3; }
    .kt-submenu .kt-nav-link.active { color: #818cf8; background: transparent; }

    /* ========== HEADER ========== */
    .kt-header {
        background: #fff;
        border-bottom: 1px solid #f1f1f4;
        height: 70px;
        position: sticky;
        top: 0;
        z-index: 30;
    }

    /* ========== CARDS ========== */
    .kt-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #f1f1f4;
        transition: all 0.2s ease;
    }
    .kt-card:hover { box-shadow: 0 1px 15px rgba(0,0,0,0.04); }

    /* Stat icon circles */
    .kt-stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    .kt-stat-icon.primary { background: linear-gradient(135deg, #6366f1, #818cf8); }
    .kt-stat-icon.success { background: linear-gradient(135deg, #10b981, #34d399); }
    .kt-stat-icon.warning { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .kt-stat-icon.danger { background: linear-gradient(135deg, #ef4444, #f87171); }
    .kt-stat-icon.info { background: linear-gradient(135deg, #06b6d4, #22d3ee); }
    .kt-stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #a78bfa); }

    /* ========== BADGES ========== */
    .kt-badge { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; }
    .kt-badge-primary { background: #eef2ff; color: #6366f1; }
    .kt-badge-success { background: #ecfdf5; color: #10b981; }
    .kt-badge-warning { background: #fffbeb; color: #f59e0b; }
    .kt-badge-danger { background: #fef2f2; color: #ef4444; }
    .kt-badge-info { background: #ecfeff; color: #06b6d4; }
    .kt-badge-secondary { background: #f3f4f6; color: #6b7280; }

    /* ========== TABLE ========== */
    .kt-table { width: 100%; }
    .kt-table thead th {
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #9ca3af;
        border-bottom: 1px solid #f1f1f4;
        text-align: left;
    }
    .kt-table tbody td {
        padding: 12px 16px;
        font-size: 13px;
        border-bottom: 1px solid #f8f8fa;
        vertical-align: middle;
    }
    .kt-table tbody tr:last-child td { border-bottom: none; }
    .kt-table tbody tr:hover { background: #fafbfc; }

    /* ========== BUTTONS ========== */
    .btn-admin { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; transition: all 0.2s; cursor: pointer; border: none; }
    .btn-admin-primary { background: #6366f1; color: white; }
    .btn-admin-primary:hover { background: #4f46e5; box-shadow: 0 4px 12px rgba(99,102,241,0.3); }
    .btn-admin-success { background: #10b981; color: white; }
    .btn-admin-success:hover { background: #059669; }
    .btn-admin-danger { background: #ef4444; color: white; }
    .btn-admin-danger:hover { background: #dc2626; }
    .btn-admin-warning { background: #f59e0b; color: white; }
    .btn-admin-warning:hover { background: #d97706; }
    .btn-admin-outline { background: white; color: #374151; border: 1px solid #e5e7eb; }
    .btn-admin-outline:hover { background: #f9fafb; border-color: #d1d5db; }
    .btn-admin-sm { padding: 5px 12px; font-size: 12px; }

    /* ========== FORM INPUTS ========== */
    .admin-input {
        width: 100%;
        padding: 8px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.2s;
        outline: none;
        background: #fff;
    }
    .admin-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    .admin-label { display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }

    /* ========== MOBILE OVERLAY ========== */
    .kt-sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 40; backdrop-filter: blur(2px); }
    .kt-sidebar-overlay.active { display: block; }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 1024px) {
        .kt-sidebar { left: -264px; width: 264px !important; min-width: 264px !important; }
        .kt-sidebar.mobile-open { left: 0; }
        .kt-main { margin-left: 0 !important; }
        #headerSidebarToggle { display: none; }
    }

    /* ========== TOOLTIP ========== */
    .kt-tooltip { position: relative; }
    .kt-tooltip::after {
        content: attr(data-tooltip);
        visibility: hidden; position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%);
        background: #1e1e2d; color: white; padding: 4px 10px; border-radius: 6px; font-size: 11px;
        white-space: nowrap; margin-bottom: 6px; opacity: 0; transition: opacity 0.2s; pointer-events: none;
    }
    .kt-tooltip:hover::after { visibility: visible; opacity: 1; }

    /* Collapsed sidebar tooltip for nav items */
    .kt-sidebar.collapsed .kt-nav-link { position: relative; }
    .kt-sidebar.collapsed .kt-nav-link::after {
        content: attr(data-tooltip);
        position: absolute;
        left: calc(100% + 14px);
        top: 50%;
        transform: translateY(-50%);
        background: #1e1e2d;
        color: #fff;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 12px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.15s, visibility 0.15s;
        pointer-events: none;
        z-index: 100;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .kt-sidebar.collapsed .kt-nav-link:hover::after { opacity: 1; visibility: visible; }

    /* ========== ANIMATIONS ========== */
    @keyframes kt-fade-in { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .kt-fade-in { animation: kt-fade-in 0.3s ease forwards; }

    /* ========== DATATABLES OVERRIDE — Match admin design ========== */
    /* Wrapper layout */
    .dataTables_wrapper { font-family: 'Inter', sans-serif; font-size: 13px; color: #374151; }
    /* Top controls (search + length) */
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length { padding: 14px 18px 0; }
    .dataTables_wrapper .dataTables_filter { text-align: right; }
    .dataTables_wrapper .dataTables_filter label,
    .dataTables_wrapper .dataTables_length label { font-size: 12px; color: #6b7280; font-weight: 500; }
    .dataTables_wrapper .dataTables_filter input {
        width: 220px; padding: 7px 12px; border: 1px solid #e5e7eb; border-radius: 8px;
        font-size: 13px; outline: none; margin-left: 6px; transition: all 0.2s; background: #f9fafb; }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); background: #fff; }
    .dataTables_wrapper .dataTables_length select {
        padding: 6px 28px 6px 10px; border: 1px solid #e5e7eb; border-radius: 8px;
        font-size: 13px; outline: none; margin: 0 4px; background: #f9fafb; color: #374151; cursor: pointer; }
    .dataTables_wrapper .dataTables_length select:focus { border-color: #6366f1; }
    /* Table itself */
    .dataTables_wrapper table.dataTable { width: 100% !important; border-collapse: collapse; margin: 0 !important; }
    .dataTables_wrapper table.dataTable thead th {
        padding: 12px 16px; font-size: 11px; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.06em; color: #9ca3af; border-bottom: 1px solid #f1f1f4;
        border-top: none; background: #fff; white-space: nowrap; }
    .dataTables_wrapper table.dataTable thead th.dt-orderable-asc,
    .dataTables_wrapper table.dataTable thead th.dt-orderable-desc { outline: none; cursor: pointer; }
    .dataTables_wrapper table.dataTable tbody td {
        padding: 12px 16px; font-size: 13px; border-bottom: 1px solid #f8f8fa;
        vertical-align: middle; border-top: none; }
    .dataTables_wrapper table.dataTable tbody tr:last-child td { border-bottom: none; }
    .dataTables_wrapper table.dataTable tbody tr:hover > td { background: #fafbfc; }
    /* Bottom controls (info + pagination) */
    .dataTables_wrapper .dataTables_info {
        font-size: 12px; color: #9ca3af; padding: 12px 18px; float: left; }
    .dataTables_wrapper .dataTables_paginate { padding: 8px 18px; float: right; }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 5px 10px; border-radius: 6px; font-size: 12px; font-weight: 500;
        border: none !important; box-shadow: none !important; margin: 0 1px; cursor: pointer;
        color: #374151 !important; background: transparent !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
        background: #f3f4f6 !important; color: #1f2937 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #6366f1 !important; color: white !important; border-radius: 6px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover { color: #d1d5db !important; cursor: not-allowed; }
    /* Processing overlay */
    .dataTables_processing {
        background: rgba(255,255,255,0.95) !important; border: none !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06) !important; padding: 12px 24px !important;
        border-radius: 10px !important; font-size: 13px !important; color: #6366f1 !important;
        font-weight: 500 !important; }
    /* Responsive extension — expand control */
    table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control::before,
    table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control::before {
        background-color: #6366f1; border: none; box-shadow: none; }
    /* Responsive detail row */
    table.dataTable.dtr-inline.collapsed > tbody > tr.child,
    table.dataTable > tbody > tr.child td.child { background: #f9fafb !important; }
    table.dataTable > tbody > tr.child td ul.dtr-details { display: block; width: 100%; }
    table.dataTable > tbody > tr.child td ul.dtr-details > li { border-bottom: 1px solid #f1f1f4; padding: 8px 0; }
    table.dataTable > tbody > tr.child span.dtr-title { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #9ca3af; min-width: 100px; display: inline-block; }
    /* Clearfix for bottom bar */
    .dataTables_wrapper::after { content: ''; display: table; clear: both; }

    /* ========== BACKWARD COMPAT (admin- prefixed classes existing pages use) ========== */
    .admin-card { background: #fff; border-radius: 12px; border: 1px solid #f1f1f4; transition: all 0.2s ease; }
    .admin-card:hover { box-shadow: 0 1px 15px rgba(0,0,0,0.04); }
    .admin-table { width: 100%; }
    .admin-table thead th { padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; border-bottom: 1px solid #f1f1f4; text-align: left; }
    .admin-table tbody td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid #f8f8fa; vertical-align: middle; }
    .admin-table tbody tr:last-child td { border-bottom: none; }
    .admin-table tbody tr:hover { background: #fafbfc; }
    .badge { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; }
    .badge-primary { background: #eef2ff; color: #6366f1; }
    .badge-success { background: #ecfdf5; color: #10b981; }
    .badge-warning { background: #fffbeb; color: #f59e0b; }
    .badge-danger { background: #fef2f2; color: #ef4444; }
    .badge-info { background: #ecfeff; color: #06b6d4; }
    .badge-secondary { background: #f3f4f6; color: #6b7280; }
    .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    .stat-icon.primary { background: linear-gradient(135deg, #6366f1, #818cf8); }
    .stat-icon.success { background: linear-gradient(135deg, #10b981, #34d399); }
    .stat-icon.warning { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .stat-icon.danger { background: linear-gradient(135deg, #ef4444, #f87171); }
    .stat-icon.info { background: linear-gradient(135deg, #06b6d4, #22d3ee); }
    .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #a78bfa); }
    .tooltip-wrapper { position: relative; }
    .tooltip-wrapper .tooltip-text { visibility: hidden; position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); background: #1e1e2d; color: white; padding: 4px 10px; border-radius: 6px; font-size: 11px; white-space: nowrap; margin-bottom: 6px; opacity: 0; transition: opacity 0.2s; }
    .tooltip-wrapper:hover .tooltip-text { visibility: visible; opacity: 1; }
</style>

@stack('styles')
