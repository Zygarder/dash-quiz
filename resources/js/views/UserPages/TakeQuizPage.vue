<template>
    <div class="take-quiz-page">
        <main class="container">
            <div v-if="loading" class="loading-container">
                <p>Loading quiz...</p>
            </div>
            <div v-else-if="error" class="error-container">
                <h2>Quiz Not Found</h2>
                <p>{{ error }}</p>
                <button @click="goBackToQuizzes" class="back-btn">Back to Quizzes</button>
            </div>
            <div v-else-if="questions.length" class="quiz-container">
                <div class="quiz-header">
                    <h2>{{ quiz.title }}</h2>
                    <div class="quiz-stats">
                        <span>Question {{ currentIndex + 1 }} of {{ questions.length }}</span>
                    </div>
                </div>

                <div class="progress-wrapper">
                    <div class="progress-bar" :style="{ width: progress + '%' }"></div>
                </div>

                <!-- Option Statistics -->
                <div class="option-stats">
                    <h4>Your Choice Pattern:</h4>
                    <div class="stats-grid">
                        <div v-for="(count, label) in optionStats" :key="label" class="stat-item">
                            <span class="stat-label">{{ label }}:</span>
                            <span class="stat-count">{{ count }}</span>
                            <div class="stat-bar">
                                <div class="stat-fill" :style="{ width: getOptionPercentage(label) + '%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="question-box">
                    <h3>Q{{ currentQuestion.question_number }}. {{ currentQuestion.text }}</h3>
                    <form @submit.prevent="submitAnswer">
                        <div v-for="opt in currentQuestion.options" :key="opt.id" class="option">
                            <input type="radio" :id="opt.id" v-model="selectedAnswer" :value="opt.id" />
                            <label :for="opt.id">
                                <span class="option-label">{{ opt.label }}.</span>
                                {{ opt.text }}
                            </label>
                        </div>
                        <button class="submit-btn" :disabled="!selectedAnswer">Next</button>
                    </form>
                </div>
            </div>
            <div v-else>
                <p>No questions available for this quiz.</p>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const quiz = ref({ title: '', total_questions: 0 })
const questions = ref([])
const currentIndex = ref(0)
const selectedAnswer = ref(null)
const profileImage = ref('/images/profiles/person.jpg')
const score = ref(0)
const loading = ref(true)
const error = ref(null)
const optionStats = ref({
    'A': 0,
    'B': 0,
    'C': 0,
    'D': 0
})

const currentQuestion = computed(() => questions.value[currentIndex.value] || {})
const progress = computed(() => ((currentIndex.value) / questions.value.length) * 100)

const getOptionPercentage = (label) => {
  const total = Object.values(optionStats.value).reduce((sum, count) => sum + count, 0)
  return total > 0 ? (optionStats.value[label] / total) * 100 : 0
}

const fetchQuiz = async () => {
    const quizId = route.params.quiz_id

    // Validate quiz ID
    if (!quizId || isNaN(quizId)) {
        error.value = 'Invalid quiz ID provided.'
        loading.value = false
        return
    }

    loading.value = true
    error.value = null

    try {
        const { data } = await axios.get('/api/quiz', { params: { quiz_id: quizId } })
        quiz.value = data.quiz
        questions.value = data.quiz.questions

        if (!questions.value.length) {
            error.value = 'This quiz has no questions available.'
        }
    } catch (e) {
        console.error('Failed to fetch quiz:', e)
        if (e.response?.status === 422) {
            error.value = 'Quiz not found or no longer available.'
        } else {
            error.value = 'Failed to load quiz. Please try again.'
        }
    } finally {
        loading.value = false
    }
}

const submitAnswer = async () => {
    if (selectedAnswer.value === null) return

    try {
        const { data } = await axios.post('/api/quiz/answer', {
            question_id: currentQuestion.value.id,
            answer_id: selectedAnswer.value
        })

        // Track option statistics
        const selectedOption = currentQuestion.value.options.find(opt => opt.id === selectedAnswer.value)
        if (selectedOption) {
            optionStats.value[selectedOption.label]++
        }

        if (data.correct) {
            score.value++
        }

        selectedAnswer.value = null
        currentIndex.value++

        if (currentIndex.value >= questions.value.length) {
            // Quiz complete, submit result
            await submitQuizResult()
        }
    } catch (e) {
        console.error('Failed to submit answer:', e)
    }
}

const submitQuizResult = async () => {
    try {
        await axios.post('/api/quiz/result', {
            quiz_id: route.params.quiz_id,
            score: score.value
        })
        // Redirect to result page with score and total questions
        router.push({
            path: '/quiz-result',
            query: {
                score: score.value,
                total: questions.value.length
            }
        })
    } catch (e) {
        console.error('Failed to submit quiz result:', e)
    }
}

const goBackToQuizzes = () => {
    router.push('/quizzes')
}

onMounted(() => {
    fetchQuiz()
})
</script>

<style scoped>
/* include similar styles from blade for question layout */
.quiz-container {
    max-width: 600px;
    margin: auto;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
}

.progress-wrapper {
    background: #eee;
    height: 8px;
    border-radius: 4px;
    margin-bottom: 15px;
}

.progress-bar {
    height: 100%;
    background: #4b3fc2;
    transition: width 0.3s;
}

.question-box h3 {
    margin-bottom: 10px;
}

.option {
    margin: 8px 0;
}

.submit-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.quiz-header {
    text-align: center;
    margin-bottom: 20px;
}

.quiz-header h2 {
    color: #4b3fc2;
    margin-bottom: 5px;
}

.quiz-stats {
    color: #666;
    font-size: 14px;
}

.option-label {
    font-weight: bold;
    color: #4b3fc2;
    margin-right: 8px;
}

.option-stats {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.option-stats h4 {
    margin-bottom: 10px;
    color: #333;
    text-align: center;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.stat-item {
    text-align: center;
}

.stat-label {
    font-weight: bold;
    color: #4b3fc2;
    display: block;
    margin-bottom: 2px;
}

.stat-count {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

.stat-bar {
    height: 4px;
    background: #eee;
    border-radius: 2px;
    overflow: hidden;
}

.stat-fill {
    height: 100%;
    background: linear-gradient(90deg, #4b3fc2, #6c5ce7);
    transition: width 0.3s ease;
}

.loading-container,
.error-container {
    max-width: 600px;
    margin: auto;
    padding: 40px 20px;
    text-align: center;
    background: #fff;
    border-radius: 12px;
}

.error-container h2 {
    color: #e74c3c;
    margin-bottom: 10px;
}

.back-btn {
    margin-top: 20px;
    padding: 10px 20px;
    background: #4b3fc2;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.back-btn:hover {
    background: #3a2d99;
}
</style>