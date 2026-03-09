{{-- Admin Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

{{-- jQuery + DataTables 1.x + Responsive --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
// Setup CSRF token for all jQuery AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
});
// ========== Metronic-style Sidebar Controller ==========
const KTSidebar = {
    sidebar: null,
    overlay: null,
    body: null,
    STORAGE_KEY: 'kt_sidebar_collapsed',

    init() {
        this.sidebar = document.getElementById('adminSidebar');
        this.overlay = document.getElementById('sidebarOverlay');
        this.body = document.body;

        // Restore collapsed state on desktop
        if (window.innerWidth >= 1024) {
            const collapsed = localStorage.getItem(this.STORAGE_KEY) === 'true';
            if (collapsed) {
                this.sidebar.classList.add('collapsed');
                this.body.classList.add('sidebar-collapsed');
            }
        }
    },

    toggle() {
        if (window.innerWidth < 1024) return;
        const isCollapsed = this.sidebar.classList.toggle('collapsed');
        this.body.classList.toggle('sidebar-collapsed', isCollapsed);
        localStorage.setItem(this.STORAGE_KEY, isCollapsed);
    },

    open() {
        if (window.innerWidth >= 1024) return;
        this.sidebar.classList.add('mobile-open');
        this.overlay.classList.add('active');
    },

    close() {
        if (window.innerWidth >= 1024) return;
        this.sidebar.classList.remove('mobile-open');
        this.overlay.classList.remove('active');
    }
};

document.addEventListener('DOMContentLoaded', function() {
    KTSidebar.init();
});

// Close sidebar on resize to desktop
window.addEventListener('resize', function() {
    if (window.innerWidth >= 1024) {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar) sidebar.classList.remove('mobile-open');
        if (overlay) overlay.classList.remove('active');
    }
});

// Close user dropdown when clicking outside
document.addEventListener('click', function(e) {
    document.querySelectorAll('.kt-user-dropdown').forEach(function(dd) {
        if (!dd.parentElement.contains(e.target)) {
            dd.classList.add('hidden');
        }
    });
});

// Confirm delete helper
function confirmDelete(formId, message) {
    message = message || 'Are you sure you want to delete this?';
    Swal.fire({
        title: 'Confirm Delete',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then(function(result) {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

// Confirm action helper
function confirmAction(formId, title, message, confirmText) {
    confirmText = confirmText || 'Yes, proceed';
    Swal.fire({
        title: title,
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6366f1',
        cancelButtonColor: '#6b7280',
        confirmButtonText: confirmText,
        cancelButtonText: 'Cancel'
    }).then(function(result) {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

// Backward compat
function toggleSidebar() { KTSidebar.open(); }
</script>

{{-- SweetAlert Flash Messages --}}
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: @js(session('success')),
        timer: 3500,
        timerProgressBar: true,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
    });
});
</script>
@endif

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: @js(session('error')),
        showConfirmButton: true,
        confirmButtonColor: '#6366f1',
    });
});
</script>
@endif

@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function() {
    var errorList = {!! json_encode($errors->all()) !!};
    var html = '<ul style="text-align:left;margin:0;padding-left:1.2em;">';
    errorList.forEach(function(msg) { html += '<li style="margin-bottom:4px;">' + msg + '</li>'; });
    html += '</ul>';
    Swal.fire({
        icon: 'warning',
        title: 'Please fix the following:',
        html: html,
        showConfirmButton: true,
        confirmButtonColor: '#6366f1',
    });
});
</script>
@endif

@stack('scripts')
