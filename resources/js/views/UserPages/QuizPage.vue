<template>
    <div class="dash-quiz">
        <SideBarComp :show="showSidebar" @close="closeSidebar" />

        <header class="top-bar">
            <div class="menu-btn" @click="toggleSidebar">&#9776;</div>
            <p>CHOOSE YOUR QUIZ</p>

            <router-link to="/profile">
                <img :src="avatar" alt="DP" class="user-avatar profile-img">
            </router-link>
        </header>

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
import SideBarComp from "../../components/SideBar.vue"
import { useSidebar } from "@/composables/useSidebar"
import { useUser } from "@/composables/useUser"
import { ref, onMounted } from 'vue'
import axios from 'axios'

const { showSidebar, toggleSidebar, closeSidebar } = useSidebar()
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
/* Scoped styles replace your <style> block and external CSS imports */
.quiz-choice {
    display: inline-block;
    margin: 10px;
    text-decoration: none;
}

.quiz-container {
    text-align: center;
}

.top-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    /* Add other top-bar styles from your style.css here */
}

.profile-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #ccc;
}

.menu-btn {
    cursor: pointer;
    font-size: 24px;
}
</style>