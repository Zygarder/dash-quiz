<template>
    <div class="leaderboard">

        <!-- HEADER -->
        <div class="leaderboard-header">
            <div>
                <h1>Leaderboard</h1>
                <p class="sub">{{ leaderboard.length }} participants</p>
            </div>

            <div v-if="userPosition" class="you-pill">
                <span>#{{ userPosition }}</span>
                <small>You</small>
            </div>
        </div>

        <!-- LIST -->
        <div class="leaderboard-list">

            <div v-for="(user, index) in leaderboard" :key="user.id" class="leader-item"
                :class="[{ 'is-you': user.isYou }, index < 3 ? 'top' : '']">

                <!-- RANK -->
                <div class="rank">
                    <span v-if="index < 3" class="medal">
                        {{ ['🥇', '🥈', '🥉'][index] }}
                    </span>
                    <span v-else>{{ index + 1 }}</span>
                </div>

                <!-- USER -->
                <div class="user">
                    <img :src="`/storage/images/profiles/${user.profile_photo || 'default.png'} `" alt="user-image"
                        class="avatar">
                    <div class="info">
                        <span class="name">{{ user.name }}</span>
                        <span class="quiz">{{ user.quiz_title }}</span>
                    </div>
                </div>

                <!-- SCORE -->
                <div class="score">
                    <div class="bar">
                        <div class="fill" :style="{ width: scoreWidth(user.score) }"></div>
                    </div>
                    <span>{{ user.score }}/10</span>
                </div>

            </div>

            <!-- EMPTY -->
            <div v-if="!leaderboard.length" class="empty">
                <p>No rankings yet 📊</p>
            </div>

        </div>
    </div>
</template>

<script setup>
import axios from 'axios'
import { computed, onMounted, ref } from 'vue'

const leaderboard = ref([])
let isLoading = false

const userPosition = computed(() => {
    const you = leaderboard.value.find(u => u.isYou)
    return you ? leaderboard.value.indexOf(you) + 1 : null
})

const scoreWidth = (score) => `${(score / 10) * 100}%`

// fetch leaderboard data
const getLeaderBoard = async () => {
    await axios.get('sanctum/cookie');

    isLoading = true;
    try {
        const { data } = await axios.get('/api/dashboard/leaderboard')
        leaderboard.value = data.data;
        console.log(leaderboard.value)
    } catch (error) {
        console.error(error.message)
    } finally {
        isLoading = false
    }
}

onMounted(async () => {
    await getLeaderBoard();
})
</script>

<style scoped>
.leaderboard {
    width: 100%;
    background: #fff;
    border-radius: 14px;
    padding: 1.2rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
}

/* HEADER */
.leaderboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.leaderboard-header h1 {
    font-size: clamp(1.2rem, 2vw, 1.5rem);
    font-weight: 800;
    color: #1e1b4b;
}

.sub {
    font-size: 0.8rem;
    color: #6b7280;
}

/* YOU BADGE */
.you-pill {
    background: #6366f1;
    color: white;
    padding: 6px 10px;
    border-radius: 999px;
    display: flex;
    gap: 6px;
    align-items: center;
    font-weight: 600;
    font-size: 0.75rem;
}

/* LIST */
.leaderboard-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    max-height: 420px;
    overflow-y: auto;
}

/* ITEM */
.leader-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 10px;
    transition: 0.2s;
    background: #fafafa;
}

.leader-item:hover {
    background: #f3f4f6;
}

/* TOP 3 */
.leader-item.top {
    background: #f9fafb;
    border: 1px solid #eee;
}

/* YOU */
.leader-item.is-you {
    border: 1px solid #6366f1;
    background: #eef2ff;
}

/* RANK */
.rank {
    width: 30px;
    text-align: center;
    font-weight: 700;
    color: #6b7280;
}

.medal {
    font-size: 1.2rem;
}

/* USER */
.user {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 0;
}

.avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    flex-shrink: 0;
}

.info {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.name {
    font-weight: 600;
    font-size: 0.9rem;
    color: #111827;
}

.quiz {
    font-size: 0.75rem;
    color: #6b7280;
}

/* SCORE */
.score {
    text-align: right;
    min-width: 90px;
}

.bar {
    height: 6px;
    background: #e5e7eb;
    border-radius: 999px;
    overflow: hidden;
    margin-bottom: 4px;
}

.fill {
    height: 100%;
    background: linear-gradient(90deg, #6366f1, #8b5cf6);
    border-radius: 999px;
    transition: width 0.4s ease;
}

.score span {
    font-size: 0.75rem;
    font-weight: 600;
}

/* EMPTY */
.empty {
    text-align: center;
    padding: 20px;
    color: #9ca3af;
}

/* SCROLL */
.leaderboard-list::-webkit-scrollbar {
    width: 5px;
}

.leaderboard-list::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 10px;
}

/* MOBILE */
@media (max-width: 640px) {
    .leaderboard-header {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }

    .leader-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .score {
        width: 100%;
    }
}
</style>