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
import { ref, computed, nextTick, onMounted } from "vue"
import Chart from "chart.js/auto"
import axios from "axios"

// --- State Management ---
const stats = ref(null)
const chartCanvas = ref(null)
let chartInstance = null

const notification = ref("")
const filterType = ref("today")

// --- Computed & Utilities ---
const filteredLogs = computed(() => {
  if (!stats.value?.logs) return []
  const now = new Date()
  return stats.value.logs.filter(log => {
    const logDate = new Date(log.created_at)
    if (filterType.value === "today") return logDate.toDateString() === now.toDateString()
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

// --- Chart.js Logic ---
const renderChart = () => {
  if (!chartCanvas.value || !stats.value) return
  if (chartInstance) chartInstance.destroy()

  chartInstance = new Chart(chartCanvas.value, {
    type: "bar",
    data: {
      labels: ["System Stats"],
      datasets: [
        { label: "Total Users", data: [stats.value.total_users], backgroundColor: "#3b82f6" },
        { label: "Total Quizzes", data: [stats.value.total_quizzes], backgroundColor: "#8b5cf6" },
        { label: "Active Users", data: [stats.value.active_users], backgroundColor: "#22c55e" },
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: true } }
    }
  })
}

// --- Data Fetching (The "Turn On" Request) ---
const fetchInitialData = async () => {
  try {
    // Note: Ensure this URL matches your actual Laravel API route
    const response = await axios.get('/api/admin/stats')
    stats.value = response.data

    await nextTick()
    renderChart()
  } catch (err) {
    console.error("Could not load initial stats:", err)
  }
}

// --- Lifecycle & Real-time Listeners ---
onMounted(() => {
  // 1. Get current data immediately via Axios
  fetchInitialData()

  // 2. Listen for future updates via Laravel Echo
  if (window.Echo) {
    window.Echo.channel('dashboard')
      .listen('StatsUpdated', async (e) => {
        console.log("Real-time update received:", e)
        stats.value = e.stats
        await nextTick()
        renderChart()
      })
      .listen('NewLog', (e) => {
        if (stats.value?.logs) {
          stats.value.logs.unshift(e.log)
          notification.value = "New activity detected!"
          setTimeout(() => notification.value = "", 3000)
        }
      })
  } else {
    console.error("Echo is not defined. Check your Echo configuration.")
  }
})
</script>

<style scoped>
/* ===== Layout ===== */
.dashboard-wrapper {
  padding: 2rem;
  background: #f8fafc;
  min-height: 100vh;
}

/* ===== Notification (Top Right Floating) ===== */
.notification {
  position: fixed;
  top: 20px;
  right: 20px;
  background: #dcfce7;
  color: #166534;
  padding: 12px 18px;
  border-radius: 10px;
  font-size: 0.9rem;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  z-index: 999;
}

/* ===== Loader ===== */
.loading-state {
  text-align: center;
  padding: 6rem;
  color: #4c1d95;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 5px solid #ede9fe;
  border-top: 5px solid #7c3aed;
  border-radius: 50%;
  margin: 0 auto 1rem;
  animation: spin 1s linear infinite;
}

/* ===== Stats Cards ===== */
.admin-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.admin-card {
  background: white;
  padding: 1.8rem;
  border-radius: 16px;
  border: 1px solid #f1f5f9;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
  transition: 0.2s;
}

.admin-card:hover {
  transform: translateY(-3px);
}

.admin-card h4 {
  color: #64748b;
  font-size: 0.85rem;
  margin-bottom: 8px;
}

.admin-card p {
  font-size: 2rem;
  font-weight: 700;
  color: #4c1d95;
}

/* ===== Grid Sections ===== */
.chart-section,
.admin-details {
  background: white;
  border-radius: 16px;
  padding: 1.5rem;
  border: 1px solid #f1f5f9;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
}

/* Leaderboard gets its own elevated card styling */
.leaderboard {
  background: white;
  border-radius: 16px;
  padding: 1.5rem;
  border: 2px solid #e0e7ff;
  box-shadow: 0 10px 30px rgba(76, 29, 149, 0.08);
  display: flex;
  flex-direction: column;
}

/* 2-column layout */
.chart-leaderboard-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

/* ===== Titles ===== */
.section-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: #1e293b;
}

.section-title div {
  font-size: 10px;
}

/* Leaderboard specific header */
.leaderboard h3.section-title {
  display: flex;
  justify-content: center;
  align-items: center;
  background: #f8fafc;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e2e8f0;
  border-radius: 16px 16px 0 0;
  color: #1e1b4b;
}

/* ===== Chart ===== */
.chart-section canvas {
  max-height: 200px;
}


/* ===== Leaderboard Specifics ===== */
.leaderboard-list {
  max-height: 350px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 8px;
  /* Natural spacing between rows */
  padding-right: 5px;
}

/* Scrollbar styling for the list */
.leaderboard-list::-webkit-scrollbar {
  width: 6px;
}

.leaderboard-list::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 4px;
}

/* Row styling */
.leader-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background: #ffffff;
  border: 1px solid #f1f5f9;
  border-radius: 10px;
  font-size: 0.9rem;
  transition: 0.2s;
}

.leader-row:hover {
  border-color: #d1d5db;
  transform: translateX(3px);
  background-color: #f9fafb;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.leader-name {
  font-weight: 500;
  color: #1e293b;
}

.rank-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  background: #f1f5f9;
  color: #64748b;
  border-radius: 50%;
  font-size: 0.8rem;
  font-weight: 700;
  border: 1px solid #e2e8f0;
}

/* Highlight the top 3 players */
.rank-badge.top-3 {
  background: #fef08a;
  color: #854d0e;
  border-color: #fde047;
}

.avg-score {
  color: #166534;
  font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, Courier, monospace;
  font-size: 1rem;
  font-weight: 700;
}

/* ===== Logs ===== */
.logs-header {
  display: flex-end;
  justify-content: center;
  align-items: center;
  margin-bottom: 1rem;
  font-weight: 600;
}

.logs-row {
  border-bottom: 1px solid black;
  align-items: center;
  display: flex;
  justify-content: center;
}

.logs-row span {

  padding: 0.9rem 0.5rem;
  border-bottom: 1px solid #f8fafc;
  font-size: 0.9rem;
}

.logs-row small {
  color: #94a3b8;
}

.empty {
  text-align: center;
  color: #94a3b8;

}

/* ===== Filter Buttons ===== */
.filter-buttons {
  display: flex;
  gap: 8px;
}

.filter-buttons button {
  padding: 5px 12px;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
  cursor: pointer;
  font-size: 0.8rem;
  transition: 0.2s;
}

.filter-buttons button:hover {
  background: #ede9fe;
  color: #4c1d95;
}

/* ===== Animation ===== */
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}
</style>