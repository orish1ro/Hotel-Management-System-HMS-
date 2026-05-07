<style>
    #chat-icon {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #003366;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        z-index: 9999;
        transition: background 0.2s, transform 0.2s;
    }
    #chat-icon:hover { background: #003d7a; transform: scale(1.08); }

    #chat-box {
        display: none;
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 340px;
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        flex-direction: column;
        z-index: 9999;
        overflow: hidden;
    }

    #chat-box.open { display: flex; }

    .chat-header {
        background: #003366;
        color: #fff;
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-header-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chat-online-dot {
        width: 9px;
        height: 9px;
        background: #4ade80;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .chat-title {
        font-size: 14px;
        font-weight: 700;
    }

    #close-chat {
        background: none;
        border: none;
        color: rgba(255,255,255,0.8);
        cursor: pointer;
        padding: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }
    #close-chat:hover { color: #fff; }

    #chat-history {
        height: 300px;
        padding: 14px;
        overflow-y: auto;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    #chat-history::-webkit-scrollbar { width: 4px; }
    #chat-history::-webkit-scrollbar-track { background: transparent; }
    #chat-history::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    .chat-bubble-wrap {
        display: flex;
        margin-top: 2px;
    }
    .chat-bubble-wrap.guest { justify-content: flex-end; }
    .chat-bubble-wrap.staff { justify-content: flex-start; }

    .chat-bubble {
        padding: 10px 14px;
        border-radius: 16px;
        max-width: 78%;
        font-size: 13px;
        line-height: 1.5;
        box-shadow: 0 1px 3px rgba(0,0,0,0.07);
    }

    .chat-bubble-wrap.guest .chat-bubble {
        background: #003366;
        color: #fff;
        border-bottom-right-radius: 4px;
    }

    .chat-bubble-wrap.staff .chat-bubble {
        background: #e8edf3;
        color: #003366;
        border-bottom-left-radius: 4px;
    }

    .chat-footer {
        padding: 10px 12px;
        border-top: 1px solid #e2e8f0;
        background: #fff;
        display: flex;
        gap: 8px;
        align-items: center;
    }

    #chat-input {
        flex: 1;
        padding: 8px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 20px;
        font-size: 13px;
        outline: none;
        background: #f8fafc;
        color: #003366;
        transition: border-color 0.2s;
    }
    #chat-input:focus { border-color: #003366; background: #fff; }

    #send-btn {
        width: 36px;
        height: 36px;
        background: #003366;
        color: #fff;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.2s;
    }
    #send-btn:hover { background: #003d7a; }
</style>

{{-- Chat toggle button --}}
<button id="chat-icon" title="Chat with us">
    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
    </svg>
</button>

{{-- Chat box --}}
<div id="chat-box">
    <div class="chat-header">
        <div class="chat-header-left">
            <div class="chat-online-dot"></div>
            <span class="chat-title">Ragadio Plaza Support</span>
        </div>
        <button id="close-chat" title="Close">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div id="chat-history">
        <div class="chat-bubble-wrap staff">
            <div class="chat-bubble">Hello! Welcome to Ragadio Plaza Hotel. How can I help you today?</div>
        </div>
    </div>

    <div class="chat-footer">
        <input type="text" id="chat-input" placeholder="Type your message...">
        <button id="send-btn" title="Send">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
            </svg>
        </button>
    </div>
</div>

<script>
    const chatIcon    = document.getElementById('chat-icon');
    const chatBox     = document.getElementById('chat-box');
    const closeChat   = document.getElementById('close-chat');
    const chatInput   = document.getElementById('chat-input');
    const sendBtn     = document.getElementById('send-btn');
    const chatHistory = document.getElementById('chat-history');

    chatIcon.addEventListener('click', () => {
        chatBox.classList.add('open');
        chatIcon.style.display = 'none';
    });

    closeChat.addEventListener('click', () => {
        chatBox.classList.remove('open');
        chatIcon.style.display = 'flex';
    });

    sendBtn.addEventListener('click', function () {
        const messageText = chatInput.value.trim();
        if (!messageText) return;

        fetch('/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: messageText })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                chatInput.value = '';
                appendBubble(messageText, 'guest');
            } else {
                alert('Please login first to send a message.');
            }
        })
        .catch(err => console.error(err));
    });

    chatInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') sendBtn.click();
    });

    function appendBubble(text, type) {
        const wrap = document.createElement('div');
        wrap.className = 'chat-bubble-wrap ' + type;
        wrap.innerHTML = `<div class="chat-bubble">${text}</div>`;
        chatHistory.appendChild(wrap);
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    function loadMessages() {
        fetch('/get-messages')
            .then(r => r.json())
            .then(messages => {
                chatHistory.innerHTML = `
                    <div class="chat-bubble-wrap staff">
                        <div class="chat-bubble">Hello! Welcome to Ragadio Plaza Hotel. How can I help you today?</div>
                    </div>
                `;
                messages.forEach(msg => {
                    const type = msg.sender_type === 'Guest' ? 'guest' : 'staff';
                    appendBubble(msg.message_text, type);
                });
            });
    }

    window.addEventListener('load', loadMessages);
    setInterval(loadMessages, 3000);
</script>