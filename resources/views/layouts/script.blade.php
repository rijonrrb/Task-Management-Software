{{-- =====================================================
     script.blade.php  – All JS scripts
     Usage: @include('layouts.script')
     Pages can push extra JS with: @push('scripts') ... @endpush
     ===================================================== --}}

{{-- SweetAlert2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

{{-- Coloris color-picker JS --}}
<script src="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Coloris !== 'undefined') {
            Coloris({
                el: '[data-coloris]',
                themeMode: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                format: 'hex',
                closeButton: true,
                closeLabel: 'Done',
                clearButton: false,
                swatches: ['#6366f1','#8b5cf6','#ec4899','#ef4444','#f97316','#f59e0b','#84cc16','#10b981','#06b6d4','#3b82f6','#64748b','#1e293b']
            });
        }
    });
</script>

{{-- Page-loader & progress-bar --}}
<script>
(function () {
    var loader = document.getElementById('page-loader');
    var bar    = document.getElementById('top-progress-bar');
    var appEl  = document.getElementById('app');

    // Style the loader overlay (it lives outside #app so opacity on #app won't affect it)
    if (loader) {
        var isDark = document.documentElement.classList.contains('dark');
        loader.style.cssText = [
            'position:fixed;top:0;left:0;width:100%;height:100%;z-index:9999;',
            'display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1rem;',
            isDark ? 'background:#0f172a;' : 'background:#f8fafc;'
        ].join('');
        loader.innerHTML = '<div class="loader-spinner"></div>'
            + '<span style="font-size:.75rem;color:#94a3b8;font-weight:500;letter-spacing:.05em;" class="animate-pulse">Loading...</span>';
    }

    // Progress bar animation while page loads
    var progress = 0;
    var interval = setInterval(function () {
        progress += Math.random() * 15 + 5;
        if (progress > 90) progress = 90;
        if (bar) bar.style.width = progress + '%';
    }, 150);

    window.addEventListener('load', function () {
        clearInterval(interval);
        if (bar) bar.style.width = '100%';
        setTimeout(function () {
            // Fade out loader
            if (loader) {
                loader.style.transition = 'opacity .3s ease';
                loader.style.opacity = '0';
                setTimeout(function () { loader.style.display = 'none'; }, 300);
            }
            if (bar) bar.style.opacity = '0';
            // Reveal app
            if (appEl) appEl.style.opacity = '1';
            setTimeout(function () { if (bar) bar.style.display = 'none'; }, 400);
        }, 250);
    });

    // Show progress bar on navigation
    document.addEventListener('click', function (e) {
        var link = e.target.closest('a[href]');
        if (link && !link.target && !e.ctrlKey && !e.metaKey) {
            var href = link.getAttribute('href') || '';
            if (!href.startsWith('#') && !href.startsWith('javascript')) {
                if (bar) { bar.style.display = 'block'; bar.style.opacity = '1'; bar.style.width = '0%'; }
                var p = 0;
                var ni = setInterval(function () {
                    p += Math.random() * 20 + 10;
                    if (p > 85) { p = 85; clearInterval(ni); }
                    if (bar) bar.style.width = p + '%';
                }, 100);
            }
        }
    });

    document.addEventListener('submit', function () {
        if (bar) { bar.style.display = 'block'; bar.style.opacity = '1'; bar.style.width = '30%'; }
    });
})();
</script>

{{-- Theme toggle --}}
<script>
var _themeDropdownOpen = false;

function setTheme(mode) {
    console.log('[Theme] setTheme called with:', mode);
    document.documentElement.classList.add('theme-transitioning');
    localStorage.setItem('theme', mode);
    var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    document.documentElement.classList.toggle('dark', mode === 'dark' || (mode === 'system' && prefersDark));
    _updateThemeChecks();
    setTimeout(function () { document.documentElement.classList.remove('theme-transitioning'); }, 300);
    _closeThemeDropdown();
}

