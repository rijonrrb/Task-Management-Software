<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiPrompt;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AiController extends Controller
{
    // ── Settings (OpenAI keys, model, tokens) ──

    public function settings()
    {
        $settings = [
            'openai_api_key'       => SiteSetting::get('openai_api_key', ''),
            'openai_default_model' => SiteSetting::get('openai_default_model', 'gpt-4o-mini'),
            'openai_max_tokens'    => SiteSetting::get('openai_max_tokens', '4000'),
        ];

        return view('admin.ai.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'openai_api_key'       => ['nullable', 'string', 'max:500'],
            'openai_default_model' => ['required', 'string', 'max:100'],
            'openai_max_tokens'    => ['required', 'integer', 'min:100', 'max:128000'],
        ]);

        SiteSetting::set('openai_api_key', $validated['openai_api_key'] ?? '', 'ai', 'password');
        SiteSetting::set('openai_default_model', $validated['openai_default_model'], 'ai', 'text');
        SiteSetting::set('openai_max_tokens', (string) $validated['openai_max_tokens'], 'ai', 'number');

        Cache::forget('site_settings');
        Cache::forget('site_settings_ai');

        return back()->with('success', 'AI settings updated successfully.');
    }

    // ── Prompts ──

    public function prompts()
    {
        $prompts = AiPrompt::orderBy('service')->orderBy('name')->get();

        return view('admin.ai.prompts.index', compact('prompts'));
    }

    public function editPrompt(AiPrompt $prompt)
    {
        return view('admin.ai.prompts.edit', compact('prompt'));
    }

    public function updatePrompt(Request $request, AiPrompt $prompt)
    {
        $validated = $request->validate([
            'name'                 => ['required', 'string', 'max:255'],
            'model'                => ['required', 'string', 'max:100'],
            'max_tokens'           => ['required', 'integer', 'min:100', 'max:128000'],
            'temperature'          => ['required', 'numeric', 'min:0', 'max:2'],
            'system_prompt'        => ['required', 'string'],
            'user_prompt_template' => ['required', 'string'],
            'is_active'            => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $prompt->update($validated);

        return back()->with('success', 'Prompt "' . $prompt->name . '" updated successfully.');
    }

    public function togglePrompt(AiPrompt $prompt)
    {
        $prompt->update(['is_active' => !$prompt->is_active]);

        $state = $prompt->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Prompt \"{$prompt->name}\" {$state}.");
    }
}
