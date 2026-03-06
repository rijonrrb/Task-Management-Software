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
new DataTable('#usersTable', {
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: '{{ route('admin.users.index') }}',
    columns: [
        { data: 'name_html',                 name: 'name',                   orderable: true,  searchable: true },
        { data: 'tasks_count',               name: 'tasks_count',            responsivePriority: 3 },
        { data: 'support_tickets_count',     name: 'support_tickets_count',  responsivePriority: 4 },
        { data: 'last_login_at',             name: 'last_login_at',          responsivePriority: 3 },
        { data: 'status_html',               name: 'is_banned',              orderable: true,  searchable: false, responsivePriority: 2 },
        { data: 'actions',                   name: 'actions',                orderable: false, searchable: false, responsivePriority: 1 },
    ],
    language: {
        processing: '<span class="text-indigo-600">Loading users…</span>',
        search:     'Search:',
        emptyTable: '<div class="py-10 text-center text-gray-400">No users found</div>',
    },
    pageLength: 15,
    order: [[0, 'asc']],
});
</script>
@endpush
@endsection
