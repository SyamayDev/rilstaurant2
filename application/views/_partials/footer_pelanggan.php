</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --maroon: #6b0f0f;
        --light-bg: #fafafa;
        --white: #fff;
        --border: #ddd;
    }

    /* ===== Container Utama ===== */
    #ril-chatbot-container {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 999;
        font-family: "Segoe UI", sans-serif;
    }

    /* ===== Tombol Bulat Chat ===== */
    #ril-chatbot-icon {
        width: 70px;
        height: 70px;
        font-size: 30px;
        background: var(--maroon);
        border-radius: 50%;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    #ril-chatbot-icon:hover {
        transform: scale(1.08);
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
    }

    /* ===== Jendela Chat ===== */
    #ril-chat-window {
        width: 360px;
        max-height: 560px;
        background: var(--white);
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        overflow: hidden;
        position: fixed;
        bottom: 100px;
        right: 25px;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        transform: scale(0.8);
        opacity: 0;
        pointer-events: none;
    }

    #ril-chat-window:not(.hidden) {
        transform: scale(1);
        opacity: 1;
        pointer-events: auto;
    }

    #ril-chat-window.hidden {
        transform: scale(0.8);
        opacity: 0;
        pointer-events: none;
    }

    /* ===== Header ===== */
    .chat-header {
        background: var(--maroon);
        color: #fff;
        padding: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .chat-header h3 {
        font-size: 1.1em;
        margin: 0;
    }

    .chat-header button {
        background: none;
        border: none;
        color: #fff;
        font-size: 1.2em;
        cursor: pointer;
        transition: color 0.2s;
    }

    .chat-header button:hover {
        color: #FFD700;
    }

    /* ===== Body ===== */
    .chat-body {
        flex-grow: 1;
        padding: 15px;
        overflow-y: auto;
        background: var(--light-bg);
    }

    /* ===== Chat Message ===== */
    .chat-message {
        display: flex;
        align-items: flex-start;
        margin-bottom: 14px;
        animation: bubbleFade 0.3s ease;
    }

    @keyframes bubbleFade {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== Avatar AI ===== */
    .chat-message.ai-message::before {
        content: "🤖";
        font-size: 22px;
        margin-right: 8px;
        background: var(--maroon);
        color: #fff;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-shrink: 0;
    }

    /* ===== User Message (tanpa icon) ===== */
    .chat-message.user-message {
        justify-content: flex-end;
    }

    .message-content {
        max-width: 80%;
        padding: 10px 14px;
        border-radius: 18px;
        line-height: 1.4;
        font-size: 0.95em;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        word-wrap: break-word;
    }

    .chat-message.user-message .message-content {
        background: var(--maroon);
        color: #fff;
        border-bottom-right-radius: 5px;
    }

    .chat-message.ai-message .message-content {
        background: #fff;
        border: 1px solid #ccc;
        color: #222;
        border-bottom-left-radius: 5px;
    }

    /* ===== Footer ===== */
    .chat-footer {
        display: flex;
        padding: 10px;
        background: var(--white);
        border-top: 1px solid var(--border);
        flex-shrink: 0;
    }

    #ril-user-input {
        flex-grow: 1;
        border: 1px solid var(--border);
        border-radius: 25px;
        padding: 8px 14px;
        outline: none;
        font-size: 0.95em;
        transition: border 0.3s;
    }

    #ril-user-input:focus {
        border-color: var(--maroon);
    }

    #ril-send-btn {
        width: 42px;
        height: 42px;
        border: none;
        background: var(--maroon);
        color: #fff;
        border-radius: 50%;
        margin-left: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s;
    }

    #ril-send-btn:hover {
        background: #8b1b1b;
        transform: scale(1.05);
    }

    /* ===== Scrollbar ===== */
    .chat-body::-webkit-scrollbar { width: 6px; }
    .chat-body::-webkit-scrollbar-thumb { background: var(--maroon); border-radius: 10px; }

    /* ===== Typing Indicator ===== */
    .typing-indicator {
        display: flex;
        align-items: center;
        gap: 3px;
        padding: 8px 14px;
        border-radius: 18px;
        background: #fff;
        border: 1px solid #ccc;
        animation: fadeIn 0.3s ease;
    }

    .typing-indicator span {
        height: 8px;
        width: 8px;
        background-color: #999;
        border-radius: 50%;
        display: inline-block;
        animation: bounce 1.3s infinite ease-in-out both;
    }

    .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
    .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1.0); }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== Responsive Mobile ===== */
    @media (max-width: 600px) {
        #ril-chat-window {
            width: 92%;
            right: 4%;
            bottom: 90px;
            max-height: 80vh;
        }

        #ril-chatbot-icon {
            width: 60px;
            height: 60px;
            font-size: 26px;
        }

        .chat-header h3 {
            font-size: 1em;
        }

        .message-content {
            font-size: 0.9em;
        }

        #ril-user-input {
            font-size: 0.9em;
        }
    }
