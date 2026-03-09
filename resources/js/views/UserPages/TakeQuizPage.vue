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
/* ===== MAIN CONTAINER ===== */
.take-quiz-page .container {
    max-width: 700px;
    margin: 40px auto;
    padding: 20px;
    min-height: 70vh;
    display: flex;
    flex-direction: column;
    gap: 25px;
    font-family: 'Inter', sans-serif;
}

/* ===== QUIZ HEADER ===== */
.quiz-header {
    text-align: center;
    margin-bottom: 15px;
}

.quiz-header h2 {
    color: #4b3fc2;
    font-weight: 800;
    font-size: 2rem;
    margin-bottom: 5px;
}

.quiz-stats {
    color: #666;
    font-size: 0.9rem;
}

/* ===== PROGRESS BAR ===== */
.progress-wrapper {
    width: 100%;
    background: #eee;
    height: 10px;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 20px;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #4b3fc2, #6c5ce7);
    width: 0;
    border-radius: 8px;
    transition: width 0.4s ease;
}

/* ===== QUESTION BOX ===== */
.question-box {
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.question-box h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2d2d2d;
    border: 1px solid #000;
    padding: 10px;
    margin-bottom: 5px;
    text-align: center;
}

/* ===== OPTIONS AS BUTTONS ===== */
.option {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 10px 0;
}

.option input[type="radio"] {
    display: none;
}

.option label {
    display: block;
    width: 100%;
    padding: 15px 20px;
    border-radius: 12px;
    border: 2px solid #4b3fc2;
    background: #fff;
    color: #4b3fc2;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
}

.option input[type="radio"]:checked+label {
    background: #4b3fc2;
    color: #fff;
    box-shadow: 0 8px 15px rgba(75, 63, 194, 0.3);
    transform: translateY(-2px);
}

.option label:hover {
    background: #f0f0ff;
}

/* ===== SUBMIT BUTTON CENTERED ===== */
.submit-btn {
    margin: 0 auto;
    display: block;
    background-color: #4b3fc2;
    color: #fff;
    border: none;
    padding: 14px 30px;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.2s ease;
}

.submit-btn:hover:not(:disabled) {
    background-color: #3a2d99;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(75, 63, 194, 0.3);
}

.submit-btn:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

/* ===== LOADING STATE ===== */
.loading-container {
    text-align: center;
    padding: 50px 20px;
    font-size: 1.1rem;
    color: #666;
}

/* ===== ERROR STATE ===== */
.error-container {
    background: #fff0f0;
    border: 1px solid #f4cccc;
    border-radius: 16px;
    padding: 40px 25px;
    text-align: center;
}

.error-container h2 {
    color: #e74c3c;
    font-weight: 700;
    margin-bottom: 12px;
}

.error-container p {
    color: #a94442;
    margin-bottom: 20px;
    font-size: 1rem;
}

.back-btn {
    background-color: #4b3fc2;
    color: #fff;
    border: none;
    padding: 12px 28px;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.2s ease;
}

.back-btn:hover {
    background-color: #3a2d99;
    transform: translateY(-1px);
}

/* ===== MOBILE ===== */
@media (max-width: 600px) {
    .question-box h3 {
        font-size: 1.1rem;
        
    }

    .option label {
        padding: 12px 16px;
        font-size: 0.95rem;
    }

    .submit-btn {
        padding: 12px 24px;
        font-size: 0.95rem;
    }
}
</style>