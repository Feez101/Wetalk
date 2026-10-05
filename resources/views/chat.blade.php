<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WeAi · Wetalk</title>
    <style>
        :root { color-scheme: light; font-family: system-ui, sans-serif; color: #172033; background: #f4f6fb; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        main { width: min(100%, 720px); height: min(760px, calc(100vh - 48px)); min-height: 420px; display: flex; flex-direction: column; overflow: hidden; background: white; border: 1px solid #e2e7f0; border-radius: 18px; box-shadow: 0 16px 48px #25365b12; }
        header { padding: 22px 24px; border-bottom: 1px solid #edf0f5; }
        h1 { margin: 0; font-size: 1.2rem; }
        header p { margin: 5px 0 0; color: #68738a; font-size: .92rem; }
        #messages { flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 14px; }
        .message { max-width: 85%; padding: 12px 15px; border-radius: 16px; white-space: pre-wrap; overflow-wrap: anywhere; line-height: 1.5; }
        .assistant { align-self: flex-start; background: #f0f3fa; }
        .user { align-self: flex-end; color: white; background: #465bd8; }
        form { display: flex; align-items: flex-end; gap: 12px; padding: 16px; border-top: 1px solid #edf0f5; }
        textarea { flex: 1; min-height: 48px; max-height: 140px; resize: vertical; padding: 13px 14px; border: 1px solid #d9deea; border-radius: 12px; font: inherit; }
        button { min-height: 48px; padding: 0 20px; border: 0; border-radius: 12px; color: white; background: #465bd8; font: inherit; font-weight: 600; cursor: pointer; }
        button:disabled { cursor: wait; opacity: .6; }
        @media (max-width: 520px) { body { padding: 0; } main { height: 100vh; min-height: 0; border: 0; border-radius: 0; } #messages { padding: 18px; } }
    </style>
</head>
<body>
<main>
    <header>
        <h1>WeAi</h1>
        <p>Ask a question and WeAi will help.</p>
    </header>
    <section id="messages" aria-live="polite" aria-label="Chat messages">
        <div class="message assistant">Hi! What would you like to know?</div>
    </section>
    <form id="chat-form">
        @csrf
        <textarea id="message" name="message" maxlength="4000" placeholder="Type your question…" required aria-label="Your question"></textarea>
        <button id="send" type="submit">Send</button>
    </form>
</main>
<script>
    const form = document.querySelector('#chat-form');
    const input = document.querySelector('#message');
    const send = document.querySelector('#send');
    const messages = document.querySelector('#messages');

    function addMessage(text, role) {
        const item = document.createElement('div');
        item.className = `message ${role}`;
        item.textContent = text;
        messages.append(item);
        messages.scrollTop = messages.scrollHeight;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        addMessage(message, 'user');
        input.value = '';
        send.disabled = true;
        send.textContent = 'Thinking…';

        try {
            const response = await fetch(@json(route('chat.send')), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ message }),
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'WeAi could not answer right now.');
            addMessage(result.answer, 'assistant');
        } catch (error) {
            addMessage(error.message || 'WeAi could not answer right now. Please try again.', 'assistant');
        } finally {
            send.disabled = false;
            send.textContent = 'Send';
            input.focus();
        }
    });
</script>
</body>
</html>
