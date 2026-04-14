<template>
    <main class="chat-wrapper">

        <!-- HEADER -->
        <header class="chat-header">
            🤖 Dash Quiz AI Buddy
        </header>

        <!-- CHAT BOX -->
        <section class="chat-box" ref="chatBox">

            <div v-for="(msg, index) in messages" :key="index" :class="['msg', msg.role]">
                <div class="bubble">
                    {{ msg.text }}
                </div>
            </div>

            <!-- typing indicator -->
            <div v-if="loading" class="msg ai">
                <div class="bubble">Typing...</div>
            </div>

        </section>

        <!-- INPUT -->
        <footer class="chat-input">

            <input v-model="input" type="text" placeholder="Ask about Dash Quiz..." @keyup.enter="sendMessage" />

            <button @click="sendMessage" :disabled="loading">
                {{ loading ? "..." : "Send" }}
            </button>

        </footer>

    </main>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import axios from 'axios'

const input = ref('')
const loading = ref(false)
const chatBox = ref(null)

const messages = ref([
    {
        role: 'ai',
        text: 'Hello 👋 I am your Dash Quiz AI Buddy. Ask me anything about the system.'
    }
])

// scroll to bottom
const scrollBottom = async () => {
    await nextTick()
    if (chatBox.value) {
        chatBox.value.scrollTop = chatBox.value.scrollHeight
    }
}

// SEND MESSAGE
const sendMessage = async () => {
    if (!input.value.trim() || loading.value) return

    const userText = input.value

    // push user message
    messages.value.push({
        role: 'user',
        text: userText
    })

    input.value = ''
    loading.value = true

    try {
        const res = await axios.post('/api/chatbot', {
            message: userText
        })

        console.log("RAW RESPONSE:", res.data)

        // SAFE extraction (VERY IMPORTANT)
        const reply =
            res.data?.candidates?.[0]?.content?.parts?.[0]?.text
            ?? "No response from AI"

        messages.value.push({
            role: 'ai',
            text: reply
        })

    } catch (err) {
        console.error(err)

        messages.value.push({
            role: 'ai',
            text: "⚠️ Failed to get response"
        })
    }

    loading.value = false
}
</script>

<style scoped>
.chat-wrapper {
    height: 100vh;
    display: flex;
    flex-direction: column;
    background: #f9fafb;
}

/* HEADER */
.chat-header {
    background: #4f46e5;
    color: white;
    padding: 14px;
    font-weight: 600;
    text-align: center;
}

/* CHAT BOX */
.chat-box {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* MESSAGE ROW */
.msg {
    display: flex;
}

.msg.user {
    justify-content: flex-end;
}

.msg.ai {
    justify-content: flex-start;
}

/* BUBBLE */
.bubble {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 14px;
    line-height: 1.4;
    word-wrap: break-word;
}

/* USER STYLE */
.msg.user .bubble {
    background: #4f46e5;
    color: white;
    border-bottom-right-radius: 4px;
}

/* AI STYLE */
.msg.ai .bubble {
    background: #e5e7eb;
    color: #111827;
    border-bottom-left-radius: 4px;
}

/* INPUT AREA */
.chat-input {
    display: flex;
    padding: 10px;
    gap: 10px;
    border-top: 1px solid #ddd;
    background: white;
}

.chat-input input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
}

.chat-input button {
    padding: 10px 16px;
    background: #4f46e5;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.chat-input button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>