function _updateThemeChecks() {
    var current = localStorage.getItem('theme') || 'system';
    ['light', 'dark', 'system'].forEach(function (t) {
        var el = document.getElementById('theme-check-' + t);
        if (el) el.classList.toggle('hidden', t !== current);
    });
}

function _closeThemeDropdown() {
    _themeDropdownOpen = false;
    var dd = document.getElementById('theme-dropdown');
    if (dd) dd.classList.add('hidden');
}

document.addEventListener('mousedown', function (e) {
    var w = document.getElementById('theme-toggle-wrapper');
    if (w && !w.contains(e.target)) _closeThemeDropdown();
});

var _btn = document.getElementById('theme-toggle-btn');
if (_btn) {
    _btn.addEventListener('click', function (e) {
        e.stopPropagation();
        console.log('[Theme] theme-toggle-btn clicked');
        var dd = document.getElementById('theme-dropdown');
        if (!dd) {
            console.log('[Theme] theme-dropdown not found');
            return;
        }
        _themeDropdownOpen = !_themeDropdownOpen;
        console.log('[Theme] dropdown open state now:', _themeDropdownOpen);
        dd.classList.toggle('hidden', !_themeDropdownOpen);
        if (_themeDropdownOpen) _updateThemeChecks();
    });
}


document.addEventListener('click', function (e) {
    var delegatedBtn = e.target.closest && e.target.closest('#theme-toggle-btn');
    if (!delegatedBtn) return;
    console.log('[Theme][Delegate] theme-toggle-btn clicked');
    var dd = document.getElementById('theme-dropdown');
    if (!dd) {
        console.log('[Theme][Delegate] theme-dropdown not found');
        return;
    }
    _themeDropdownOpen = !_themeDropdownOpen;
    console.log('[Theme][Delegate] dropdown open state now:', _themeDropdownOpen);
    dd.classList.toggle('hidden', !_themeDropdownOpen);
    if (_themeDropdownOpen) _updateThemeChecks();
    e.stopPropagation();
});

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
    if (!localStorage.getItem('theme') || localStorage.getItem('theme') === 'system') {
        document.documentElement.classList.toggle('dark', e.matches);
    }
});

_updateThemeChecks();

// Tasks dropdown
var _tasksDropdownOpen = false;

function _closeTasksDropdown() {
    _tasksDropdownOpen = false;
    var menu = document.getElementById('tasks-dropdown-menu');
    if (menu) menu.classList.add('hidden');
    var chevron = document.getElementById('tasks-chevron');
    if (chevron) chevron.style.transform = '';
}

document.addEventListener('mousedown', function (e) {
    var w = document.getElementById('tasks-dropdown-wrapper');
    if (w && !w.contains(e.target)) _closeTasksDropdown();
});

var _tasksBtn = document.getElementById('tasks-dropdown-btn');
if (_tasksBtn) {
    _tasksBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        var menu = document.getElementById('tasks-dropdown-menu');
        if (!menu) return;
        _tasksDropdownOpen = !_tasksDropdownOpen;
        menu.classList.toggle('hidden', !_tasksDropdownOpen);
        var chevron = document.getElementById('tasks-chevron');
        if (chevron) chevron.style.transform = _tasksDropdownOpen ? 'rotate(180deg)' : '';
    });
}

document.addEventListener('click', function (e) {
    var delegatedBtn = e.target.closest && e.target.closest('#tasks-dropdown-btn');
    if (!delegatedBtn) return;
    var menu = document.getElementById('tasks-dropdown-menu');
    if (!menu) return;
    _tasksDropdownOpen = !_tasksDropdownOpen;
    menu.classList.toggle('hidden', !_tasksDropdownOpen);
    var chevron = document.getElementById('tasks-chevron');
    if (chevron) chevron.style.transform = _tasksDropdownOpen ? 'rotate(180deg)' : '';
    e.stopPropagation();
});
</script>

{{-- Page-specific scripts injected by child views via @push('scripts') --}}
@stack('scripts')
