@extends('admin.layouts.app')

@section('title', 'Manage Users')
@section('page-title', 'Users')
@section('page-subtitle', 'Manage all registered users')

@section('content')
<div class="admin-card">
    <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800">All Users</h3>
    </div>
    <div class="p-2">
        <table id="usersTable" class="admin-table w-full" style="width:100%">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Tasks</th>
                    <th>Tickets</th>
                    <th>Last Login</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route('admin.users.index') }}',
        columns: [
            { data: 'name_html',             name: 'name',                  orderable: true,  searchable: true,  defaultContent: '' },
            { data: 'tasks_count',           name: 'tasks_count',           orderable: true,  searchable: false, responsivePriority: 3, defaultContent: '0' },
            { data: 'support_tickets_count', name: 'support_tickets_count', orderable: true,  searchable: false, responsivePriority: 4, defaultContent: '0' },
            { data: 'last_login_at',         name: 'last_login_at',         orderable: true,  searchable: false, responsivePriority: 3, defaultContent: 'Never' },
            { data: 'status_html',           name: 'is_banned',             orderable: true,  searchable: false, responsivePriority: 2, defaultContent: '' },
            { data: 'actions',               name: 'actions',               orderable: false, searchable: false, responsivePriority: 1, defaultContent: '' }
        ],
        language: {
            processing: '<span class="text-indigo-600">Loading users…</span>',
            emptyTable: '<div class="py-10 text-center text-gray-400"><svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>No users found</div>'
        },
        pageLength: 15,
        order: [[0, 'asc']]
    });
});
</script>
@endpush
@endsection
