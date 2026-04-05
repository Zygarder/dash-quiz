<template>
  <div class="leaderboard">

    <!-- HEADER -->
    <div class="leaderboard-header">
      <div>
        <h1>Leaderboard</h1>
        <p class="sub">{{ leaderboard.length }} participants</p>
      </div>

      <!-- Current user pill -->
      <div v-if="userPosition" class="you-pill">
        #{{ userPosition }} You
      </div>
    </div>

    <!-- LIST -->
    <div class="leaderboard-list">

      <!-- Leaderboard entries -->
      <div v-for="(entry, index) in leaderboard" :key="entry.id" class="leader-item"
        :class="[{ 'is-you': entry.isYou }, index < 3 ? 'top' : '']">

        <!-- RANK -->
        <div class="rank">
          <span v-if="index < 3" class="medal">
            {{ ['🥇', '🥈', '🥉'][index] }}
          </span>
          <span v-else>{{ index + 1 }}</span>
        </div>

        <!-- USER INFO -->
        <div class="user">
          <img :src="`/storage/images/profiles/${entry.profile_photo || 'default.png'}`" alt="user-image"
            class="avatar">
          <div class="info">
            <!-- ✅ Shows "You" badge if current user, otherwise name -->
            <span class="name">
              {{ entry.displayName }}
              <span v-if="entry.isYou" class="you-badge">You</span>
            </span>
            <span class="quiz">{{ entry.quiz_title }}</span>
            <span class="time" v-if="entry.completed_at">{{ formatDate(entry.completed_at) }}</span>
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

      <!-- EMPTY STATE -->
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

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString()
}

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

    console.log(leaderboard.value)

  } catch (err) {
    console.error(err.message)
  } finally {
    isLoading.value = false
  }
}
// ✅ fetchUser first, then leaderboard — order guaranteed
onMounted(async () => {
  try {
    await fetchUser()
    await getLeaderBoard()
  } catch (err) {
    console.error(err)
  }
})
</script>

<style scoped>
.leaderboard {
  width: 100%;
  background: #fff;
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}

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

.leaderboard-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
  max-height: 420px;
  overflow-y: auto;
}

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

.leader-item.top {
  background: #f9fafb;
  border: 1px solid #e5e7eb;
}

.leader-item.is-you {
  background: #eef2ff;
  border: 1px solid #6366f1;
}

.rank {
  width: 30px;
  text-align: center;
  font-weight: 700;
  color: #6b7280;
}

.medal {
  font-size: 1.3rem;
}

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
  display: flex;
  align-items: center;
  gap: 6px;
}

/* ✅ Inline "You" badge next to the name */
.you-badge {
  background: #6366f1;
  color: #fff;
  font-size: 0.65rem;
  font-weight: 700;
  padding: 2px 7px;
  border-radius: 999px;
  letter-spacing: 0.03em;
}

.quiz {
  font-size: 0.75rem;
  color: #6b7280;
}

.time {
  font-size: 0.7rem;
  color: #9ca3af;
}

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

.empty {
  text-align: center;
  padding: 20px;
  color: #9ca3af;
}

.leaderboard-list::-webkit-scrollbar {
  width: 6px;
}

.leaderboard-list::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 10px;
}

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