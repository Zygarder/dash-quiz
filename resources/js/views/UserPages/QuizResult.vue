<template>
  <div class="quiz-result-page">
    <header class="top-bar">
      <div class="nav-content">
        <div class="brand">
          <span class="brand-text">Assessment Complete</span>
        </div>
        <router-link to="/profile" class="profile-link">
          <img :src="profileImageUrl" alt="Profile" class="user-avatar" />
        </router-link>
      </div>
    </header>

    <main class="container">
      <div class="result-card">
        <div class="celebration-icon">🎉</div>

        <div class="score-summary">
          <h2 class="percentage-display">{{ percentage }}%</h2>
          <p class="score-text">You scored <strong>{{ score }}</strong> out of <strong>{{ totalQuestions }}</strong></p>
        </div>

        <div class="visual-track">
          <div class="track-fill" :style="{ width: percentage + '%' }"></div>
        </div>

        <div class="feedback-msg">
          <p v-if="percentage >= 75">Excellent work! You have a solid grasp of this COC.</p>
          <p v-else-if="percentage >= 50">Good effort! A little more review and you'll be an expert.</p>
          <p v-else>Keep practicing. Consistency is the key to mastery!</p>
        </div>

        <div class="action-grid">
          <router-link to="/home" class="btn-primary">
            Go to Dashboard
          </router-link>
          <button @click="reTake" class="btn-outline">
            Try Again
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useUser } from '@/composables/useUser'

const route = useRoute()
const router = useRouter()
const { userAvatar, fetchUser } = useUser()

const score = ref(0)
const totalQuestions = ref(10)

// Compute percentage but clamp between 0 and 100
const percentage = computed(() => {
  if (totalQuestions.value <= 0) return 0
  const percent = Math.round((score.value / totalQuestions.value) * 100)
  return percent > 100 ? 100 : percent
})

const profileImageUrl = computed(() => userAvatar.value)

onMounted(async () => {
  await fetchUser()
  try {
    const queryScore = parseInt(route.query.score) || 0
    const queryTotal = parseInt(route.query.total) || 10

    // Make sure totalQuestions is at least 1
    totalQuestions.value = queryTotal > 0 ? queryTotal : 10

    // Clamp score to 0..totalQuestions
    score.value = (queryScore < 0) ? 0 : queryScore
    if (score.value > totalQuestions.value) score.value = totalQuestions.value



  } catch (error) {
    console.error('Error in QuizResult mounted hook:', error)
  }
})

// retae
const reTake = computed(() => {
  router.replace('/quiz/' + route.query.id)
})

</script>

<style scoped>
:root {
  --primary: #6366f1;
  --text-main: #1e293b;
  --text-muted: #64748b;
  --bg-subtle: #f8fafc;
}

.quiz-result-page {
  min-height: 100vh;
  background: #f8fafc;
  font-family: "Inter", sans-serif;
  display: flex;
  flex-direction: column;
}

/* === Minimal Top Bar === */
.top-bar {
  background: #ffffff;
  border-bottom: 1px solid #e2e8f0;
  padding: 0.75rem 0;
}

.nav-content {
  max-width: 800px;
  margin: 0 auto;
  padding: 0 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.brand-text {
  font-weight: 700;
  font-size: 0.9rem;
  color: var(--text-main);
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 2px solid var(--primary);
  padding: 2px;
  object-fit: cover;
}

/* === Main Container === */
.container {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem 1rem;
}

/* === Result Card === */
.result-card {
  background: #ffffff;
  border-radius: 24px;
  padding: 3rem 2rem;
  text-align: center;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
  max-width: 450px;
  width: 100%;
  border: 1px solid #f1f5f9;
}

.celebration-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.percentage-display {
  font-size: 4rem;
  font-weight: 900;
  color: var(--text-main);
  margin: 0;
  letter-spacing: -2px;
}

.score-text {
  color: var(--text-muted);
  font-size: 1.1rem;
  margin-top: -5px;
}

/* Visual Bar */
.visual-track {
  height: 8px;
  background: #f1f5f9;
  border-radius: 10px;
  margin: 2rem 0;
  overflow: hidden;
}

.track-fill {
  height: 100%;
  background: var(--primary);
  border-radius: 10px;
  transition: width 1s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.feedback-msg {
  color: var(--text-muted);
  font-style: italic;
  margin-bottom: 2.5rem;
  font-size: 0.95rem;
}

/* === Action Buttons === */
.action-grid {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.btn-primary {
  background: var(--text-main);
  color: #fff;
  text-decoration: none;
  padding: 1rem;
  border-radius: 14px;
  font-weight: 700;
  transition: transform 0.2s;
}

.btn-outline {
  background: transparent;
  color: var(--text-muted);
  border: 2px solid #e2e8f0;
  padding: 1rem;
  border-radius: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-primary:hover,
.btn-outline:hover {
  transform: translateY(-2px);
}

.btn-outline:hover {
  border-color: var(--primary);
  color: var(--primary);
}
</style>