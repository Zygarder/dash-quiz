<template>
  <div class="dashboard-wrapper">

    <!-- Notification -->
    <div v-if="notification" class="notification">
      <i class="fas fa-bell icon-notif"></i> {{ notification }}
    </div>

    <div v-if="stats">

      <!-- STATS -->
      <section class="admin-stats">
        <div class="admin-card">
          <i class="fas fa-users icon-users"></i>
          <h4>Total Users</h4>
          <p>{{ stats.total_users }}</p>
        </div>

        <div class="admin-card">
          <i class="fas fa-file-alt icon-quizzes"></i>
          <h4>Total Quizzes</h4>
          <p>{{ stats.total_quizzes }}</p>
        </div>

        <div class="admin-card">
          <i class="fas fa-user-check icon-active"></i>
          <h4>Active Users</h4>
          <p>{{ stats.active_users }}</p>
        </div>
      </section>

      <!-- GRID -->
      <div class="chart-leaderboard-grid">

        <!-- CHART -->
        <section class="chart-section">
          <h3 class="section-title">
            <i class="fas fa-chart-bar icon-chart"></i> System Analytics
          </h3>

          <div class="chart-wrapper">
            <canvas ref="chartCanvas"></canvas>
          </div>
        </section>

        <!-- LEADERBOARD -->
        <section class="leaderboard">
          <h3 class="section-title">
            <i class="fas fa-trophy"></i> Top 10 Dashers
          </h3>

          <div v-if="stats.top_users?.length" class="leaderboard-list">

            <div v-for="(user, index) in stats.top_users" :key="user.id" class="leader-row" :title="`${user.first_name} ${user.last_name}`">
              <!-- Rank -->
              <div class="rank">
                <i v-if="index === 0" class="fas fa-crown"></i>
                <span v-else>#{{ index + 1 }}</span>
              </div>

              <!-- User -->
              <div class="user-info">
                <div class="avatar">
                  {{ user.first_name?.[0] }}{{ user.last_name?.[0] }}
                </div>

                <div class="name-block">
                  <span class="leader-name">
                    {{ user.first_name }} {{ user.last_name }}
                  </span>
                  <small>Top performer</small>
                </div>
              </div>

              <!-- Score -->
              <div class="score">
                <i class="fas fa-star"></i>
                {{ user.average_score }}%
              </div>
            </div>

          </div>

          <div v-else class="empty-state">
            No leaderboard data yet.
          </div>
        </section>

      </div>

      <!-- LOGS -->
      <section class="admin-details">
        <div class="logs-table">

          <!-- HEADER -->
          <div class="logs-header">
            <span>
              <i class="fas fa-clock"></i> Recent Activity
            </span>

            <div class="filter-buttons">
              <button :class="{ active: filterType === 'today' }" @click="filterType = 'today'">
                Today
              </button>

              <button :class="{ active: filterType === 'week' }" @click="filterType = 'week'">
                Week
              </button>
            </div>
          </div>

          <!-- LOG LIST -->
          <div v-if="filteredLogs.length" class="logs-list">

            <div v-for="log in filteredLogs" :key="log.id" class="log-item">
              <!-- ICON -->
              <div class="log-icon" :class="getLogType(log.type)">
                <i :class="getLogIcon(log.type)"></i>
              </div>

              <!-- CONTENT -->
              <div class="log-content">
                <p class="log-text">{{ log.description }}</p>
                <small>
                  {{ formatDate(log.created_at) }} • Admin #{{ log.admin_id }}
                </small>
              </div>

              <!-- DOT -->
              <div class="log-dot"></div>
            </div>

          </div>

          <!-- EMPTY -->
          <div v-else class="empty">
            <i class="fas fa-inbox"></i> No logs found.
          </div>

        </div>
      </section>

    </div>

    <!-- LOADING -->
    <div v-else class="loading-state">
      <div class="spinner"></div>
      <h3>Loading dashboard...</h3>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted } from "vue"
import Chart from "chart.js/auto"
import axios from "axios"

const stats = ref([])
const chartCanvas = ref(null)
let chartInstance = null

const notification = ref("")
const filterType = ref("today")

