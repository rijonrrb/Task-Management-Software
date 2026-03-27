@extends('admin.layouts.app')

@section('title', 'AI Prompts')
@section('page-title', 'AI Prompts')
@section('page-subtitle', 'Manage system and user prompt templates used by AI features')

@section('content')
<div class="admin-card">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">All Prompts</h3>
        <span class="text-xs text-gray-400">{{ $prompts->count() }} prompt{{ $prompts->count() !== 1 ? 's' : '' }}</span>
    </div>

    @if($prompts->isEmpty())
        <div class="p-10 text-center text-gray-400 text-sm">
            No AI prompts found. Run <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">php artisan db:seed --class=AiSeeder</code> to seed defaults.
        </div>
    @else
        <div class="divide-y divide-gray-100">
            @foreach($prompts as $prompt)
                <div class="p-5 flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-medium text-sm text-gray-800">{{ $prompt->name }}</span>
                            <span class="text-[10px] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-500">
                                {{ $prompt->service }}
                            </span>
                            @if($prompt->is_active)
                                <span class="text-[10px] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded bg-green-50 text-green-600">Active</span>
                            @else
                                <span class="text-[10px] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded bg-red-50 text-red-500">Inactive</span>
                            @endif
                        </div>
                        <div class="mt-1 flex items-center gap-3 text-xs text-gray-400">
                            <span>Model: <strong class="text-gray-600">{{ $prompt->model }}</strong></span>
                            <span>Max tokens: <strong class="text-gray-600">{{ number_format($prompt->max_tokens) }}</strong></span>
                            <span>Temp: <strong class="text-gray-600">{{ $prompt->temperature }}</strong></span>
                        </div>
                        <p class="mt-1 text-xs text-gray-400 font-mono truncate">slug: {{ $prompt->slug }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <form action="{{ route('admin.ai.prompts.toggle', $prompt) }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="text-xs px-3 py-1.5 rounded border {{ $prompt->is_active ? 'border-red-200 text-red-500 hover:bg-red-50' : 'border-green-200 text-green-600 hover:bg-green-50' }} transition"
                            >
                                {{ $prompt->is_active ? 'Disable' : 'Enable' }}
                            </button>
                        </form>
                        <a
                            href="{{ route('admin.ai.prompts.edit', $prompt) }}"
                            class="text-xs px-3 py-1.5 rounded border border-indigo-200 text-indigo-500 hover:bg-indigo-50 transition"
                        >
                            Edit
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
