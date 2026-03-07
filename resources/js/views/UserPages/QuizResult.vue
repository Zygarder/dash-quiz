<template>
    <div class="quiz-result-page">

        <header class="top-bar">
            <div class="menu-btn" @click="toggleSidebar">&#9776;</div>
            <router-link to="/profile">
                <img :src="avatar" alt="DP" class="user-avatar profile-img" />
            </router-link>
        </header>

        <main class="container">
            <div class="result-box">
                <h2>Your score: {{ score }} / {{ totalQuestions }}</h2>
                <p>Percentage: {{ percentage }}%</p>
                <button @click="goDashboard">Back to Dashboard</button>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useSidebar } from '@/composables/useSidebar'
import { useUser } from '@/composables/useUser'

const router = useRouter()
const route = useRoute()
const { showSidebar, toggleSidebar, closeSidebar } = useSidebar()
const { user, fetchUser, avatar } = useUser()

const score = ref(0)
const totalQuestions = ref(10)

const percentage = computed(() => {
    return totalQuestions.value > 0 ? Math.round((score.value / totalQuestions.value) * 100) : 0
})

const goDashboard = () => {
    router.replace('/dashboard')
}

onMounted(async () => {
    await fetchUser()
    try {
        console.log('QuizResult mounted, route.query:', route.query)
        // Get score and total from query parameters
        const queryScore = route.query.score
        const queryTotal = route.query.total

        console.log('queryScore:', queryScore, 'queryTotal:', queryTotal)

        if (queryScore !== undefined) {
            score.value = parseInt(queryScore) || 0
        }
        if (queryTotal !== undefined) {
            totalQuestions.value = parseInt(queryTotal) || 10
        }

        console.log('Parsed score:', score.value, 'total:', totalQuestions.value)
    } catch (error) {
        console.error('Error in QuizResult mounted hook:', error)
    }
})
</script>

<style scoped>
.result-box {
    margin: auto;
    padding: 20px;
    text-align: center;
    max-width: 400px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.result-box h2 {
    color: #4b3fc2;
    margin-bottom: 10px;
}

.result-box p {
    color: #666;
    margin-bottom: 20px;
}

.result-box button {
    padding: 12px 24px;
    background: #4b3fc2;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s;
}

.result-box button:hover {
    background: #3a2d99;
}

/* Header and sidebar styles */
.top-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 20px;
    background: #fff;
    border-bottom: 1px solid #eee;
}

.menu-btn {
    cursor: pointer;
    font-size: 24px;
    background: none;
    border: none;
    color: #333;
}

.profile-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #ccc;
}

.quiz-result-page {
    min-height: 100vh;
    background: #f5f5f5;
}
</style>