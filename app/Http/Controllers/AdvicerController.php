<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenAI;

class AdvicerController extends Controller
{
    public function getSupplementRecommendations(Request $request)
    {
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'age' => 'required|integer|min:1',
            'gender' => 'required|string|in:male,female,other',
            'goals' => 'required|array|min:1',
            'goals.*' => 'string|max:255',
            'healthIssues' => 'nullable|array',
            'healthIssues.*' => 'string|max:255',
            'sleepQuality' => 'required|string|max:100',
            'stressLevel' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Liste des suppléments disponibles
        $availableSupplements = [
            "MACA", "TRYPTOPHAN USP32", "TYROSINE", "ALA", "Reg Ginseng",
            "L-Theanine", "Green Tea extract", "NAC", "DHM", "5HTP 99%",
            "B complex", "Melatonin", "L-Arginine USP43", "Horny Goat Weed Icarins 20%",
            "Creatine monohydrate 99%", "Fish Collagen", "Tongkat Ali Extract eurycomanone 3%",
            "L-Carnitine 50", "VC Liposomes", "Ashwagandha (withanolides 10%)",
            "S-Adenosyl-L-methionine Disulfate Tosylate", "Salidroside 3%",
            "St john's Wort (hypericin 0.3%)", "L-Dopa", "Parsley leaf extract",
            "Clove", "NMN", "NR", "NAD+", "Psyllium husk", "tryptophan",
            "Tyrosine", "ALA"
        ];

        // Construction du prompt
        $goalsList = implode(", ", $request->input('goals'));
        $healthIssuesList = $request->input('healthIssues') ? implode(", ", $request->input('healthIssues')) : 'None';

        $prompt = "Based on the following user profile, recommend supplements from this list: " . implode(", ", $availableSupplements) . "\n\n";
        $prompt .= "User Profile:\n";
        $prompt .= "- Age: " . $request->input('age') . "\n";
        $prompt .= "- Gender: " . $request->input('gender') . "\n";
        $prompt .= "- Health Goals: " . $goalsList . "\n";
        $prompt .= "- Health Issues: " . $healthIssuesList . "\n";
        $prompt .= "- Sleep Quality: " . $request->input('sleepQuality') . "\n";
        $prompt .= "- Stress Level: " . $request->input('stressLevel') . "\n\n";
        $prompt .= "Provide recommendations in a valid JSON format with the following structure:\n";
        $prompt .= "{\n";
        $prompt .= '  "supplements": [' . "\n";
        $prompt .= '    {' . "\n";
        $prompt .= '      "name": "Supplement Name",' . "\n";
        $prompt .= '      "description": "Brief description",' . "\n";
        $prompt .= '      "benefits": ["benefit 1", "benefit 2"],' . "\n";
        $prompt .= '      "dosage": "Recommended dosage"' . "\n";
        $prompt .= "    }\n";
        $prompt .= "  ],\n";
        $prompt .= '  "explanation": "Overall explanation of the recommendations"' . "\n";
        $prompt .= "}\n\n";
        $prompt .= "Consider interactions, contraindications, and the user's specific needs. Only recommend supplements from the provided list.";

        try {
            $client = OpenAI::client(env('OPENAI_API_KEY'));

            $response = $client->chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a knowledgeable supplement advisor. Provide evidence-based recommendations tailored to the user’s profile. Always respond with valid JSON.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000,
            ]);

            $aiResponse = $response['choices'][0]['message']['content'] ?? null;

            if (!$aiResponse) {
                return response()->json([
                    'message' => 'No response from AI'
                ], 500);
            }

            return response()->json(json_decode($aiResponse, true));

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch AI response',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
