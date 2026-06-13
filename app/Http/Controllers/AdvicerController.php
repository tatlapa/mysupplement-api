<?php

namespace App\Http\Controllers;

use App\Http\Requests\Advicer\AdvicerRequest;
use App\Services\AdvicerService;

class AdvicerController extends Controller
{
    public function __construct(private AdvicerService $advicerService) {}

    public function getSupplementRecommendations(AdvicerRequest $request)
    {
        try {
            $recommendations = $this->advicerService->getRecommendations($request->validated());

            return response()->json($recommendations);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch AI response',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
