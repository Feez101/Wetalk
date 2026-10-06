<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeAiChatTest extends TestCase
{
    public function test_dashboard_is_available_with_csrf_protected_weai_entry_point(): void
    {
        config(['services.openai.api_key' => 'test-secret-key']);

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('name="csrf-token"', false)
            ->assertSee('name="weai-endpoint"', false)
            ->assertSee('WeTalk Dashboard');

        $this->get('/')
            ->assertOk()
            ->assertDontSee('test-secret-key');
    }

    public function test_conversation_is_sent_to_openai_and_answer_returned(): void
    {
        config([
            'services.openai.api_key' => 'test-secret-key',
            'services.openai.model' => 'test-model',
        ]);

        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => 'The answer is 42.']],
                ],
            ]),
        ]);

        $this->postJson('/weai/chat', [
            'messages' => [
                ['role' => 'user', 'content' => 'What is the answer?'],
            ],
        ])
            ->assertOk()
            ->assertExactJson(['answer' => 'The answer is 42.']);

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.openai.com/v1/chat/completions'
                && $request->hasHeader('Authorization', 'Bearer test-secret-key')
                && $request['model'] === 'test-model'
                && $request['messages'][1]['content'] === 'What is the answer?';
        });
    }

    public function test_legacy_chat_endpoint_accepts_a_single_question(): void
    {
        config([
            'services.openai.api_key' => 'test-secret-key',
            'services.openai.model' => 'test-model',
        ]);

        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => 'Hello!']],
                ],
            ]),
        ]);

        $this->postJson('/chat', ['message' => 'Hello'])
            ->assertOk()
            ->assertExactJson(['answer' => 'Hello!']);

        Http::assertSent(fn ($request): bool => $request['messages'][1]['content'] === 'Hello');
    }

    public function test_chat_validates_message_history_and_content_limits(): void
    {
        $this->postJson('/weai/chat', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('messages');

        $this->postJson('/weai/chat', [
            'messages' => [
                ['role' => 'assistant', 'content' => 'This conversation has no user question.'],
            ],
        ])->assertUnprocessable();

        $this->postJson('/weai/chat', [
            'messages' => [
                ['role' => 'user', 'content' => str_repeat('a', 6001)],
            ],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('messages.0.content');

        $this->postJson('/chat', ['message' => str_repeat('a', 2001)])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('message');
    }

    public function test_missing_api_key_returns_safe_error(): void
    {
        config(['services.openai.api_key' => '']);

        $this->postJson('/weai/chat', [
            'messages' => [
                ['role' => 'user', 'content' => 'Hello'],
            ],
        ])
            ->assertStatus(503)
            ->assertExactJson(['message' => 'WEAI is not configured yet. Add an OpenAI API key to the server environment.']);
    }

    public function test_upstream_error_does_not_expose_provider_response(): void
    {
        config(['services.openai.api_key' => 'test-secret-key']);

        Http::fake([
            'api.openai.com/*' => Http::response(['error' => ['message' => 'sensitive provider detail']], 500),
        ]);

        $this->postJson('/weai/chat', [
            'messages' => [
                ['role' => 'user', 'content' => 'Hello'],
            ],
        ])
            ->assertStatus(503)
            ->assertExactJson(['message' => 'WEAI is temporarily unavailable. Please try again shortly.'])
            ->assertDontSee('sensitive provider detail')
            ->assertDontSee('test-secret-key');
    }
}
