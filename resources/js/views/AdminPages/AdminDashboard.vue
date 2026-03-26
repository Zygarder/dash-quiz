<template>
  <div class="dashboard-wrapper">
    <!-- Notification -->
    <div v-if="notification" class="notification">
      <i class="fas fa-bell"></i> {{ notification }}
    </div>

    <div v-if="stats">
      <!-- Cards -->
      <section class="admin-stats">
        <div class="admin-card">
          <i class="fas fa-users"></i>
          <h4>Total Users</h4>
          <p>{{ stats.total_users }}</p>
        </div>

        <div class="admin-card">
          <i class="fas fa-file-alt"></i>
          <h4>Total Quizzes</h4>
          <p>{{ stats.total_quizzes }}</p>
        </div>

        <div class="admin-card">
          <i class="fas fa-user-check"></i>
          <h4>Active Users</h4>
          <p>{{ stats.active_users }}</p>
        </div>
      </section>

      <!-- Chart + Leaderboard -->
      <div class="chart-leaderboard-grid">
        <section class="chart-section">
          <h3 class="section-title"><i class="fas fa-chart-bar"></i> System Analytics</h3>
          <canvas ref="chartCanvas"></canvas>
        </section>

        <section class="leaderboard">
          <h3 class="section-title"><i class="fas fa-trophy"></i> Top 10 Dashers</h3>

          <div v-if="stats.top_users?.length" class="leaderboard-list">
            <div v-for="(user, index) in stats.top_users" :key="user.id" class="leader-row">
              <div class="user-info">
                <span class="rank-badge" :class="{ 'top-3': index < 3 }">
                  <i v-if="index === 0" class="fas fa-crown"></i>
                  <span v-else>#{{ index + 1 }}</span>
                </span>
                <span class="leader-name">{{ user.first_name }} {{ user.last_name }}</span>
              </div>
              <strong class="avg-score"><i class="fas fa-star"></i> {{ user.average_score }}%</strong>
            </div>
          </div>

          <div v-else class="empty-state">No leaderboard data yet.</div>
        </section>
      </div>

      <!-- Logs -->
      <section class="admin-details">
        <div class="logs-table">
          <div class="logs-header">
            <span><i class="fas fa-clock"></i> Recent Activity</span>
            <div class="filter-buttons">
              <button :class="{ active: filterType === 'today' }" @click="filterType = 'today'">Today</button>
              <button :class="{ active: filterType === 'week' }" @click="filterType = 'week'">Week</button>
            </div>
          </div>

          <div v-if="filteredLogs.length">
            <div v-for="log in filteredLogs" :key="log.id" class="logs-row">
              <div class="logs-icon" :class="getLogType(log.type)"><i :class="getLogIcon(log.type)"></i></div>
              <div class="logs-content">
                <p>{{ log.description }}</p>
                <small>{{ formatDate(log.created_at) }} • Admin #{{ log.admin_id }}</small>
              </div>
            </div>
          </div>

          <div v-else class="empty"><i class="fas fa-inbox"></i> No logs found.</div>
        </div>
      </section>
    </div>

    <!-- Loading -->
    <div v-else class="loading-state">
      <div class="spinner"></div>
      <h3>Loading dashboard...</h3>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted, onUnmounted } from "vue"
import Chart from "chart.js/auto"
import axios from "axios"

// --- State ---
const stats = ref(null)
const chartCanvas = ref(null)
let chartInstance = null

const notification = ref("")
const filterType = ref("today")

let interval = null // for auto-refresh

// --- Computed ---
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

// --- Utils ---
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

