<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeAiChatController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
        ]);

        $apiKey = config('services.openai.api_key');

        if (! is_string($apiKey) || trim($apiKey) === '') {
            return response()->json([
                'message' => 'WeAi is not configured yet. Please try again later.',
            ], 503);
        }

        try {
            $response = Http::acceptJson()
                ->withToken($apiKey)
                ->connectTimeout(5)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => config('services.openai.model'),
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are WeAi, a helpful assistant. Answer the user clearly and accurately.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $validated['message'],
                        ],
                    ],
                ]);
        } catch (ConnectionException) {
            return $this->upstreamError();
        }

        if (! $response->successful()) {
            return $this->upstreamError();
        }

        $answer = $response->json('choices.0.message.content');

        if (! is_string($answer) || trim($answer) === '') {
            return $this->upstreamError();
        }

        return response()->json(['answer' => $answer]);
    }

    private function upstreamError(): JsonResponse
    {
        return response()->json([
            'message' => 'WeAi is temporarily unavailable. Please try again later.',
        ], 502);
    }
}
