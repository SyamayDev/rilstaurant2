document.addEventListener('DOMContentLoaded', () => {
    // 1. Get DOM Elements
    const chatbotIcon = document.getElementById('ril-chatbot-icon');
    const chatWindow = document.getElementById('ril-chat-window');
    const closeBtn = document.getElementById('ril-close-btn');
    const chatBody = document.getElementById('ril-chat-body');
    const userInput = document.getElementById('ril-user-input');
    const sendBtn = document.getElementById('ril-send-btn');
    const clearBtn = document.getElementById('ril-clear-btn');

    if (!chatbotIcon || !chatWindow) {
        console.error('Chatbot elements not found. Make sure to include the chatbot HTML snippet in your view.');
        return;
    }

    let chatHistory = [];

    // 2. UI Interaction Logic
    chatbotIcon.addEventListener('click', () => toggleChatWindow(true));
    closeBtn.addEventListener('click', () => toggleChatWindow(false));
    clearBtn.addEventListener('click', clearChat);
    sendBtn.addEventListener('click', handleUserInput);
    userInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleUserInput();
        }
    });

    function toggleChatWindow(show) {
        if (show) {
            chatWindow.classList.remove('hidden');
            chatbotIcon.style.display = 'none';
            loadChatHistory();
            userInput.focus();
        } else {
            chatWindow.classList.add('hidden');
            chatbotIcon.style.display = 'flex';
        }
    }

    // 3. Chat History Management
    function loadChatHistory() {
        const history = JSON.parse(sessionStorage.getItem('ril_chat_history')) || [];
        chatHistory = history;
        renderChatHistory();
        if (history.length === 0) {
            const welcomeMsg = 'Halo Kak! Ada yang bisa RIL bantu seputar Rilstaurant? 😊';
            addMessageToUI('ai', welcomeMsg, false); // Don't save welcome message to history yet
        }
    }
    
    function renderChatHistory() {
        chatBody.innerHTML = '';
        chatHistory.forEach(msg => addMessageToUI(msg.sender, msg.text, false));
    }

    function saveChatHistory() {
        sessionStorage.setItem('ril_chat_history', JSON.stringify(chatHistory));
    }

    function clearChat() {
        sessionStorage.removeItem('ril_chat_history');
        chatHistory = [];
        renderChatHistory();
        const welcomeMsg = 'Percakapan dihapus. Ada lagi yang bisa RIL bantu?';
        addMessageToUI('ai', welcomeMsg, false);

        // Optionally, notify the backend that the chat history is cleared
        fetch(window.clear_chat_api_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(response => {
            if (!response.ok) {
                console.error('Failed to notify backend about chat clear.');
            }
        }).catch(error => {
            console.error('Error notifying backend about chat clear:', error);
        });
    }

    // 4. Message Handling
    function addMessageToUI(sender, text, save = true) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('mb-2', 'max-w-[80%]', 'break-words'); // chat-message
        
        const contentDiv = document.createElement('div');
        contentDiv.classList.add('p-3', 'rounded-xl', 'leading-tight'); // message-content

        if (sender === 'user') {
            messageDiv.classList.add('self-end'); // user-message
            contentDiv.classList.add('bg-primary', 'text-white', 'rounded-br-sm');
        } else {
            messageDiv.classList.add('self-start'); // ai-message
            contentDiv.classList.add('bg-gray-200', 'text-gray-800', 'rounded-bl-sm');
        }
        
        contentDiv.innerText = text;
        
        messageDiv.appendChild(contentDiv);
        chatBody.appendChild(messageDiv);
        chatBody.scrollTop = chatBody.scrollHeight;

        if (save) {
            chatHistory.push({ sender, text });
            saveChatHistory();
        }
    }

    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typing-indicator';
        typingDiv.classList.add('mb-2', 'max-w-[80%]', 'break-words', 'self-start'); // ai-message
        typingDiv.innerHTML = `
            <div class="p-3 rounded-xl leading-tight bg-gray-200 text-gray-800 rounded-bl-sm">
                <div class="flex items-center justify-center h-5">
                    <span class="h-2 w-2 mx-0.5 bg-gray-500 rounded-full opacity-70 animate-bounce" style="animation-delay: 0s;"></span>
                    <span class="h-2 w-2 mx-0.5 bg-gray-500 rounded-full opacity-70 animate-bounce" style="animation-delay: 0.2s;"></span>
                    <span class="h-2 w-2 mx-0.5 bg-gray-500 rounded-full opacity-70 animate-bounce" style="animation-delay: 0.4s;"></span>
                </div>
            </div>
        `;
        chatBody.appendChild(typingDiv);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function removeTypingIndicator() {
        const indicator = document.getElementById('typing-indicator');
        if (indicator) indicator.remove();
    }

    async function handleUserInput() {
        const userMessage = userInput.value.trim();
        if (!userMessage) return;

        addMessageToUI('user', userMessage);
        userInput.value = '';
        showTypingIndicator();

        try {
            // Send the entire chatHistory, as the current user message has already been added
            const aiResponse = await getAiResponseFromServer(userMessage, chatHistory); 
            removeTypingIndicator();
            addMessageToUI('ai', aiResponse);
        } catch (error) {
            removeTypingIndicator();
            addMessageToUI('ai', 'Aduh, maaf Kak, sepertinya ada sedikit gangguan. Boleh coba tanya lagi? 😥');
            console.error('Error getting AI response:', error);
        }
    }

    // 5. Secure API Interaction via Backend
async function getAiResponseFromServer(userMessage, historyContext) {
        const response = await fetch(window.chat_api_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            // Send the new message and the past conversation history
            body: JSON.stringify({ 
                message: userMessage,
                history: historyContext 
            })
        });

        if (!response.ok) {
            const errorData = await response.json().catch(() => null); // Try to get JSON error details
            console.error('Backend Error:', `Gagal menghubungi AI. Kode: ${response.status}`, errorData);
            throw new Error(`Gagal menghubungi AI. Kode: ${response.status}`);
        }

        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        return data.reply;
    }
});


