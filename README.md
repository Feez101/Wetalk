# Wetalk

Wetalk is a minimal Laravel application with a WeAi question-and-answer chat.
Questions are sent to the application backend, which calls the OpenAI Chat
Completions API. The API key is read only by Laravel and is never sent to the
browser.

## Requirements

- PHP 8.2 or later with the extensions required by Laravel
- Composer

## Local setup

1. Install dependencies with `composer install`.
2. Copy `.env.example` to `.env` and set `OPENAI_API_KEY` to your **new,
   rotated** API key. The previously shared key is compromised and must not be
   used. Optionally set `OPENAI_MODEL`; it defaults to `gpt-4o-mini`.
3. Generate the application key with `php artisan key:generate`.
4. Start the app with `php artisan serve` and open the URL printed by Artisan.

Never put an API key in frontend code, commit it, or share it in logs. The
placeholder in `.env.example` is not a usable key.

Run the focused feature tests with `php artisan test --filter=WeAiChatTest`.
