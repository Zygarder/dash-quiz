<template>
  <div class="leaderboard">

    <!-- HEADER -->
    <div class="leaderboard-header">
      <div>
        <h1>Leaderboard</h1>
        <p class="sub">{{ leaderboard.length }} participants</p>
      </div>

      <div v-if="userPosition" class="you-pill">
        #{{ userPosition }} You
      </div>
    </div>

    <!-- LIST -->
    <div class="leaderboard-list">

      <div 
        v-for="(user, index) in leaderboard" 
        :key="user.id" 
        class="leader-item"
        :class="[{ 'is-you': user.isYou }, index < 3 ? 'top' : '']"
      >

        <!-- RANK -->
        <div class="rank">
          <span v-if="index < 3" class="medal">
            {{ ['🥇', '🥈', '🥉'][index] }}
          </span>
          <span v-else>{{ index + 1 }}</span>
        </div>

        <!-- USER -->
        <div class="user">
          <img 
            :src="`/storage/images/profiles/${user.profile_photo || 'default.png'}`" 
            alt="user-image" 
            class="avatar"
          >
          <div class="info">
            <span class="name">{{ user.name }}</span>
            <span class="quiz">{{ user.quiz_title }}</span>
            <span class="time" v-if="user.completed_at">{{ formatDate(user.completed_at) }}</span>
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
import { ref, computed, onMounted } from 'vue'

const leaderboard = ref([])
const isLoading = ref(false)

const userPosition = computed(() => {
  const you = leaderboard.value.find(u => u.isYou)
  return you ? leaderboard.value.indexOf(you) + 1 : null
})

const scoreWidth = (score) => `${(score / 10) * 100}%`

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString()
}

// Fetch leaderboard data
const getLeaderBoard = async () => {
  isLoading.value = true
  try {
    await axios.get('/sanctum/csrf-cookie')
    const { data } = await axios.get('/api/dashboard/leaderboard')
    leaderboard.value = data.data
  } catch (err) {
    console.error(err.message)
  } finally {
    isLoading.value = false
  }
}

onMounted(getLeaderBoard)
</script>

<style scoped>
.leaderboard {
  width: 100%;
  background: #fff;
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 10px 25px rgba(0,0,0,0.05);
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
  color: #111827;
}

.sub {
  font-size: 0.8rem;
  color: #6b7280;
}

.you-pill {
  background: #6366f1;
  color: #fff;
  padding: 6px 12px;
  border-radius: 999px;
  font-weight: 600;
  font-size: 0.75rem;
  display: flex;
  align-items: center;
  gap: 6px;
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
  padding: 12px 16px;
  border-radius: 12px;
  background: #ffffff;
  border: 1px solid #f1f5f9;
  transition: 0.2s;
}

.leader-item:hover {
  background: #f9fafb;
}

/* TOP 3 */
.leader-item.top {
  background: #f9fafb;
  border: 1px solid #e5e7eb;
}

/* YOU */
.leader-item.is-you {
  background: #eef2ff;
  border: 1px solid #6366f1;
}

/* RANK */
.rank {
  width: 30px;
  text-align: center;
  font-weight: 700;
  color: #6b7280;
}

.medal {
  font-size: 1.3rem;
}

/* USER */
.user {
  display: flex;
  align-items: center;
  gap: 12px;
  flex: 1;
  min-width: 0;
}

.avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 2px solid #f1f5f9;
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

.time {
  font-size: 0.7rem;
  color: #9ca3af;
}

/* SCORE */
.score {
  min-width: 90px;
  text-align: right;
}

.bar {
  height: 5px;
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

/* SCROLLBAR */
.leaderboard-list::-webkit-scrollbar {
  width: 6px;
}
.leaderboard-list::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 10px;
}

/* MOBILE */
@media (max-width: 640px) {
  .leaderboard-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
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