// --- Chart ---
const renderChart = () => {
  if (!chartCanvas.value || !stats.value) return

  if (chartInstance) {
    chartInstance.destroy()
  }

  chartInstance = new Chart(chartCanvas.value, {
    type: "bar",
    data: {
      labels: ["System Stats"],
      datasets: [
        { label: "Total Users", data: [stats.value.total_users] },
        { label: "Total Quizzes", data: [stats.value.total_quizzes] },
        { label: "Active Users", data: [stats.value.active_users] },
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
    }
  })
}

// --- Fetch Data ---
const fetchStats = async (showNotif = false) => {
  try {
    const response = await axios.get('/api/admin/dashboard')

    // Optional: detect updates
    if (showNotif && stats.value) {
      notification.value = "Dashboard updated!"
      setTimeout(() => notification.value = "", 2000)
    }

    stats.value = response.data.data

    await nextTick()
    renderChart()

  } catch (err) {
    console.error("Error fetching stats:", err)
  }
  
}

// --- Lifecycle ---
onMounted(() => {
  // initial load
  fetchStats()

  interval = setInterval(() => {
    fetchStats(true)
  }, 3000)
})

// cleanup
onUnmounted(() => {
  if (interval) clearInterval(interval)
})
</script>

<style scoped>
/* ===== Layout ===== */
.dashboard-wrapper {
  padding: 1.5rem;
  background: #f8fafc;
  min-height: 100vh;
}

/* ===== Notification ===== */
.notification {
  position: fixed;
  top: 16px;
  right: 16px;
  z-index: 99;
  background: #ecfdf5;
  color: #065f46;
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 0.85rem;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
}

/* ===== Loading ===== */
.loading-state {
  text-align: center;
  padding: 5rem;
  color: #64748b;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #e5e7eb;
  border-top: 4px solid #6366f1;
  border-radius: 50%;
  margin: 0 auto 1rem;
  animation: spin 1s linear infinite;
}

/* ===== Stats ===== */
.admin-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.admin-card {
  background: #fff;
  padding: 1.2rem;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  transition: 0.2s;
}

.admin-card:hover {
  transform: translateY(-2px);
}

.admin-card h4 {
  font-size: 0.75rem;
  color: #94a3b8;
  margin-bottom: 6px;
}

.admin-card p {
  font-size: 1.6rem;
  font-weight: 700;
  color: #1e293b;
}

/* ===== Grid ===== */
.chart-leaderboard-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.chart-section,
.leaderboard,
.admin-details {
  background: #fff;
  border-radius: 12px;
  padding: 1.2rem;
  border: 1px solid #e5e7eb;
}

/* ===== Titles ===== */
.section-title {
  font-size: 0.95rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: #334155;
}

/* ===== Chart ===== */
.chart-section canvas {
  max-height: 200px;
}

/* ===== Leaderboard ===== */
.leaderboard-list {
  display: flex;
  flex-direction: column;
  gap: 6px;
  max-height: 320px;
  overflow-y: auto;
}

.leader-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.7rem;
  border-radius: 8px;
  border: 1px solid #f1f5f9;
  transition: 0.2s;
}

.leader-row:hover {
  background: #f9fafb;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.rank-badge {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  background: #f1f5f9;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 600;
}

.rank-badge.top-3 {
  background: #e0e7ff;
  color: #3730a3;
}

.leader-name {
  font-size: 0.85rem;
}

.avg-score {
  font-size: 0.85rem;
  font-weight: 600;
  color: #16a34a;
}

/* ===== Logs ===== */
.logs-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.8rem;
  font-size: 0.85rem;
  font-weight: 600;
}

.logs-row {
  display: flex;
  gap: 10px;
  padding: 0.7rem 0;
  border-bottom: 1px solid #f1f5f9;
}

.logs-content p {
  font-size: 0.85rem;
  color: #1e293b;
}

.logs-content small {
  font-size: 0.7rem;
  color: #94a3b8;
}

.empty,
.empty-state {
  text-align: center;
  font-size: 0.8rem;
  color: #94a3b8;
  padding: 1rem 0;
}

/* ===== Filters ===== */
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
}

.filter-buttons button.active {
  background: #6366f1;
  color: white;
  border-color: #6366f1;
}

/* ===== Animation ===== */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>