@extends('admin.layouts.app')

@section('title', 'AI Settings')
@section('page-title', 'AI Settings')
@section('page-subtitle', 'Configure OpenAI API credentials and default model parameters')

@section('content')
<form action="{{ route('admin.ai.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- API Credentials --}}
        <div class="admin-card">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">OpenAI API Credentials</h3>
                <p class="text-xs text-gray-400 mt-1">Your secret key is stored encrypted and never displayed in full.</p>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="admin-label">API Key</label>
                    <input
                        type="password"
                        name="openai_api_key"
                        value="{{ old('openai_api_key', $settings['openai_api_key']) }}"
                        class="admin-input font-mono"
                        placeholder="sk-..."
                        autocomplete="new-password"
                    >
                    <p class="text-xs text-gray-400 mt-1">Leave blank to keep the existing key unchanged.</p>
                    @error('openai_api_key') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <p class="text-xs text-gray-400">
                        Get your key at
                        <a href="https://platform.openai.com/api-keys" target="_blank" rel="noopener noreferrer" class="text-indigo-400 hover:underline">
                            platform.openai.com/api-keys
                        </a>
                    </p>
                </div>

                @if($settings['openai_api_key'])
                    <div class="flex items-center gap-2 text-xs text-green-400">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        API key is configured
                    </div>
                @else
                    <div class="flex items-center gap-2 text-xs text-yellow-400">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        No API key configured — AI features will be disabled
                    </div>
                @endif
            </div>
        </div>

        {{-- Model & Token Settings --}}
        <div class="admin-card">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Default Model Parameters</h3>
                <p class="text-xs text-gray-400 mt-1">Used as fallback when a prompt doesn't specify its own model.</p>
            </div>
            <div class="p-5 space-y-4">

                <div>
                    <label class="admin-label">Default Model <span class="text-red-400">*</span></label>
                    <select name="openai_default_model" class="admin-input">
                        @php
                            $models = [
                                'gpt-4o'          => 'GPT-4o (Flagship)',
                                'gpt-4o-mini'     => 'GPT-4o Mini (Fast & Affordable)',
                                'gpt-4-turbo'     => 'GPT-4 Turbo',
                                'gpt-4'           => 'GPT-4',
                                'gpt-3.5-turbo'   => 'GPT-3.5 Turbo (Legacy)',
                                'o1'              => 'o1 (Reasoning)',
                                'o1-mini'         => 'o1 Mini (Reasoning)',
                                'o3-mini'         => 'o3 Mini (Reasoning)',
                            ];
                            $current = old('openai_default_model', $settings['openai_default_model']);
                        @endphp
                        @foreach($models as $value => $label)
                            <option value="{{ $value }}" {{ $current === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('openai_default_model') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="admin-label">Max Tokens <span class="text-red-400">*</span></label>
                    <input
                        type="number"
                        name="openai_max_tokens"
                        value="{{ old('openai_max_tokens', $settings['openai_max_tokens']) }}"
                        class="admin-input"
                        min="100"
                        max="128000"
                        step="100"
                    >
                    <p class="text-xs text-gray-400 mt-1">Maximum tokens per response. GPT-4o supports up to 128,000.</p>
                    @error('openai_max_tokens') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <p class="text-xs text-gray-400 leading-relaxed">
                        Individual prompts can override these defaults. See
                        <a href="{{ route('admin.ai.prompts.index') }}" class="text-indigo-400 hover:underline">AI Prompts</a>
                        to configure per-prompt model and token settings.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-6">
        <button type="submit" class="btn-admin btn-admin-primary">Save AI Settings</button>
    </div>
</form>
@endsection
