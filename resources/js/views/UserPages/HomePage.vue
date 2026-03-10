<template>
    <div class="dashboard-page">
        <TopBar />

        <main class="dashboard">
            <h3>Leaderboard (Top Scores)</h3>

            <!-- Current User Rank -->
            <div v-if="userRank" class="your-rank">
                Your Rank: <strong>#{{ userRank }}</strong>
            </div>

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
                    <tr v-for="(leader, index) in leaders" :key="leader.user_id" :class="[
                        { you: leader.user_id === USER?.id },
                        index === 0 ? 'first-place' : ''
                    ]">

                        <!-- Rank -->
                        <td>
                            <span v-if="index === 0">🥇</span>
                            <span v-else-if="index === 1">🥈</span>
                            <span v-else-if="index === 2">🥉</span>
                            <span v-else class="rank-badge no-rank">
                                {{ index + 1 }}
                            </span>
                        </td>

                        <!-- User -->
                        <td>
                            <div class="dash-user">
                                <img :src="`/storage/images/profiles/${leader.profile_photo || 'default.png'}`"
                                    class="dash-avatar" @error="setDefaultImage"  draggable="false"/>

                                <span class="dash-name">
                                    <span v-if="index === 0">👑</span>
                                    {{ leader.name }}

                                    <span v-if="leader.user_id === USER?.id" class="you-tag">
                                        (You)
                                    </span>
                                </span>
                            </div>
                        </td>

                        <!-- Quiz -->
                        <td>{{ leader.quiz_title }}</td>

                        <!-- Score -->
                        <td :class="{ perfect: leader.score === 10 }">
                            <div class="score-bar">
                                <div class="score-fill" :style="{ width: ((leader.score / 10) * 100) + '%' }">
                                </div>
                            </div>

                            <span class="score-text">
                                {{ leader.score }} / 10
                            </span>
                        </td>
                    </tr>

                    <tr v-if="leaders.length === 0">
                        <td colspan="4" class="no-score">
                            No scores yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue"
import axios from "axios"
import TopBar from "../../components/TopBar.vue"
import { useUser, } from "../../composables/useUser.js"

const { USER, fetchUser } = useUser()

const leaders = ref([])



const fetchLeaderboard = async () => {
    try {
        const { data } = await axios.get("/api/dashboard/leaderboard")
        leaders.value = data.results || []
        console.log(USER.value)
    } catch (err) {
        console.error("Leaderboard fetch failed", err)
    }
}

const userRank = computed(() => {
    const index = leaders.value.findIndex(
        l => l.user_id === USER.value?.id
    )

    return index !== -1 ? index + 1 : null
})

const setDefaultImage = (event) => {
    event.target.src = "/storage/images/profiles/default.png"
}

onMounted(async () => {
    await fetchUser()
    await fetchLeaderboard()
})
</script>

<style scoped>
/* === MAIN DASHBOARD === */
.dashboard {
    max-width: 900px;
    margin: 2rem auto;
    padding: 0 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    animation: fadeIn 0.3s ease;
}

/* === PAGE TITLE === */
.dashboard h3 {
    font-size: 1.8rem;
    font-weight: 800;
    color: #4b3fc2;
    text-align: center;
}

/* === USER RANK DISPLAY === */
.your-rank {
    text-align: center;
    font-weight: 700;
    color: #4b3fc2;
}

/* === TABLE === */
.quiz-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 6px;
}

.quiz-table thead th {
    background-color: #4b3fc2;
    user-select: none;
    color: #fff;
    font-weight: 600;
    padding: 12px 15px;
    text-align: center;
}

.quiz-table tbody tr {
    background: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: all 0.25s ease;
}

.quiz-table tbody tr:hover {
    transform: translateY(-2px);
}

/* === FIRST PLACE === */
.first-place {
    background: linear-gradient(135deg, #fff9c4, #fff176);
    box-shadow: 0 0 20px rgba(255, 223, 0, 0.4);
    font-weight: bold;
}

/* === CURRENT USER === */
.you {
    background-color: #e8f5e8;
    font-weight: 700;
    border-left: 4px solid #28a745;
}

/* === RANK BADGE === */
.rank-badge {
    display: inline-block;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    font-size: 0.85rem;
    text-align: center;
    line-height: 24px;
    font-weight: 700;
}

.no-rank {
    color: black;
}

/* === USER === */
.dash-user {
    display: flex;
    align-items: center;
    gap: 10px;
}

.dash-avatar {
    user-select: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ddd;
}

.dash-name {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

.dash-name span{
    margin-top: -5px;
}

.you-tag {
    color: #28a745;
    font-weight: bold;
    font-size: 0.8rem;
    padding-top:8.5px;
}

/* === SCORE BAR === */
.score-bar {
    height: 6px;
    background: #eee;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 4px;
}

.score-fill {
    height: 100%;
    background: linear-gradient(90deg, #4b3fc2, #6c5ce7);
    border-radius: 4px;
    animation: growBar 0.8s ease forwards;
}

.score-text {
    font-size: 0.85rem;
    color: #333;
}

.perfect {
    color: #28a745;
    font-weight: 800;
}

/* === EMPTY === */
.no-score {
    text-align: center;
    color: #888;
    font-style: italic;
    padding: 1rem 0;
}

/* === RESPONSIVE === */
@media (max-width:700px) {

    .quiz-table thead th,
    .quiz-table tbody td {
        padding: 10px 8px;
        font-size: 0.85rem;
    }

    .dash-avatar {
        width: 28px;
        height: 28px;
    }

}

/* === ANIMATIONS === */
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

@keyframes growBar {
    from {
        width: 0;
    }
}
</style>