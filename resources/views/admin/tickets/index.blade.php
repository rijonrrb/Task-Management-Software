@extends('admin.layouts.app')

@section('title', 'Support Tickets')
@section('page-title', 'Support Tickets')
@section('page-subtitle', 'Manage customer support requests')

@section('content')
{{-- Status Tabs --}}
<div class="flex flex-wrap gap-2 mb-5" id="ticketStatusTabs">
    <button class="btn-admin btn-admin-sm btn-admin-primary" data-status="" onclick="filterTickets('')">
        All <span class="ml-1 opacity-70">({{ $statusCounts['all'] }})</span>
    </button>
    <button class="btn-admin btn-admin-sm btn-admin-outline" data-status="open" onclick="filterTickets('open')">
        Open <span class="ml-1 opacity-70">({{ $statusCounts['open'] }})</span>
    </button>
    <button class="btn-admin btn-admin-sm btn-admin-outline" data-status="in_progress" onclick="filterTickets('in_progress')">
        In Progress <span class="ml-1 opacity-70">({{ $statusCounts['in_progress'] }})</span>
    </button>
    <button class="btn-admin btn-admin-sm btn-admin-outline" data-status="resolved" onclick="filterTickets('resolved')">
        Resolved <span class="ml-1 opacity-70">({{ $statusCounts['resolved'] }})</span>
    </button>
    <button class="btn-admin btn-admin-sm btn-admin-outline" data-status="closed" onclick="filterTickets('closed')">
        Closed <span class="ml-1 opacity-70">({{ $statusCounts['closed'] }})</span>
    </button>
</div>

{{-- Tickets Table --}}
<div class="admin-card">
    <div class="p-2">
        <table id="ticketsTable" class="admin-table w-full" style="width:100%">
            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>User</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned To</th>
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
var activeStatus = '';

var ticketsTable = new DataTable('#ticketsTable', {
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
        url: '{{ route('admin.tickets.index') }}',
        data: function (d) {
            d.status = activeStatus;
        }
    },
    columns: [
        { data: 'ticket_html',   name: 'subject',            orderable: true,  searchable: true },
        { data: 'user_html',     name: 'user.name',          orderable: false, searchable: false, responsivePriority: 3 },
        { data: 'priority_html', name: 'priority',           orderable: true,  searchable: false, responsivePriority: 4 },
        { data: 'status_html',   name: 'status',             orderable: true,  searchable: false, responsivePriority: 2 },
        { data: 'assigned_html', name: 'assigned_admin_id',  orderable: false, searchable: false, responsivePriority: 5 },
        { data: 'created_at',    name: 'created_at',         orderable: true,  searchable: false, responsivePriority: 4 },
        { data: 'actions',       name: 'actions',            orderable: false, searchable: false, responsivePriority: 1 },
    ],
    language: {
        processing: '<span class="text-indigo-600">Loading tickets…</span>',
        emptyTable: '<div class="py-10 text-center text-gray-400">No tickets found</div>',
    },
    pageLength: 15,
    order: [[5, 'desc']],
});

function filterTickets(status) {
    activeStatus = status;
    // Update tab active state
    document.querySelectorAll('#ticketStatusTabs button').forEach(function(btn) {
        var s = btn.getAttribute('data-status');
        btn.className = 'btn-admin btn-admin-sm ' + (s === status ? 'btn-admin-primary' : 'btn-admin-outline');
    });
    ticketsTable.ajax.reload();
}
</script>
@endpush
@endsection
