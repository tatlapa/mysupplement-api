<?php

namespace App\Services;

use OpenAI;

class AdvicerService
{
    private array $availableSupplements = [
        "MACA", "TRYPTOPHAN USP32", "TYROSINE", "ALA", "Reg Ginseng",
        "L-Theanine", "Green Tea extract", "NAC", "DHM", "5HTP 99%",
        "B complex", "Melatonin", "L-Arginine USP43", "Horny Goat Weed Icarins 20%",
        "Creatine monohydrate 99%", "Fish Collagen", "Tongkat Ali Extract eurycomanone 3%",
        "L-Carnitine 50", "VC Liposomes", "Ashwagandha (withanolides 10%)",
        "S-Adenosyl-L-methionine Disulfate Tosylate", "Salidroside 3%",
        "St john's Wort (hypericin 0.3%)", "L-Dopa", "Parsley leaf extract",
        "Clove", "NMN", "NR", "NAD+", "Psyllium husk", "tryptophan",
        "Tyrosine", "ALA",
    ];

    public function getRecommendations(array $data): array
    {
        $prompt = $this->buildPrompt($data);

        $client = OpenAI::factory()
            ->withApiKey(env('GROQ_API_KEY'))
            ->withBaseUri('https://api.groq.com/openai/v1')
            ->make();

        $response = $client->chat()->create([
            'model'    => 'llama-3.3-70b-versatile',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a knowledgeable supplement advisor. Provide evidence-based recommendations tailored to the user\'s profile. Always respond with valid JSON.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens'  => 2000,
        ]);

        $raw = $response['choices'][0]['message']['content'] ?? null;

        if (!$raw) {
            throw new \RuntimeException('No response from AI');
        }

        return $this->parseJson($raw);
    }

    private function buildPrompt(array $data): string
    {
        $goalsList       = implode(', ', $data['goals']);
        $healthIssues    = !empty($data['healthIssues']) ? implode(', ', $data['healthIssues']) : 'None';
        $supplementsList = implode(', ', $this->availableSupplements);

        return <<<PROMPT
        Based on the following user profile, recommend supplements from this list: {$supplementsList}

        User Profile:
        - Age: {$data['age']}
        - Gender: {$data['gender']}
        - Health Goals: {$goalsList}
        - Health Issues: {$healthIssues}
        - Sleep Quality: {$data['sleepQuality']}
        - Stress Level: {$data['stressLevel']}

        Provide recommendations in a valid JSON format with the following structure:
        {
          "supplements": [
            {
              "name": "Supplement Name",
              "description": "Brief description",
              "benefits": ["benefit 1", "benefit 2"],
              "dosage": "Recommended dosage"
            }
          ],
          "explanation": "Overall explanation of the recommendations"
        }

        Consider interactions, contraindications, and the user's specific needs. Only recommend supplements from the provided list.
        PROMPT;
    }

    private function parseJson(string $raw): array
    {
        $raw = preg_replace('/^```(?:json)?\s*/i', '', trim($raw));
        $raw = preg_replace('/\s*```$/', '', $raw);

        $decoded = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON from AI: ' . $raw);
        }

        return $decoded;
    }
}