</style>

<?php if ($this->session->flashdata('success')): ?>
<script> Swal.fire('Sukses', '<?= $this->session->flashdata('success') ?>', 'success') </script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
<script> Swal.fire('Error', '<?= $this->session->flashdata('error') ?>', 'error') </script>
<?php endif; ?>

<!-- Chatbot Structure -->
<div id="ril-chatbot-container">
    <div id="ril-chatbot-icon" title="Buka Chat">
        🤖
    </div>

    <div id="ril-chat-window" class="hidden">
        <div class="chat-header">
            <h3>RIL - Asisten Rilstaurant</h3>
            <div>
                <button id="ril-clear-btn" title="Hapus percakapan">🧹</button>
                <button id="ril-close-btn" title="Tutup chat">&times;</button>
            </div>
        </div>
        <div class="chat-body" id="ril-chat-body"></div>
        <div class="chat-footer">
            <input type="text" id="ril-user-input" placeholder="Ketik pesan...">
            <button id="ril-send-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                     <line x1="22" y1="2" x2="11" y2="13"></line>
                     <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- URLs asli tetap -->
<script>
window.chatbot_context_url = '<?= site_url("katalog/get_chatbot_context") ?>';
window.chat_api_url = '<?= site_url("api/chat") ?>';
</script>
<script src="<?= base_url('assets/js/chatbot.js?v=1.2') ?>"></script>

<!-- Script Animasi Ketik -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const chatBody = document.getElementById('ril-chat-body');
    if (!chatBody) return;

    const showTypingIndicator = () => {
        if (document.getElementById('typing-indicator')) return;
        const msg = document.createElement('div');
        msg.classList.add('chat-message', 'ai-message');
        msg.id = 'typing-indicator';
        const bubble = document.createElement('div');
        bubble.classList.add('message-content');
        bubble.innerHTML = '<div class="typing-indicator"><span></span><span></span><span></span></div>';
        msg.appendChild(bubble);
        chatBody.appendChild(msg);
        chatBody.scrollTop = chatBody.scrollHeight;
    };

    const hideTypingIndicator = () => {
        const typing = document.getElementById('typing-indicator');
        if (typing) typing.remove();
    };

    const observer = new MutationObserver(mutations => {
        for (const m of mutations) {
            if (m.type !== 'childList' || !m.addedNodes.length) continue;
            let userAdded = false, botAdded = false;
            m.addedNodes.forEach(node => {
                if (node.nodeType === 1 && node.classList.contains('chat-message')) {
                    if (node.classList.contains('user-message')) userAdded = true;
                    else if (node.classList.contains('ai-message') && node.id !== 'typing-indicator') botAdded = true;
                }
            });
            if (userAdded) showTypingIndicator();
            if (botAdded) hideTypingIndicator();
        }
    });
    observer.observe(chatBody, { childList: true });

    document.getElementById('ril-clear-btn')?.addEventListener('click', hideTypingIndicator);
});
</script>

</body>
</html>
