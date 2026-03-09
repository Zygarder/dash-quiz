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
/* === PAGE CONTAINER === */
.quiz-result-page {
    min-height: 100vh;
    background: #f5f5f8;
    font-family: "Inter", sans-serif;
    display: flex;
    flex-direction: column;
}

/* === TOP BAR === */
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #4b3fc2;
    padding: 12px 20px;
    color: #fff;
    font-weight: 700;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.menu-btn {
    cursor: pointer;
    font-size: 1.4rem;
}

.user-avatar.profile-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #fff;
    object-fit: cover;
}

/* === MAIN CONTAINER === */
.container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem 1rem;
}

/* === RESULT BOX === */
.result-box {
    background: #fff;
    border-radius: 16px;
    padding: 40px 30px;
    text-align: center;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    max-width: 400px;
    width: 100%;
    animation: fadeIn 0.3s ease;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.result-box h2 {
    font-size: 2rem;
    font-weight: 800;
    color: #4b3fc2;
}

.result-box p {
    font-size: 1.2rem;
    color: #333;
}

/* === BUTTON === */
.result-box button {
    background-color: #4b3fc2;
    color: #fff;
    border: none;
    padding: 12px 24px;
    font-weight: 700;
    font-size: 1rem;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.result-box button:hover {
    background-color: #3a2d99;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(75, 63, 194, 0.3);
}

/* === RESPONSIVE === */
@media (max-width: 500px) {
    .result-box {
        padding: 30px 20px;
    }

    .result-box h2 {
        font-size: 1.6rem;
    }

    .result-box p {
        font-size: 1rem;
    }

    .top-bar {
        padding: 10px 15px;
    }
}

/* === ANIMATION === */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(6px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>