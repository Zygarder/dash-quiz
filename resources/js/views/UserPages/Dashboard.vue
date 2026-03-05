<template>
    <div class="dashboard-page">
        <SideBarComp :show="showSidebar" @close="closeSidebar" />
        <!-- Top Bar -->
        <header>
            <div class="top-bar">
                <button class="menu-btn" @click="toggleSidebar">&#9776;</button>

                <div>
                    <h2>Welcome {{ user.first_name }}!</h2>
                </div>

                <router-link to="/profile">
                    <img :src="user.profile_photo" alt="DP" class="top-avatar" />
                </router-link>
            </div>
        </header>



        <!-- Main Content -->
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
                    <tr v-for="(leader, index) in leaders" :key="leader.user_id"
                        :class="{ you: leader.user_id === user.id }">
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

const { showSidebar, toggleSidebar, closeSidebar } = useSidebar()
const leaders = ref([])
const user = ref({})

const fetchProfile = async () => {
    try {
        const { data } = await axios.get("profile")
        user.value = data.data
    } catch (err) {
        console.error(err)
    }
}

const fetchLeaderboard = async () => {
    try {
        const { data } = await axios.get("dashboard/leaderboard")
        leaders.value = data.leaders
    } catch (err) {
        console.error(err)
    }
}

onMounted(() => {
    fetchProfile()
    fetchLeaderboard()
})
</script>

<style scoped>
.leaderboard {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
    margin-top: 10px;
}

.leaderboard th {
    background: #222;
    color: #fff;
    padding: 10px;
}

.leaderboard td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

.dash-user {
    display: flex;
    align-items: center;
    gap: 10px;
}

.dash-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ccc;
}

.top-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #ccc;
}

.dash-name {
    font-weight: bold;
    color: #333;
}

.you-tag {
    color: #007bff;
    font-size: 0.9em;
}

.leaderboard tr:hover {
    background: #f5faff;
}

.leaderboard tr.you {
    background: #e8f4ff !important;
    border-left: 4px solid #007bff;
}

.no-score {
    text-align: center;
    font-style: italic;
    color: gray;
}
</style>