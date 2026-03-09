<template>
    <div class="dashboard-page">
        <SideBarComp :show="showSidebar" @close="closeSidebar" />

        <header>
            <div class="top-bar">
                <button class="menu-btn" @click="toggleSidebar">&#9776;</button>

                <div class="welcome-text">
                    <h2 v-if="user && user.first_name">Welcome, {{ user.first_name }}!</h2>
                    <h2 v-else>Loading...</h2>
                </div>

                <router-link to="/profile">
                    <img :src="avatar" alt="DP" class="user-avatar top-avatar" />
                </router-link>
            </div>
        </header>

        <main class="dashboard">
            <h3>Leaderboard (Top Scores)</h3>

            <table class="quiz-table leaderboard">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Dasher</th>
                        <th>Quiz Title</th>
                        <th>Best Score</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(leader, index) in leaders" :key="index" :class="{ you: leader.user_id === user.id }">
                        <td>{{ index + 1 }}</td>

                        <td>
                            <div class="dash-user">
                                <img :src="leader.profile_photo" class="dash-avatar" />
                                <span class="dash-name">
                                    {{ leader.name }}
                                    <span v-if="leader.user_id === user.id" class="you-tag">(You)</span>
                                </span>
                            </div>
                        </td>

                        <td>{{ leader.quiz_title }}</td>
                        <td>{{ leader.score }}</td>
                    </tr>

                    <tr v-if="leaders.length === 0">
                        <td colspan="4" class="no-score">No scores yet.</td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"
import SideBarComp from "@/components/SideBar.vue"
import { useSidebar } from "@/composables/useSidebar"
import { useUser } from "@/composables/useUser"

const { showSidebar, toggleSidebar, closeSidebar } = useSidebar()
const { user, fetchUser, avatar } = useUser()
const leaders = ref([])

// user data provided by composable; fetch on mounted below

const fetchLeaderboard = async () => {
    try {
        const { data } = await axios.get("/api/dashboard/leaderboard")
        // Check if data.leaders exists, otherwise use empty array
        leaders.value = data.leaders || []
    } catch (err) {
        console.error("Leaderboard fetch failed", err)
    }
}

onMounted(async () => {
    await fetchUser()
    fetchLeaderboard()
})
</script>

<style scoped>
.you {
    background-color: #e8f5e8; /* light green background */
    font-weight: bold;
}

.you-tag {
    color: #28a745; /* green color */
    font-weight: bold;
    font-size: 0.9em;
}

.dash-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 10px;
}
</style>
