@extends('admin.layouts.app')

@section('title', 'Custom Pages')
@section('page-title', 'Custom Pages')
@section('page-subtitle', 'Manage website pages and content')

@section('content')
<div class="flex items-center justify-end mb-5">
    <a href="{{ route('admin.pages.create') }}" class="btn-admin btn-admin-primary btn-admin-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        New Page
    </a>
</div>

<div class="admin-card">
    <div class="p-2">
        <table id="pagesTable" class="admin-table w-full" style="width:100%">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>In Menu</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
new DataTable('#pagesTable', {
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: '{{ route('admin.pages.index') }}',
    columns: [
        { data: 'title',       name: 'title',       orderable: true,  searchable: true },
        { data: 'slug',        name: 'slug',        orderable: false, searchable: true,  responsivePriority: 4, render: function(d) { return '<span class="text-xs text-gray-400 font-mono">/page/' + d + '</span>'; } },
        { data: 'status_html', name: 'is_published', orderable: true,  searchable: false, responsivePriority: 2 },
        { data: 'menu_html',   name: 'show_in_menu', orderable: false, searchable: false, responsivePriority: 5 },
        { data: 'created_at', name: 'created_at',   orderable: true,  searchable: false, responsivePriority: 3 },
        { data: 'actions',    name: 'actions',      orderable: false, searchable: false, responsivePriority: 1 },
    ],
    language: {
        processing: '<span class="text-indigo-600">Loading pages…</span>',
        emptyTable: '<div class="py-10 text-center text-gray-400"><svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>No pages created yet</div>',
    },
    pageLength: 15,
    order: [[4, 'desc']],
});
</script>
@endpush
@endsection
