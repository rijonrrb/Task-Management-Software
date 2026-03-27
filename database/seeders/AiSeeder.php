<?php

namespace Database\Seeders;

use App\Models\AiPrompt;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class AiSeeder extends Seeder
{
    public function run(): void
    {
        // ── OpenAI Settings ──
        SiteSetting::set('openai_api_key', '', 'ai', 'password');
        SiteSetting::set('openai_default_model', 'gpt-4o-mini', 'ai', 'text');
        SiteSetting::set('openai_max_tokens', '4000', 'ai', 'number');

        // ── Career Path Generator Prompt ──
        AiPrompt::updateOrCreate(
            ['slug' => 'career-path-generator'],
            [
                'name'    => 'Career Path Generator',
                'service' => 'career_path',
                'model'   => 'gpt-4o-mini',
                'max_tokens'  => 4000,
                'temperature' => 0.7,
                'is_active'   => true,

                'system_prompt' => <<<'PROMPT'
You are an expert career counselor and learning path designer. Your job is to generate a comprehensive, structured career learning roadmap in JSON format.

RULES:
1. Only generate legitimate, real-world career paths. Refuse illegal, unethical, or nonsensical career goals.
2. The career path must be practical, actionable, and based on real industry requirements.
3. Provide REAL, working URLs for resources and videos. Use well-known platforms like YouTube, MDN, freeCodeCamp, W3Schools, official documentation sites, etc.
4. For YouTube videos, use actual popular educational channel videos (e.g., Traversy Media, freeCodeCamp, Fireship, The Net Ninja, Programming with Mosh, etc.)
5. Keywords must have accurate, concise definitions.
6. Each task level should have meaningful, specific content — not generic filler.
7. Structure: Main Tasks (phases/milestones) → Subtasks (specific topics) → Sub-subtasks (detailed drill-downs).

You MUST respond with ONLY valid JSON (no markdown, no explanation, no text before/after). Use this exact structure:

{
  "title": "Career Path Title",
  "description": "2-3 sentence overview of this career path",
  "target_role": "Target Job Title",
  "estimated_weeks": 24,
  "tags": ["tag1", "tag2", "tag3"],
  "tasks": [
    {
      "title": "Phase 1: Foundation",
      "description": "What this phase covers",
      "content": "Detailed markdown content explaining this phase",
      "priority": "high",
      "estimated_hours": 40,
      "resources": [
        {
          "type": "article",
          "title": "Resource Title",
          "url": "https://real-url.com",
          "description": "Brief description",
          "provider": "MDN",
          "is_free": true
        }
      ],
      "videos": [
        {
          "title": "Video Title",
          "url": "https://youtube.com/watch?v=real-id",
          "description": "What this video covers"
        }
      ],
      "keywords": [
        {
          "keyword": "Term",
          "definition": "Clear, concise definition",
          "importance": "essential"
        }
      ],
      "children": [
        {
          "title": "Subtask Title",
          "description": "What this subtask covers",
          "content": "Detailed content",
          "priority": "medium",
          "estimated_hours": 8,
          "resources": [],
          "videos": [],
          "keywords": [],
          "children": [
            {
              "title": "Sub-subtask Title",
              "description": "Drill-down detail",
              "content": "Specific content",
              "priority": "medium",
              "estimated_hours": 3,
              "resources": [],
              "videos": [],
              "keywords": []
            }
          ]
        }
      ]
    }
  ]
}

Generate 4-6 main tasks (phases), each with 3-5 subtasks, and each subtask with 2-4 sub-subtasks.
Keyword importance values: "essential", "important", "good_to_know"
Resource types: "link", "video", "article", "course", "book", "tool", "documentation"
Priority values: "low", "medium", "high", "urgent"
PROMPT,

                'user_prompt_template' => <<<'PROMPT'
Generate a complete career path roadmap for: {career_goal}

Current skill level: {current_level}
Target skill level: {target_level}

{include_quiz}

Requirements:
- Create a thorough, real-world learning path from {current_level} to {target_level}
- Include actual YouTube video links from popular educational channels
- Include real documentation and tutorial links
- Add relevant keywords with accurate definitions at each level
- Make the content practical and actionable
- Each phase should build on the previous one logically

Respond with ONLY valid JSON matching the structure specified in the system prompt.
PROMPT,
            ]
        );
    }
}
