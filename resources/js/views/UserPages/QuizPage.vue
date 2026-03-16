<template>
  <div class="dash-quiz">
    <TopBar />

    <main class="container">
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

      <section class="challenge-container">
        <div v-if="quizzes.length === 0" class="quiz-container">
          <div v-for="i in 3" :key="i" class="skeleton-card"></div>
        </div>

        <div v-else class="quiz-container">
          <router-link v-for="quiz in quizzes" :key="quiz.id" :to="{ name: 'quiz-start', params: { quiz_id: quiz.id } }"
            class="quiz-card">

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
                  </span>
                  <span class="meta-item difficulty" :class="quiz.difficulty?.toLowerCase()">
                    <i class="fas fa-chart-line"></i>
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
    </main>
  </div>
</template>

<script setup>
import { useUser } from "@/composables/useUser"
import { ref, onMounted } from 'vue'
import axios from 'axios'
import TopBar from "@/components/UserSide/TopBar.vue"

const { fetchUser } = useUser()

// quizzes data
const props = defineProps({})

const quizzes = ref([]) // will be the array/object of quizzes

const fetchQuizzes = async () => {
  try {
    const { data } = await axios.get('/api/quizzes')

    // assign the fetched data to quizzes
    quizzes.value = data.results

  } catch (err) {
    console.error('Failed to fetch quizzes:', err)
  }
}

onMounted(async () => {
  await fetchUser()
  fetchQuizzes()
})

</script>


<style scoped>
:target {
  --primary: #4b3fc2;
  --primary-light: #6358db;
  --bg-soft: #f8faff;
}

.dash-quiz {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #f0f2f5;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.container {
  max-width: 1100px;
  margin: 0 auto;
  width: 100%;
  padding: 40px 20px;
}

/* === HEADER DESIGN === */
.page-header {
  margin-bottom: 40px;
  animation: slideDown 0.6s ease-out;
}

.header-content {
  display: flex;
  align-items: center;
  gap: 20px;
}

.icon-box {
  background: #4b3fc2;
  color: white;
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.4rem;
  box-shadow: 0 4px 12px rgba(75, 63, 194, 0.3);
}

.text-group h2 {
  font-size: 1.75rem;
  font-weight: 800;
  color: #1a1a1a;
  margin: 0;
}

.text-group p {
  color: #666;
  margin: 4px 0 0;
}

/* === QUIZ GRID === */
.quiz-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 24px;
}

.quiz-card {
  text-decoration: none;
  color: inherit;
  perspective: 1000px;
}

.card-inner {
  background: white;
  border-radius: 20px;
  padding: 24px;
  display: flex;
  align-items: center;
  gap: 18px;
  position: relative;
  border: 1px solid rgba(0, 0, 0, 0.05);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  overflow: hidden;
}

.quiz-card:hover .card-inner {
  transform: translateY(-8px);
  box-shadow: 0 15px 30px rgba(75, 63, 194, 0.12);
  border-color: #4b3fc2;
}

/* ICON WRAPPER */
.icon-wrapper {
  width: 56px;
  height: 56px;
  background: #f0eeff;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: #4b3fc2;
  transition: 0.3s;
}

.quiz-card:hover .icon-wrapper {
  background: #4b3fc2;
  color: white;
  transform: scale(1.1) rotate(-5deg);
}

/* CONTENT */
.quiz-content h3 {
  font-size: 1.1rem;
  margin: 0 0 8px 0;
  color: #2d2d2d;
  line-height: 1.3;
}

.quiz-meta {
  display: flex;
  gap: 12px;
}

.meta-item {
  font-size: 0.8rem;
  font-weight: 600;
  color: #7a7a7a;
  display: flex;
  align-items: center;
  gap: 5px;
}

.difficulty.beginner {
  color: #27ae60;
}

.difficulty.intermediate {
  color: #f39c12;
}

/* ARROW */
.arrow-indicator {
  margin-left: auto;
  color: #ccc;
  transition: 0.3s;
}

.quiz-card:hover .arrow-indicator {
  transform: translateX(5px);
  color: #4b3fc2;
}

/* SKELETON LOADER */
.skeleton-card {
  height: 110px;
  background: linear-gradient(90deg, #eee 25%, #f5f5f5 50%, #eee 75%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
  border-radius: 20px;
}

@keyframes shimmer {
  0% {
    background-position: 200% 0;
  }

  100% {
    background-position: -200% 0;
  }
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 600px) {
  .quiz-container {
    grid-template-columns: 1fr;
  }

  .header-content {
    flex-direction: column;
    text-align: center;
  }

  .icon-box {
    margin: 0 auto;
  }
}
</style>