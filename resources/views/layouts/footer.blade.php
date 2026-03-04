{{-- =====================================================
     footer.blade.php
     Usage: @include('layouts.footer')
     ===================================================== --}}
<footer class="border-t border-slate-200 dark:border-slate-800 py-6 mt-auto bg-white/50 dark:bg-slate-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 gradient-primary rounded-lg flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs text-slate-500 dark:text-slate-500 font-medium">TaskFlow</span>
                <span class="text-xs text-slate-300 dark:text-slate-700">&bull;</span>
                <span class="text-xs text-slate-400 dark:text-slate-600">Built with Laravel {{ app()->version() }}</span>
            </div>
            <div class="flex items-center gap-4 text-xs text-slate-400 dark:text-slate-600">
                <span>Redis</span>
                <span>&bull;</span>
                <span>Pusher</span>
                <span>&bull;</span>
                <span>Vue 3</span>
            </div>
        </div>
    </div>
</footer>
