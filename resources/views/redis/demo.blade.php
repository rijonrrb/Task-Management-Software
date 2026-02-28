{{--
╔══════════════════════════════════════════════════════════════╗
║  VIEW: Redis Demo / Playground                               ║
║  Purpose: Interactive page to learn Redis operations         ║
║  Learning: Vue component integration, Redis commands         ║
╚══════════════════════════════════════════════════════════════╝

This page uses the <redis-demo /> Vue component which handles
all the interactive Redis operations via AJAX calls.
--}}
@extends('layouts.app')
@section('title', 'Redis Lab')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-red-900/30 border border-red-800/40 rounded-xl flex items-center justify-center">
                <span class="text-xl">🔴</span>
            </div>
            <div>
                <h1 class="text-xl font-semibold text-white">Redis Lab</h1>
                <p class="text-sm text-gray-500">Interactive playground to learn Redis operations</p>
            </div>
        </div>

        {{-- Info Banner --}}
        <div class="mt-4 bg-[#1c1008] border border-red-900/40 rounded-xl p-4">
            <h3 class="font-semibold text-red-400 mb-2 text-sm">What is Redis?</h3>
            <p class="text-xs text-red-300/70 leading-relaxed">
                Redis is an <strong class="text-red-300">in-memory data store</strong> — think of it as a super-fast dictionary that lives in RAM.
                It's used for <strong class="text-red-300">caching</strong> (avoid slow DB queries), <strong class="text-red-300">sessions</strong> (user login state),
                <strong class="text-red-300">queues</strong> (background jobs), and <strong class="text-red-300">real-time features</strong> (pub/sub).
                Data access is measured in <strong class="text-red-300">microseconds</strong>, not milliseconds!
            </p>
            <div class="mt-3 flex flex-wrap gap-2">
                <span class="px-2.5 py-1 bg-red-900/30 rounded-full text-xs font-medium text-red-400 border border-red-800/40">Strings (SET/GET)</span>
                <span class="px-2.5 py-1 bg-red-900/30 rounded-full text-xs font-medium text-red-400 border border-red-800/40">Lists (Queues)</span>
                <span class="px-2.5 py-1 bg-red-900/30 rounded-full text-xs font-medium text-red-400 border border-red-800/40">Hashes (Objects)</span>
                <span class="px-2.5 py-1 bg-red-900/30 rounded-full text-xs font-medium text-red-400 border border-red-800/40">Counters (INCR)</span>
                <span class="px-2.5 py-1 bg-red-900/30 rounded-full text-xs font-medium text-red-400 border border-red-800/40">TTL (Expiry)</span>
                <span class="px-2.5 py-1 bg-red-900/30 rounded-full text-xs font-medium text-red-400 border border-red-800/40">Cache Pattern</span>
            </div>
        </div>
    </div>

    {{--
    ═══════════ REDIS DEMO VUE COMPONENT ═══════════
    This Vue component handles all the interactive demos.
    It makes AJAX calls to RedisDemoController endpoints.
    --}}
    <redis-demo></redis-demo>
@endsection
