<template>
    <div class="dash-quiz">
        <TopBar />

        <main class="container">
            <section class="challenge-container">
                <h2>Computer Systems Servicing</h2>

                <div class="quiz-container">
                    <router-link v-for="quiz in quizzes" :key="quiz.id"
                        :to="{ name: 'quiz-start', params: { quiz_id: quiz.id } }" class="quiz-choice">
                        <div class="competency-btn">
                            {{ quiz.description }}
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
import TopBar from "../../components/TopBar.vue"

const { user, fetchUser, avatar } = useUser()

// quizzes data
const props = defineProps({})


const quizzes = ref([])

const fetchQuizzes = async () => {
    try {
        const { data } = await axios.get('/api/quizzes')
        quizzes.value = data.quizzes
    } catch (e) {
        console.error('Failed to fetch quizzes:', e)
    }
}

onMounted(async () => {
    await fetchUser()
    fetchQuizzes()
})

</script>
<style scoped>
/* === DASH QUIZ LAYOUT === */
.dash-quiz {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #f5f5f8;
  
}

/* === QUIZ SECTION CONTAINER === */
.challenge-container {
  max-width: 1000px;
  margin: 40px auto;
  padding: 0 20px;
  text-align: center;
}

.challenge-container h2 {
  font-size: 1.8rem;
  color: #2d2d2d;
  margin-bottom: 30px;
  font-weight: 800;
  position: relative;
  display: inline-block;
  animation: fadeIn 1s ease;
}

/* Heading underline accent */
.challenge-container h2::after {
  content: '';
  display: block;
  width: 60px;
  height: 4px;
  background: #4b3fc2;
  margin: 10px auto 0;
  border-radius: 2px;
}

/* === QUIZ GRID === */
.quiz-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

/* === QUIZ LINK WRAPPER === */
.quiz-choice {
  text-decoration: none;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.quiz-choice:hover {
  transform: translateY(-3px);
}

/* === QUIZ CARD BUTTON === */
.competency-btn {
  background-color: #fff;
  color: #3f2f87;
  border: 2px solid #3f2f87;
  padding: 30px 20px;
  border-radius: 14px;
  font-size: 1.1rem;
  font-weight: 700;
  min-height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.quiz-choice:hover .competency-btn {
  background-color: #3f2f87;
  color: #fff;
  box-shadow: 0 10px 20px rgba(63, 47, 135, 0.2);
  transform: translateY(-3px);
}

/* === MOBILE ADJUSTMENTS === */
@media (max-width: 600px) {
  .quiz-container {
    grid-template-columns: 1fr;
  }

  .challenge-container h2 {
    font-size: 1.4rem;
  }

  .competency-btn {
    padding: 25px 15px;
    font-size: 1rem;
  }
}

/* === FADE IN ANIMATION === */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>