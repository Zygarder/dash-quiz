<template>
    <div class="dash-quiz" :class="{ 'sidebar-shifted': isSidebarOpen }">
        <div class="container">

            <header class="page-header">
                <div class="header-content">
                    <div class="icon-box">
                        <i class="fas fa-desktop"></i>
                    </div>

                    <div class="text-group">
                        <h2>Computer Systems Servicing</h2>
                        <p>Select a competency to begin your assessment</p>
                    </div>
                </div>
            </header>

            <div v-if="loading" class="loading">
                <div class="spinner"></div>
            </div>

            <template v-else>
                <section class="challenge-container">
                    <!-- EMPTY -->
                    <div v-if="quizzes.length === 0" class="empty">
                        No quizzes yet 📊
                    </div>

                    <div v-else class="quiz-grid">
                        <router-link v-for="quiz in quizzes" :key="quiz.id" :title=quiz.description
                            :to="{ name: 'quiz-start', params: { quiz_id: quiz.id } }" class="quiz-card">
                            <div class="card-inner">
                                <div class="icon-wrapper">
                                    <i class="fas fa-microchip"></i>
                                </div>

                                <div class="quiz-content">
                                    <h3>{{ quiz.description }}</h3>
                                    <div class="quiz-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-list-ol"></i>
                                            {{ quiz.total_questions || 10 }}
                                            <span>Q</span>
                                        </span>
                                        <span class="meta-item difficulty"
                                            :class="quiz.difficulty?.toLowerCase() || 'beginner'">
                                            <i class="fas fa-signal"></i>
                                            {{ quiz.difficulty || "Beginner" }}
                                        </span>
                                    </div>
                                </div>

                                <div class="arrow-indicator">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </section>
            </template>
        </div>
    </div>
</template>

<script setup>
import { useUser } from "@/composables/useUser"
import { ref, onMounted } from 'vue'
import axios from 'axios'

// 1. Receive sidebar state from Parent Layout to adjust margins
defineProps({
    isSidebarOpen: {
        type: Boolean,
        default: true
    }
})

const { fetchUser } = useUser()
const quizzes = ref([])
const loading = ref(false)

const fetchQuizzes = async () => {
    if (loading.value) return

    try {
        loading.value = true
        const { data } = await axios.get('/api/quizzes')
        quizzes.value = data.data
    } catch (err) {
        console.error('Failed to fetch quizzes:', err)
    } finally {
        loading.value = false;
    }
}

onMounted(async () => {
    await fetchUser()
    fetchQuizzes()
})
</script>

<style scoped>
/* ROOT */
.dash-quiz {
    width: 100%;
    height: auto;
    background: #f8fafc;
    padding: clamp(1rem, 2vw, 1.5rem);
    border-radius: 12px;
}

/* CONTAINER */
.container {
    width: 100%;
    max-width: 1100px;
    margin: 0 auto;
}

/* HEADER */
.page-header {
    margin-bottom: 1.5rem;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.icon-box {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, #4b3fc2, #6366f1);
    color: white;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.text-group h2 {
    font-size: clamp(1.2rem, 2vw, 1.5rem);
    font-weight: 800;
    color: #1e1b4b;
}

.text-group p {
    font-size: 0.85rem;
    color: #64748b;
}

/* ✅ GRID FIXED */
.quiz-grid {
    display: grid;
    gap: 14px;
    /* responsive auto layout */
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
}

.loading {
    display: flex;
    justify-content: center;
    padding: 30px;
}

/* CARD */
.quiz-card {
    text-decoration: none;
    color: inherit;
    padding: 14px 16px;
    height: 100px;
}

.card-inner {
    background: white;
    border-radius: 14px;
    display: flex;
    padding: 0 10px;
    align-items: center;
    gap: 12px;
    border: 1px solid #e2e8f0;
    transition: 0.2s ease;
    height: 100%;
}

.quiz-card:hover .card-inner {
    transform: translateY(-3px);
    border-color: #6366f1;
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.08);
}

/* ICON */
.icon-wrapper {
    width: 42px;
    height: 42px;
    background: #f5f3ff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6366f1;
    font-size: 1rem;
    flex-shrink: 0;
}

/* CONTENT */
.quiz-content {
    flex: 1;
    min-width: 0;
}

.quiz-content h3 {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;

    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* META */
.quiz-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.meta-item {
    font-size: 0.7rem;
    font-weight: 600;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* DIFFICULTY */
.difficulty.beginner {
    color: #10b981;
    background: #ecfdf5;
    padding: 2px 6px;
    border-radius: 6px;
}

.difficulty.intermediate {
    color: #f59e0b;
    background: #fffbeb;
    padding: 2px 6px;
    border-radius: 6px;
}

.difficulty.advanced {
    color: #ef4444;
    background: #fef2f2;
    padding: 2px 6px;
    border-radius: 6px;
}

.empty {
    text-align: center;
    padding: 20px;
    font-size: medium;
    color: #9ca3af;
}

/* ARROW */
.arrow-indicator {
    margin-left: auto;
    color: #cbd5f5;
    transition: 0.2s;
}

.quiz-card:hover .arrow-indicator {
    transform: translateX(3px);
    color: #6366f1;
}


.spinner {
    width: 35px;
    height: 35px;
    border: 4px solid #eee;
    border-top: 4px solid #6366f1;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* 📱 MOBILE */
@media (max-width: 500px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .icon-box {
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
    }

    .arrow-indicator {
        display: none;
    }
}
</style>