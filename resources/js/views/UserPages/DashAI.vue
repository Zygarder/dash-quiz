<template>
    <div class="chat-wrapper">

        <!-- HEADER -->
        <header class="chat-header">
            <i class="fas fa-robot"></i>
            <span>Dash AI</span>
        </header>

        <!-- CHAT BOX -->
        <section class="chat-box" ref="chatBox">

            <div v-for="(msg, index) in messages" :key="index" :class="['msg', msg.role]">
                <div class="bubble">
                    {{ msg.text }}
                </div>
            </div>

            <!-- typing -->
            <div v-if="loading" class="msg ai">
                <div class="bubble typing">
                    <i class="fas fa-ellipsis-h"></i>
                </div>
            </div>

        </section>

        <!-- INPUT -->
        <footer class="chat-input">
            <input v-model="input" type="text" placeholder="Ask about Dash Quiz..." @keyup.enter="sendMessage" />

            <button @click="sendMessage" :disabled="loading">
                <i v-if="!loading" class="fas fa-paper-plane"></i>
                <i v-else class="fas fa-spinner fa-spin"></i>
            </button>
        </footer>

    </div>
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
        text: "Hi there 👋 I'm Dash AI, your quiz buddy. I'm here to help you with Dash Quiz, your scores, and your progress."
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
            ?? "No response from Dash"

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
    display: flex;
    flex-direction: column;
    height: 100%;
    max-height: 100%;
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

/* HEADER */
.chat-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: #4f46e5;
    color: white;
    font-weight: 600;
    font-size: 14px;
}

/* CHAT AREA */
.chat-box {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: #f9fafb;
}

/* MESSAGE */
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
    max-width: 75%;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 13px;
    line-height: 1.5;
    word-wrap: break-word;
}

/* USER */
.msg.user .bubble {
    background: #4f46e5;
    color: white;
    border-bottom-right-radius: 4px;
}

/* AI */
.msg.ai .bubble {
    background: #e5e7eb;
    color: #111827;
    border-bottom-left-radius: 4px;
}

/* typing */
.typing {
    opacity: 0.7;
    font-size: 12px;
}

/* INPUT */
.chat-input {
    display: flex;
    gap: 8px;
    padding: 10px;
    border-top: 1px solid #e5e7eb;
    background: #fff;
}

.chat-input input {
    flex: 1;
    padding: 10px 12px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    font-size: 13px;
    outline: none;
    transition: 0.2s;
}

.chat-input input:focus {
    border-color: #4f46e5;
}

/* BUTTON */
.chat-input button {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    border: none;
    background: #4f46e5;
    color: white;
    cursor: pointer;
    transition: 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chat-input button:hover {
    background: #6366f1;
}

.chat-input button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>