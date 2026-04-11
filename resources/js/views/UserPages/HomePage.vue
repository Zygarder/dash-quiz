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

      <div v-for="(entry, index) in leaderboard" :key="entry.id" class="leader-item" :class="[
        entry.isYou ? 'is-you' : '',
        index < 3 ? 'top' : ''
      ]">

        <!-- LEFT -->
        <div class="left">

          <!-- RANK -->
          <div class="rank">
            <span v-if="index < 3" class="medal">
              {{ ['🥇', '🥈', '🥉'][index] }}
            </span>
            <span v-else>{{ index + 1 }}</span>
          </div>

          <!-- AVATAR -->
          <div class="avatar-box">
            <img :src="`/storage/images/profiles/${entry.profile_photo || 'default.png'}`" class="avatar" alt="user" />
          </div>

          <!-- INFO -->
          <div class="info">
            <div class="name">
              {{ entry.displayName }}
              <span v-if="entry.isYou" class="you-badge">You</span>
            </div>

            <div class="quiz">{{ entry.quiz_title }}</div>

            <div class="time" v-if="entry.completed_at">
              {{ formatDate(entry.completed_at) }}
            </div>
          </div>
        </div>

        <!-- SCORE -->
        <div class="score">
          <div class="bar">
            <div class="fill" :style="{ width: scoreWidth(entry.score) }"></div>
          </div>
          <span>{{ entry.score }}/10</span>
        </div>

      </div>

      <div v-if="!leaderboard.length" class="empty">
        <p>No rankings yet 📊</p>
      </div>

    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref, computed, onMounted } from 'vue'
import { useUser } from "@/composables/useUser"

const leaderboard = ref([])
const isLoading = ref(false)
const { user, fetchUser } = useUser()

const userPosition = computed(() => {
  if (!user.value?.id) return null
  const idx = leaderboard.value.findIndex(u => u.id === user.value.id)
  return idx !== -1 ? idx + 1 : null
})

const scoreWidth = (score) => `${(score / 10) * 100}%`

const formatDate = (date) =>
  date ? new Date(date).toLocaleDateString() : ''

const getLeaderBoard = async () => {
  isLoading.value = true
  try {
    await axios.get('/sanctum/csrf-cookie')
    const { data } = await axios.get('/api/dashboard/leaderboard')

    const currentUserId = user.value?.id ?? null

    leaderboard.value = data.data.map(u => ({
      ...u,
      isYou: u.user_id === currentUserId,
      displayName: u.name,
    }))

  } catch (err) {
    console.error(err)
  } finally {
    isLoading.value = false
  }
}

onMounted(async () => {
  await fetchUser()
  await getLeaderBoard()
})
</script>

<style scoped>
.leaderboard {
  width: 100%;
  background: #fff;
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}

/* HEADER */
.leaderboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.leaderboard-header h1 {
  font-size: 1.4rem;
  font-weight: 800;
  color: #111827;
}

.sub {
  font-size: 0.85rem;
  color: #6b7280;
  margin-top: 2px;
}

/* YOU BADGE */
.you-pill {
  background: #6366f1;
  color: #fff;
  padding: 6px 12px;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 600;
}

/* LIST */
.leaderboard-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
  max-height: 450px;
  overflow-y: auto;
  padding-right: 4px;
}

/* ITEM */
.leader-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px;
  border-radius: 12px;
  border: 1px solid #f1f5f9;
  transition: 0.2s ease;
}

.leader-item:hover {
  background: #f9fafb;
}

.leader-item.top {
  background: #f8fafc;
}

.leader-item.is-you {
  background: #eef2ff;
  border-color: #6366f1;
}

/* LEFT SECTION */
.left {
  display: flex;
  align-items: center;
  gap: 12px;
  min-width: 0;
}

/* RANK */
.rank {
  width: 28px;
  text-align: center;
  font-weight: 700;
  color: #6b7280;
}

.medal {
  font-size: 1.2rem;
}

/* AVATAR */
.avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #e5e7eb;
}

/* INFO */
.info {
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.name {
  font-weight: 600;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  gap: 6px;
}

.quiz {
  font-size: 0.75rem;
  color: #6b7280;
}

.time {
  font-size: 0.7rem;
  color: #9ca3af;
}

/* YOU BADGE */
.you-badge {
  background: #6366f1;
  color: white;
  font-size: 0.65rem;
  padding: 2px 6px;
  border-radius: 999px;
}

/* SCORE */
.score {
  min-width: 80px;
  text-align: right;
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
  transition: width 0.3s ease;
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
  .leader-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .score {
    width: 100%;
    text-align: left;
  }

  .leaderboard-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
}
</style>