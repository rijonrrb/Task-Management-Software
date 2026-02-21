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
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                <span class="text-2xl">🔴</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Redis Lab</h1>
                <p class="text-gray-500">Interactive playground to learn Redis operations</p>
            </div>
        </div>

        {{-- Info Banner --}}
        <div class="mt-4 bg-gradient-to-r from-red-50 to-orange-50 border border-red-100 rounded-xl p-4">
            <h3 class="font-bold text-red-800 mb-2">📚 What is Redis?</h3>
            <p class="text-sm text-red-700 leading-relaxed">
                Redis is an <strong>in-memory data store</strong> — think of it as a super-fast dictionary that lives in RAM.
                It's used for <strong>caching</strong> (avoid slow DB queries), <strong>sessions</strong> (user login state),
                <strong>queues</strong> (background jobs), and <strong>real-time features</strong> (pub/sub).
                Data access is measured in <strong>microseconds</strong>, not milliseconds!
            </p>
            <div class="mt-3 flex flex-wrap gap-3">
                <span class="px-3 py-1 bg-white rounded-full text-xs font-medium text-red-700 border border-red-200">📝 Strings (SET/GET)</span>
                <span class="px-3 py-1 bg-white rounded-full text-xs font-medium text-red-700 border border-red-200">📋 Lists (Queues)</span>
                <span class="px-3 py-1 bg-white rounded-full text-xs font-medium text-red-700 border border-red-200">🗂️ Hashes (Objects)</span>
                <span class="px-3 py-1 bg-white rounded-full text-xs font-medium text-red-700 border border-red-200">🔢 Counters (INCR)</span>
                <span class="px-3 py-1 bg-white rounded-full text-xs font-medium text-red-700 border border-red-200">⏱️ TTL (Expiry)</span>
                <span class="px-3 py-1 bg-white rounded-full text-xs font-medium text-red-700 border border-red-200">⚡ Cache Pattern</span>
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
