<?php

namespace App\Services\Project;

use Illuminate\Support\Facades\Http;

class AiDocumentProcessor
{
    public function process(string $documentText): array
    {
        $prompt = file_get_contents(
            base_path('ai-prompts/document_extraction.txt')
        );

        $documentText = preg_replace('/\s+/', ' ', $documentText);
        $chunks = str_split($documentText, 8000);

        $results = [];

        foreach ($chunks as $chunk) {
            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt . "\n\nDOCUMENT CHUNK:\n" . $chunk
                        ]
                    ],
                    'temperature' => 0
                ]);

            if (!$response->successful()) {
                continue;
            }

            $content = $response->json('choices.0.message.content');
            $clean = trim(preg_replace('/```json|```/', '', $content));
            $decoded = json_decode($clean, true);

            if (is_array($decoded)) {
                $results[] = $decoded;
            }
        }

        return $this->mergeResults($results);
    }

    private function mergeResults(array $results): array
    {
        $merged = [];

        foreach ($results as $result) {
            foreach ($result as $key => $value) {
                if (empty($value)) continue;

                if (is_array($value)) {
                    $merged[$key] = array_merge($merged[$key] ?? [], $value);
                } else {
                    $merged[$key] = $value;
                }
            }
        }

        return $merged;
    }
}
