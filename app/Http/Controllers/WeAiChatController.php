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
        return $this->send($request);
    }

    public function store(Request $request): JsonResponse
    {
        return $this->send($request);
    }

    private function send(Request $request): JsonResponse
    {
        $data = $request->validate([
            'messages' => ['required_without:message', 'array', 'min:1', 'max:10'],
            'message' => ['required_without:messages', 'string', 'max:2000'],
            'messages.*' => ['required', 'array:role,content'],
            'messages.*.role' => ['required', 'in:user,assistant'],
            'messages.*.content' => ['required', 'string', 'max:6000'],
        ]);

        if (! isset($data['messages'])) {
            $data['messages'] = [
                ['role' => 'user', 'content' => $data['message']],
            ];
        }

        abort_unless(
            end($data['messages'])['role'] === 'user',
            422,
            'Your latest message must be a question.'
        );

        $apiKey = config('services.openai.api_key');

        if (! is_string($apiKey) || trim($apiKey) === '') {
            return response()->json([
                'message' => 'WEAI is not configured yet. Add an OpenAI API key to the server environment.',
            ], 503);
        }

        $model = config('services.openai.model');
        $model = is_string($model) && trim($model) !== '' ? trim($model) : 'gpt-4.1-mini';

        try {
            $response = Http::acceptJson()
                ->withToken(trim($apiKey))
                ->connectTimeout(10)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are WEAI, the helpful AI assistant in the WeTalk community app. Be clear, friendly, and concise. Do not claim to have access to WeTalk messages, accounts, or private community data unless the user provides that information in this conversation.',
                        ],
                        ...$data['messages'],
                    ],
                    'max_completion_tokens' => 800,
                ]);
        } catch (ConnectionException) {
            return response()->json([
                'message' => 'WEAI could not reach the AI service. Please try again shortly.',
            ], 502);
        }

        if (! $response->successful()) {
            $unavailable = $response->status() === 429 || $response->serverError();

            return response()->json([
                'message' => $unavailable
                    ? 'WEAI is temporarily unavailable. Please try again shortly.'
                    : 'WEAI could not complete that request. Please check the server configuration.',
            ], $unavailable ? 503 : 502);
        }

        $answer = data_get($response->json(), 'choices.0.message.content');

        if (! is_string($answer) || trim($answer) === '') {
            return response()->json([
                'message' => 'WEAI received an invalid response. Please try again shortly.',
            ], 502);
        }

        return response()->json(['answer' => trim($answer)]);
    }
}
