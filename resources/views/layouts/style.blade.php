{{-- =====================================================
     styles.blade.php  – All CSS / font links
     Usage: @include('layouts.style')
     Pages can push extra CSS with: @push('styles') ... @endpush
     ===================================================== --}}
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />

{{-- SweetAlert2 CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"/>

{{-- Coloris color-picker CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css"/>

{{-- Vite-compiled app CSS + JS --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- Inline theme-initializer: must run before paint to avoid flash --}}
<script>
    (function () {
        var s = localStorage.getItem('theme');
        var d = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.classList.toggle('dark', s === 'dark' || (s !== 'light' && d));
    })();
</script>

{{-- Page-specific styles injected by child views via @push('styles') --}}
@stack('styles')
