@extends('admin.layouts.app')

@section('title', 'Edit Prompt – ' . $prompt->name)
@section('page-title', 'Edit Prompt')
@section('page-subtitle', $prompt->name . ' · ' . $prompt->service)

@section('content')
<form action="{{ route('admin.ai.prompts.update', $prompt) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main prompt content (2/3) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- System Prompt --}}
            <div class="admin-card">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">System Prompt</h3>
                    <p class="text-xs text-gray-400 mt-1">Defines the AI's role, rules, and output format.</p>
                </div>
                <div class="p-5">
                    <textarea
                        name="system_prompt"
                        class="admin-input font-mono text-xs leading-relaxed"
                        rows="20"
                        required
                    >{{ old('system_prompt', $prompt->system_prompt) }}</textarea>
                    @error('system_prompt') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- User Prompt Template --}}
            <div class="admin-card">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">User Prompt Template</h3>
                    <p class="text-xs text-gray-400 mt-1">
                        Use <code class="bg-gray-100 px-1 py-0.5 rounded">{variable_name}</code> placeholders for dynamic values.
                    </p>
                </div>
                <div class="p-5">
                    <textarea
                        name="user_prompt_template"
                        class="admin-input font-mono text-xs leading-relaxed"
                        rows="12"
                        required
                    >{{ old('user_prompt_template', $prompt->user_prompt_template) }}</textarea>
                    @error('user_prompt_template') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

        </div>

        {{-- Sidebar settings (1/3) --}}
        <div class="space-y-6">

            {{-- Identity --}}
            <div class="admin-card">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Identity</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="admin-label">Display Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $prompt->name) }}" class="admin-input" required>
                        @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="admin-label">Slug</label>
                        <input type="text" value="{{ $prompt->slug }}" class="admin-input bg-gray-50 text-gray-400 font-mono text-xs" disabled>
                        <p class="text-xs text-gray-400 mt-1">Slug is read-only — used by application code.</p>
                    </div>
                    <div>
                        <label class="admin-label">Service</label>
                        <input type="text" value="{{ $prompt->service }}" class="admin-input bg-gray-50 text-gray-400 font-mono text-xs" disabled>
                    </div>
                </div>
            </div>

            {{-- Model Parameters --}}
            <div class="admin-card">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Model Parameters</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="admin-label">Model <span class="text-red-400">*</span></label>
                        <select name="model" class="admin-input">
                            @php
                                $models = [
                                    'gpt-4o'        => 'GPT-4o',
                                    'gpt-4o-mini'   => 'GPT-4o Mini',
                                    'gpt-4-turbo'   => 'GPT-4 Turbo',
                                    'gpt-4'         => 'GPT-4',
                                    'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
                                    'o1'            => 'o1',
                                    'o1-mini'       => 'o1 Mini',
                                    'o3-mini'       => 'o3 Mini',
                                ];
                                $current = old('model', $prompt->model);
                            @endphp
                            @foreach($models as $value => $label)
                                <option value="{{ $value }}" {{ $current === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('model') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="admin-label">Max Tokens <span class="text-red-400">*</span></label>
                        <input
                            type="number"
                            name="max_tokens"
                            value="{{ old('max_tokens', $prompt->max_tokens) }}"
                            class="admin-input"
                            min="100"
                            max="128000"
                            step="100"
                            required
                        >
                        @error('max_tokens') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="admin-label">Temperature <span class="text-red-400">*</span></label>
                        <input
                            type="number"
                            name="temperature"
                            value="{{ old('temperature', $prompt->temperature) }}"
                            class="admin-input"
                            min="0"
                            max="2"
                            step="0.1"
                            required
                        >
                        <p class="text-xs text-gray-400 mt-1">0 = deterministic · 1 = balanced · 2 = creative</p>
                        @error('temperature') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="admin-card">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Status</h3>
                </div>
                <div class="p-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $prompt->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        >
                        <span class="text-sm text-gray-600">Prompt is active</span>
                    </label>
                    <p class="text-xs text-gray-400 mt-1">Inactive prompts are skipped by all AI services.</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3">
                <button type="submit" class="btn-admin btn-admin-primary flex-1">Save Changes</button>
                <a href="{{ route('admin.ai.prompts.index') }}" class="btn-admin btn-admin-secondary flex-1 text-center">Cancel</a>
            </div>
        </div>

    </div>
</form>
@endsection
