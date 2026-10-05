<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeAiChatTest extends TestCase
{
    public function test_chat_page_is_available(): void
    {
        config(['services.openai.api_key' => 'test-secret-key']);

        $this->get('/')
            ->assertOk()
            ->assertSee('WeAi')
            ->assertSee('Your question')
            ->assertDontSee('test-secret-key');
    }

    public function test_question_is_sent_to_openai_and_answer_returned(): void
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

        $this->postJson('/chat', ['message' => 'What is the answer?'])
            ->assertOk()
            ->assertExactJson(['answer' => 'The answer is 42.']);

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.openai.com/v1/chat/completions'
                && $request->hasHeader('Authorization', 'Bearer test-secret-key')
                && $request['model'] === 'test-model'
                && $request['messages'][1]['content'] === 'What is the answer?';
        });
    }

    public function test_question_is_required_and_limited_to_4000_characters(): void
    {
        $this->postJson('/chat', ['message' => ''])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('message');

        $this->postJson('/chat', ['message' => str_repeat('a', 4001)])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('message');
    }

    public function test_missing_api_key_returns_safe_error(): void
    {
        config(['services.openai.api_key' => '']);

        $this->postJson('/chat', ['message' => 'Hello'])
            ->assertStatus(503)
            ->assertExactJson(['message' => 'WeAi is not configured yet. Please try again later.']);
    }

    public function test_upstream_error_does_not_expose_provider_response(): void
    {
        config(['services.openai.api_key' => 'test-secret-key']);

        Http::fake([
            'api.openai.com/*' => Http::response(['error' => ['message' => 'sensitive provider detail']], 500),
        ]);

        $this->postJson('/chat', ['message' => 'Hello'])
            ->assertStatus(502)
            ->assertExactJson(['message' => 'WeAi is temporarily unavailable. Please try again later.'])
            ->assertDontSee('sensitive provider detail')
            ->assertDontSee('test-secret-key');
    }
}
