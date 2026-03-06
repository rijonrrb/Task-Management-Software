{{-- Admin Footer - Metronic Style --}}
<footer class="border-t border-gray-100 py-4 px-5 lg:px-7">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-400">
        <span>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.settings.index') }}" class="hover:text-gray-600 transition">Settings</a>
            <span>&bull;</span>
            <span>v1.0</span>
        </div>
    </div>
</footer>
