# WeTalk

WeTalk is a Laravel 12 community messaging application with a responsive dashboard, channels, direct messages, and WEAI, an AI Q&A assistant.

## Requirements

- PHP 8.2 or newer with the extensions required by Laravel
- Composer

## Run locally

1. Install dependencies: `composer install`.
2. Copy `.env.example` to `.env`.
3. Generate the Laravel application key: `php artisan key:generate`.
4. Set `OPENAI_API_KEY` in your **local `.env` file** to a newly created OpenAI API key. Never put it in source code, browser JavaScript, screenshots, or chat.
5. Optionally set `OPENAI_MODEL` to a model enabled for your OpenAI account. The default is `gpt-4.1-mini`.
6. Start the app: `php artisan serve`.
7. Open `http://127.0.0.1:8000` or `http://127.0.0.1:8000/dashboard`.

`.env` is excluded from Git. Keep the example key value blank, and do not commit your local environment file. If an API key is ever shared or exposed, revoke it immediately and replace it with a new key.

## WEAI

Open WEAI from **Ask WEAI** in the dashboard navigation or the WEAI button above a conversation. The browser sends the conversation to Laravel using a same-origin, CSRF-protected request. Laravel validates and rate-limits requests, calls OpenAI using the server-side `OPENAI_API_KEY`, and returns the answer. The secret is not sent to the browser or logged. If the server key is missing or the AI service is unavailable, the dashboard displays an error without exposing provider details.

WEAI is a general-purpose assistant; it does not have access to private WeTalk communities, accounts, or messages. Do not include passwords or sensitive personal information in prompts.

## Project structure

- `routes/web.php`: homepage, dashboard, and WEAI routes
- `resources/views/home.blade.php`: WeTalk homepage
- `resources/views/dashboard.blade.php`: dashboard
- `public/css/wetalk.css`: homepage styling
- `public/css/weai.css` and `public/js/weai.js`: WEAI interface
- `app/Http/Controllers/WeaiChatController.php`: validated server-side OpenAI chat endpoint
- `config/services.php`: server-side OpenAI environment settings
- `database/migrations`: initial Laravel data model

The archive includes starter authentication, community, channel, and message APIs. The dashboard's community data remains a browser-side demo; WEAI does not read or save those messages.
