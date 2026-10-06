(() => {
    const markup = document.createElement('template');
    markup.innerHTML = `
        <div class="weai-backdrop" data-weai-backdrop></div>
        <section class="weai-panel" data-weai-panel role="dialog" aria-modal="true" aria-labelledby="weai-title" aria-hidden="true">
            <header class="weai-header">
                <span class="weai-brand" aria-hidden="true">✦</span>
                <div class="weai-heading">
                    <h2 id="weai-title">Ask WEAI</h2>
                    <p>Your WeTalk AI assistant</p>
                </div>
                <button class="weai-close" type="button" data-weai-close aria-label="Close WEAI">×</button>
            </header>
            <p class="weai-privacy">AI replies may be inaccurate. Don’t share passwords or sensitive personal information.</p>
            <div class="weai-thread" data-weai-thread aria-live="polite" aria-relevant="additions text"></div>
            <form class="weai-composer" data-weai-form>
                <textarea class="weai-input" data-weai-input rows="1" maxlength="2000" aria-label="Message WEAI" placeholder="Ask a question…"></textarea>
                <button class="weai-send" data-weai-send type="submit" aria-label="Send message">➤</button>
            </form>
        </section>`;

    document.body.append(markup.content);

    const panel = document.querySelector('[data-weai-panel]');
    const backdrop = document.querySelector('[data-weai-backdrop]');
    const thread = document.querySelector('[data-weai-thread]');
    const form = document.querySelector('[data-weai-form]');
    const input = document.querySelector('[data-weai-input]');
    const sendButton = document.querySelector('[data-weai-send]');
    const closeButton = document.querySelector('[data-weai-close]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const chatEndpoint = document.querySelector('meta[name="weai-endpoint"]')?.content;
    let history = [];
    let previousFocus = null;
    let busy = false;

    function addMessage(role, content, state = '') {
        const row = document.createElement('div');
        row.className = `weai-message${role === 'user' ? ' weai-message-user' : ''}${state ? ` weai-${state}` : ''}`;

        const avatar = document.createElement('span');
        avatar.className = 'weai-avatar';
        avatar.setAttribute('aria-hidden', 'true');
        avatar.textContent = role === 'user' ? 'Y' : '✦';

        const bubble = document.createElement('div');
        bubble.className = 'weai-bubble';
        bubble.textContent = content;

        row.append(avatar, bubble);
        thread.append(row);

        while (thread.querySelectorAll('.weai-message').length > 21) {
            thread.querySelector('.weai-message')?.remove();
        }

        thread.scrollTop = thread.scrollHeight;
        return { row, bubble };
    }

    function showWelcome() {
        thread.replaceChildren();
        const { bubble } = addMessage('assistant', 'Hi! I’m WEAI. Ask me a question, get help writing a message, or brainstorm ideas for your community.');
        const suggestions = document.createElement('div');
        suggestions.className = 'weai-suggestions';

        for (const prompt of ['Help me write an announcement', 'Suggest community event ideas', 'Summarize a topic for me']) {
            const suggestion = document.createElement('button');
            suggestion.className = 'weai-suggestion';
            suggestion.type = 'button';
            suggestion.textContent = prompt;
            suggestion.addEventListener('click', () => {
                input.value = prompt;
                form.requestSubmit();
            });
            suggestions.append(suggestion);
        }

        bubble.append(suggestions);
    }

    function openAssistant(event) {
        previousFocus = event.currentTarget;
        panel.classList.add('open');
        backdrop.classList.add('open');
        panel.setAttribute('aria-hidden', 'false');
        input.focus();
    }

    function closeAssistant() {
        panel.classList.remove('open');
        backdrop.classList.remove('open');
        panel.setAttribute('aria-hidden', 'true');
        previousFocus?.focus();
    }

    for (const launcher of document.querySelectorAll('[data-open-weai]')) {
        launcher.addEventListener('click', openAssistant);
    }

    const directMessagesButton = [...document.querySelectorAll('.nav-btn')]
        .find((button) => button.textContent.includes('Direct Messages'));

    if (directMessagesButton) {
        const launcher = document.createElement('button');
        launcher.className = 'nav-btn weai-launcher';
        launcher.type = 'button';
        launcher.innerHTML = '<span class="ico" aria-hidden="true">✦</span>Ask WEAI';
        launcher.setAttribute('data-open-weai', '');
        directMessagesButton.after(launcher);
        launcher.addEventListener('click', openAssistant);
    }

    const chatTools = document.querySelector('.chat-tools');

    if (chatTools) {
        const toolbarButton = document.createElement('button');
        toolbarButton.className = 'weai-toolbar-button';
        toolbarButton.type = 'button';
        toolbarButton.setAttribute('aria-label', 'Open WEAI assistant');
        toolbarButton.setAttribute('data-open-weai', '');
        toolbarButton.textContent = '✦ WEAI';
        chatTools.append(toolbarButton);
        toolbarButton.addEventListener('click', openAssistant);
    }

    closeButton.addEventListener('click', closeAssistant);
    backdrop.addEventListener('click', closeAssistant);

    document.addEventListener('keydown', (event) => {
        if (!panel.classList.contains('open')) {
            return;
        }

        if (event.key === 'Escape') {
            closeAssistant();
            return;
        }

        if (event.key !== 'Tab') {
            return;
        }

        const focusable = [...panel.querySelectorAll('button:not(:disabled), textarea:not(:disabled)')];
        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    });

    input.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            form.requestSubmit();
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const question = input.value.trim();

        if (!question || question.length > 2000 || busy) {
            return;
        }

        addMessage('user', question);
        input.value = '';

        if (!csrfToken || !chatEndpoint) {
            addMessage('assistant', 'The secure session token is missing. Reload the dashboard and try again.', 'error');
            return;
        }

        const priorHistory = history.slice(-8);
        const loading = addMessage('assistant', 'Thinking…', 'loading');
        busy = true;
        input.disabled = true;
        sendButton.disabled = true;

        try {
            const response = await fetch(chatEndpoint, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    messages: [...priorHistory, { role: 'user', content: question }],
                }),
            });
            const result = await response.json().catch(() => null);
            loading.row.remove();

            if (!response.ok) {
                addMessage('assistant', result?.message || 'WEAI couldn’t answer just now. Please try again.', 'error');
                return;
            }

            if (typeof result?.answer !== 'string' || !result.answer.trim()) {
                addMessage('assistant', 'WEAI returned an empty answer. Please try again.', 'error');
                return;
            }

            addMessage('assistant', result.answer);
            history = [
                ...priorHistory,
                { role: 'user', content: question },
                { role: 'assistant', content: result.answer },
            ];
        } catch {
            loading.row.remove();
            addMessage('assistant', 'WEAI couldn’t connect. Check your internet connection and try again.', 'error');
        } finally {
            busy = false;
            input.disabled = false;
            sendButton.disabled = false;
            if (panel.classList.contains('open')) {
                input.focus();
            }
        }
    });

    showWelcome();
})();
