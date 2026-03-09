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
$(function () {
    var activeStatus = '';

    var ticketsTable = $('#ticketsTable').DataTable({
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
            { data: 'ticket_html',   name: 'subject',           orderable: true,  searchable: true,  defaultContent: '' },
            { data: 'user_html',     name: 'user.name',         orderable: false, searchable: false, responsivePriority: 3, defaultContent: '' },
            { data: 'priority_html', name: 'priority',          orderable: true,  searchable: false, responsivePriority: 4, defaultContent: '' },
            { data: 'status_html',   name: 'status',            orderable: true,  searchable: false, responsivePriority: 2, defaultContent: '' },
            { data: 'assigned_html', name: 'assigned_admin_id', orderable: false, searchable: false, responsivePriority: 5, defaultContent: '' },
            { data: 'created_at',    name: 'created_at',        orderable: true,  searchable: false, responsivePriority: 4, defaultContent: '' },
            { data: 'actions',       name: 'actions',           orderable: false, searchable: false, responsivePriority: 1, defaultContent: '' }
        ],
        language: {
            processing: '<span class="text-indigo-600">Loading tickets…</span>',
            emptyTable: '<div class="py-10 text-center text-gray-400"><svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>No tickets found</div>'
        },
        pageLength: 15,
        order: [[5, 'desc']]
    });

    window.filterTickets = function (status) {
        activeStatus = status;
        $('#ticketStatusTabs button').each(function () {
            var s = $(this).attr('data-status');
            $(this).attr('class', 'btn-admin btn-admin-sm ' + (s === status ? 'btn-admin-primary' : 'btn-admin-outline'));
        });
        ticketsTable.ajax.reload();
    };
});
</script>
@endpush
@endsection
