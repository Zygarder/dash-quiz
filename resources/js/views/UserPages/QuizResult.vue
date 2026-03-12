<template>
  <div class="quiz-result-page">
    <!-- Top Bar -->
    <header class="top-bar">
      <div class="menu-btn" @click="toggleSidebar">&#9776;</div>

      <router-link to="/profile">
        <img :src="AVATAR" alt="DP" class="user-avatar profile-img" />
      </router-link>
    </header>

    <!-- Main Content -->
    <main class="container">
      <div class="result-box">
        <h2>Your score: {{ score }} / {{ totalQuestions }}</h2>
        <p>Percentage: {{ percentage }}%</p>
        <!-- Fixed button -->
        <router-link to="/home">
          <button type="button">Go To Dashboard</button>
        </router-link>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useUser } from '@/composables/useUser'

const router = useRouter()
const route = useRoute()
const { fetchUser } = useUser()

const score = ref(0)
const totalQuestions = ref(10)

// Compute percentage but clamp between 0 and 100
const percentage = computed(() => {
  if (totalQuestions.value <= 0) return 0
  const pct = Math.round((score.value / totalQuestions.value) * 100)
  return pct > 100 ? 100 : pct
})

onMounted(async () => {
  await fetchUser()
  try {
    const queryScore = parseInt(route.query.score) || 0
    const queryTotal = parseInt(route.query.total) || 10

    // Make sure totalQuestions is at least 1
    totalQuestions.value = queryTotal > 0 ? queryTotal : 10

    // Clamp score to 0..totalQuestions
    score.value = queryScore < 0 ? 0 : queryScore
    if (score.value > totalQuestions.value) score.value = totalQuestions.value

  } catch (error) {
    console.error('Error in QuizResult mounted hook:', error)
  }
})
</script>

<style scoped>
.quiz-result-page {
  min-height: 100vh;
  background: #f5f5f8;
  font-family: "Inter", sans-serif;
  display: flex;
  flex-direction: column;
}

/* === Top Bar === */
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

/* === Main Container === */
.container {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem 1rem;
}

/* === Result Box === */
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

/* === Button === */
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

/* === Responsive === */
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

/* === Fade In Animation === */
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