const filteredLogs = computed(() => {
  if (!stats.value?.logs) return []
  const now = new Date()

  return stats.value.logs.filter(log => {
    const logDate = new Date(log.created_at)

    if (filterType.value === "today") {
      return logDate.toDateString() === now.toDateString()
    }

    if (filterType.value === "week") {
      const weekAgo = new Date()
      weekAgo.setDate(now.getDate() - 7)
      return logDate >= weekAgo
    }

    return true
  })
})

const formatDate = (date) => new Date(date).toLocaleString()

const getLogIcon = (type) => {
  switch (type) {
    case "delete": return "fas fa-trash"
    case "create": return "fas fa-plus"
    case "update": return "fas fa-edit"
    default: return "fas fa-info-circle"
  }
}

const getLogType = (type) => ({
  "log-delete": type === "delete",
  "log-create": type === "create",
  "log-update": type === "update",
})

const renderChart = () => {
  if (!chartCanvas.value || !stats.value) return

  if (chartInstance) chartInstance.destroy()

  requestAnimationFrame(() => {
    chartInstance = new Chart(chartCanvas.value, {
      type: "bar",
      data: {
        labels: ["System Stats"],
        datasets: [
          {
            label: "Total Users",
            data: [stats.value.total_users],
            backgroundColor: "#8b5cf6",
          },
          {
            label: "Total Quizzes",
            data: [stats.value.total_quizzes],
            backgroundColor: "#16a34a",
          },
          {
            label: "Active Users",
            data: [stats.value.active_users],
            backgroundColor: "#4b3cd1",
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
      },
    })
  })
}

const fetchStats = async () => {
  try {
    const { data } = await axios.get("/api/admin/dashboard")
    stats.value = data.data

    await nextTick()
    renderChart()
  } catch (err) {
    console.error(err)
  }
}

onMounted(() => {
  fetchStats()
})
</script>

<style scoped>
/* =========================
   GLOBAL SAFE WRAPPER
========================= */
.dashboard-wrapper {
  width: 100%;
  max-width: 100%;
  overflow-x: hidden;

  padding: clamp(0.8rem, 2vw, 1.5rem);
  box-sizing: border-box;

  background: #f8fafc;
}

/* =========================
   NOTIFICATION
========================= */
.notification {
  position: fixed;
  top: 12px;
  right: 12px;
  z-index: 99;
  background: #ecfdf5;
  color: #065f46;
  padding: 10px 12px;
  border-radius: 8px;
  font-size: 0.85rem;
}

/* =========================
   ICON COLORS (UNCHANGED)
========================= */
.icon-users {
  color: #8b5cf6;
}

.icon-quizzes {
  color: #16a34a;
}

.icon-active {
  color: #4b3cd1;
}

.icon-chart {
  color: #6366f1;
}

.icon-trophy {
  color: #f59e0b;
}

.icon-crown {
  color: #fbbf24;
}

.icon-star {
  color: #22c55e;
}

.icon-clock {
  color: #64748b;
}

.icon-empty {
  color: #94a3b8;
}

.icon-notif {
  color: #10b981;
}

/* =========================
   STATS GRID
========================= */
.admin-stats {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 1rem;

  margin-bottom: 1rem;
}

.admin-card {
  background: #fff;
  border-radius: 12px;
  padding: 1rem;
  border: 1px solid #e5e7eb;
  min-width: 0;
}

/* =========================
   MAIN GRID
========================= */
.chart-leaderboard-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 1rem;

  align-items: stretch;
}

/* =========================
   CARDS
========================= */
.chart-section,
.leaderboard,
.admin-details {
  background: #fff;
  border-radius: 12px;
  padding: 1rem;
  margin-bottom: 10px;
  min-width: 0;
  overflow: hidden;

  border: 1px solid #e5e7eb;
}

/* =========================
   CHART FIX
========================= */
.chart-wrapper {
  width: 100%;
  height: 280px;
  position: relative;
}

/* ===== LEADERBOARD WRAPPER ===== */
.leaderboard {
  background: #fff;
  border-radius: 12px;
  padding: 1.2rem;
  border: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

/* ===== LIST ===== */
.leaderboard-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 0.8rem;
  max-height: 340px;
  overflow-y: auto;
  padding-right: 4px;
}

/* ===== ROW ===== */
.leader-row {
  display: grid;
  grid-template-columns: 40px 1fr auto;
  align-items: center;
  gap: 10px;
  padding: 10px;
  border-radius: 10px;
  border: 1px solid #f1f5f9;
  transition: 0.2s ease;
  background: #fff;
}

.leader-row:hover {
  background: #f8fafc;
  transform: translateY(-1px);
}

/* ===== RANK ===== */
.rank {
  text-align: center;
  font-size: 0.8rem;
  font-weight: 700;
  color: #64748b;
}

.rank i {
  color: #fbbf24;
}

/* ===== USER INFO ===== */
.user-info {
  display: flex;
  align-items: center;
  gap: 10px;
  min-width: 0;
}

.avatar {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  background: linear-gradient(135deg, #6366f1, #4f46e5);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 700;
  color: #fff;
  flex-shrink: 0;
}

.name-block {
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.leader-name {
  font-size: 0.85rem;
  font-weight: 600;
  color: #1e293b;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.name-block small {
  font-size: 0.7rem;
  color: #94a3b8;
}

/* ===== SCORE ===== */
.score {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 0.85rem;
  font-weight: 600;
  color: #16a34a;
}

.score i {
  color: #facc15;
}

/* ===== EMPTY ===== */
.empty-state {
  text-align: center;
  font-size: 0.8rem;
  color: #94a3b8;
  padding: 1rem 0;
}

/* ===== LOGS CONTAINER ===== */
.logs-table {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

/* ===== HEADER ===== */
.logs-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.9rem;
  font-weight: 600;
  color: #334155;
  margin-bottom: 0.5rem;
}

/* ===== FILTER BUTTONS ===== */
.filter-buttons {
  display: flex;
  gap: 6px;
}

.filter-buttons button {
  padding: 4px 10px;
  font-size: 0.75rem;
  border-radius: 6px;
  border: 1px solid #e5e7eb;
  background: #f9fafb;
  cursor: pointer;
  transition: 0.2s;
}

.filter-buttons button.active {
  background: #6366f1;
  color: white;
  border-color: #6366f1;
}

/* ===== LOG LIST ===== */
.logs-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
  max-height: 340px;
  overflow-y: auto;
  padding-right: 4px;
}

/* ===== LOG ITEM ===== */
.log-item {
  display: grid;
  grid-template-columns: 36px 1fr 6px;
  gap: 10px;
  align-items: start;
  padding: 10px;
  border-radius: 10px;
  border: 1px solid #f1f5f9;
  background: #fff;
  transition: 0.2s ease;
}

.log-item:hover {
  background: #f8fafc;
  transform: translateY(-1px);
}

/* ===== ICON ===== */
.log-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.85rem;
}

/* KEEP YOUR EXISTING TYPES (just better spacing) */
.log-delete {
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
}

.log-create {
  background: rgba(34, 197, 94, 0.1);
  color: #22c55e;
}

.log-update {
  background: rgba(59, 130, 246, 0.1);
  color: #3b82f6;
}

/* ===== CONTENT ===== */
.log-content {
  min-width: 0;
}

.log-text {
  font-size: 0.85rem;
  color: #1e293b;
  margin: 0;
  line-height: 1.3;
}

.log-content small {
  font-size: 0.7rem;
  color: #94a3b8;
}

/* ===== DOT (timeline feel) ===== */
.log-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #e2e8f0;
  margin-top: 10px;
}

/* ===== EMPTY ===== */
.empty {
  text-align: center;
  font-size: 0.8rem;
  color: #94a3b8;
  padding: 1rem 0;
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width: 1024px) {
  .chart-leaderboard-grid {
    grid-template-columns: 1fr;
  }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .leader-row {
    grid-template-columns: 35px 1fr auto;
    padding: 8px;
  }

  .avatar {
    width: 30px;
    height: 30px;
    font-size: 0.7rem;
  }

  .leader-name {
    font-size: 0.8rem;
  }

  .score {
    font-size: 0.8rem;
  }
}

@media (max-width: 480px) {
  .leader-row {
    grid-template-columns: 35px 1fr;
  }

  .score {
    grid-column: 1 / -1;
    justify-content: flex-end;
    margin-top: 4px;
  }
}


@media (max-width: 768px) {
  .admin-stats {
    grid-template-columns: 1fr;
  }

  .dashboard-wrapper {
    padding: 0.8rem;
  }

  .chart-wrapper {
    height: 240px;
  }
}

@media (max-width: 480px) {
  .chart-wrapper {
    height: 200px;
  }
}

/* =========================
   SAFE BOX MODEL
========================= */
* {
  box-sizing: border-box;
}
</style>