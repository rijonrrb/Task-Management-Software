<?php

namespace App\Services;

use App\Models\AiLog;
use App\Models\AiPrompt;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->apiKey = SiteSetting::get('openai_api_key', '');
    }

    /**
     * Send a chat completion request to OpenAI.
     *
     * @param  string  $systemPrompt
     * @param  string  $userPrompt
     * @param  string  $model
     * @param  int     $maxTokens
     * @param  float   $temperature
     * @param  string  $service       For logging purposes
     * @param  string|null $promptSlug For logging purposes
     * @return array{success: bool, content: string|null, usage: array|null, error: string|null}
     */
    public function chatCompletion(
        string  $systemPrompt,
        string  $userPrompt,
        string  $model = 'gpt-4o-mini',
        int     $maxTokens = 4000,
        float   $temperature = 0.7,
        string  $service = 'general',
        ?string $promptSlug = null,
    ): array {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'content' => null,
                'usage'   => null,
                'error'   => 'OpenAI API key is not configured. Please add it in Site Settings.',
            ];
        }

        $startTime = microtime(true);

        // Create pending log entry
        $log = AiLog::create([
            'user_id'       => Auth::id(),
            'prompt_slug'   => $promptSlug,
            'service'       => $service,
            'model'         => $model,
            'system_prompt' => $systemPrompt,
            'user_prompt'   => $userPrompt,
            'status'        => 'pending',
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(120)->post("{$this->baseUrl}/chat/completions", [
                'model'       => $model,
                'messages'    => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'max_tokens'  => $maxTokens,
                'temperature' => $temperature,
            ]);

            $durationMs = (int) ((microtime(true) - $startTime) * 1000);

            if ($response->failed()) {
                $errorBody = $response->json('error.message', $response->body());
                $log->update([
                    'status'        => 'failed',
                    'error_message' => $errorBody,
                    'duration_ms'   => $durationMs,
                ]);

                Log::error('OpenAI API error', ['status' => $response->status(), 'error' => $errorBody]);

                return [
                    'success' => false,
                    'content' => null,
                    'usage'   => null,
                    'error'   => "OpenAI API error: {$errorBody}",
                ];
            }

            $data    = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;
            $usage   = $data['usage'] ?? null;

            // Calculate estimated cost
            $cost = $this->estimateCost($model, $usage['prompt_tokens'] ?? 0, $usage['completion_tokens'] ?? 0);

            $log->update([
                'status'            => 'success',
                'response'          => $content,
                'prompt_tokens'     => $usage['prompt_tokens'] ?? null,
                'completion_tokens' => $usage['completion_tokens'] ?? null,
                'total_tokens'      => $usage['total_tokens'] ?? null,
                'cost'              => $cost,
                'duration_ms'       => $durationMs,
            ]);

            return [
                'success' => true,
                'content' => $content,
                'usage'   => $usage,
                'error'   => null,
            ];
        } catch (\Exception $e) {
            $durationMs = (int) ((microtime(true) - $startTime) * 1000);

            $log->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
                'duration_ms'   => $durationMs,
            ]);

            Log::error('OpenAI API exception', ['message' => $e->getMessage()]);

            return [
                'success' => false,
                'content' => null,
                'usage'   => null,
                'error'   => 'Failed to connect to OpenAI: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Generate a career path using an AI prompt template.
     */
    public function generateFromPrompt(AiPrompt $prompt, array $variables): array
    {
        $userPrompt = $prompt->buildUserPrompt($variables);

        return $this->chatCompletion(
            systemPrompt: $prompt->system_prompt,
            userPrompt:   $userPrompt,
            model:        $prompt->model,
            maxTokens:    $prompt->max_tokens,
            temperature:  $prompt->temperature,
            service:      $prompt->service,
            promptSlug:   $prompt->slug,
        );
    }

    /**
     * Parse JSON from the AI response content.
     */
    public function parseJson(string $content): ?array
    {
        // Strip markdown code fences if present
        $content = preg_replace('/^```(?:json)?\s*/m', '', $content);
        $content = preg_replace('/\s*```$/m', '', $content);
        $content = trim($content);

        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('Failed to parse AI JSON response', [
                'error'   => json_last_error_msg(),
                'content' => substr($content, 0, 500),
            ]);
            return null;
        }

        return $decoded;
    }

    /**
     * Estimate API cost based on model and token usage.
     */
    protected function estimateCost(string $model, int $promptTokens, int $completionTokens): float
    {
        // Approximate pricing per 1M tokens (as of 2024)
        $pricing = [
            'gpt-4o'      => ['input' => 2.50, 'output' => 10.00],
            'gpt-4o-mini' => ['input' => 0.15, 'output' => 0.60],
            'gpt-4-turbo' => ['input' => 10.00, 'output' => 30.00],
            'gpt-3.5-turbo' => ['input' => 0.50, 'output' => 1.50],
        ];

        $rates = $pricing[$model] ?? $pricing['gpt-4o-mini'];

        return ($promptTokens * $rates['input'] / 1_000_000) +
               ($completionTokens * $rates['output'] / 1_000_000);
    }
}
