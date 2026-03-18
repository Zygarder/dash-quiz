<template>
  <div class="dashboard-wrapper">

    <!-- 🔔 Notification -->
    <div v-if="notification" class="notification">
      {{ notification }}
    </div>

    <!-- Contents -->
    <div v-if="stats">
      <!-- 📊 Cards -->
      <section class="admin-stats">
        <div class="admin-card">
          <h4>Total Users</h4>
          <p>{{ stats.total_users }}</p>
        </div>

        <div class="admin-card">
          <h4>Total Quizzes</h4>
          <p>{{ stats.total_quizzes }}</p>
        </div>

        <div class="admin-card">
          <h4>Active Users</h4>
          <p>{{ stats.active_users }}</p>
        </div>
      </section>

      <div class="chart-leaderboard-grid">
      
        <!-- Chart -->
        <section class="chart-section">
          <h3 class="section-title">System Analytics</h3>
          <canvas ref="chartCanvas"></canvas>
        </section>

        <!-- Leaderboard -->
        <section class="leaderboard">
          <h3 class="section-title">Top Dashers</h3>

          <div v-if="stats.top_users?.length">
            <div v-for="user in stats.top_users" :key="user.id" class="leader-row">
              <span>{{ user.first_name }} {{ user.last_name }}</span>
              <strong>{{ user.total_score }}</strong>
            </div>
          </div>

          <div v-else class="empty-state">
            No leaderboard data.
          </div>
        </section>
      </div>

      <!-- 📅 Logs Filter -->
      <section class="admin-details">
        <div class="logs-table">

          <div class="logs-header">
            Recent Activity

            <div class="filter-buttons">
              <button @click="filterType = 'today'">Today</button>
              <button @click="filterType = 'week'">This Week</button>
            </div>
          </div>

          <div v-if="filteredLogs.length">
            <div v-for="log in filteredLogs" :key="log.id" class="logs-row">
              <span>{{ log.description }}</span>
              <small>{{ formatDate(log.created_at) }}</small>
            </div>
          </div>

          <div v-else class="logs-row empty">
            No logs found.
          </div>
        </div>
      </section>
    </div>

    <!-- 🔄 Loading -->
    <div v-else class="loading-state">
      <div class="spinner"></div>
      <h3>Loading dashboard...</h3>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch, nextTick } from 'vue'
import axios from 'axios'
import Chart from 'chart.js/auto'

const stats = ref(null)
const chartCanvas = ref(null)
let chartInstance = null

const filterType = ref('today')
const notification = ref('')
let lastUserCount = 0

// Fetch data
const fetchStats = async () => {
  try {
    const { data } = await axios.get('/api/admin/dashboard')
    const newData = data.data || data

    // 🔔 Detect new users
    if (stats.value && newData.total_users > lastUserCount) {
      notification.value = "🎉 New user registered!"
      setTimeout(() => notification.value = '', 3000)
    }

    stats.value = newData
    lastUserCount = newData.total_users

    await nextTick()
    renderChart()

  } catch (e) {
    console.error("Dashboard error:", e)
  }
}

// 📊 Chart.js (Separated Bars - Clean)
const renderChart = () => {
  if (!chartCanvas.value) return

  if (chartInstance) {
    chartInstance.destroy()
  }

  chartInstance = new Chart(chartCanvas.value, {
    type: 'bar',
    data: {
      labels: ['Overview'], // single group

      datasets: [
        {
          label: 'Total Users',
          data: [stats.value.total_users],
          backgroundColor: '#3b82f6',
        },
        {
          label: 'Total Quizzes',
          data: [stats.value.total_quizzes],
          backgroundColor: '#8b5cf6',
        },
        {
          label: 'Active Users',
          data: [stats.value.active_users],
          backgroundColor: '#22c55e',
        }
      ]
    },

    options: {
      responsive: true,
      maintainAspectRatio: false,

      plugins: {
        legend: {
          display: true,
          labels: {
            usePointStyle: true,
            pointStyle: 'rect',
            padding: 15
          }
        },

        tooltip: {
          backgroundColor: '#1e293b',
          titleColor: '#fff',
          bodyColor: '#e2e8f0',
          cornerRadius: 8
        }
      },

      scales: {
        x: {
          grid: { display: false },
          ticks: { display: true } // cleaner (no "Overview" text)
        },
        y: {
          beginAtZero: true,
          grid: { color: '#f1f5f9' },
          ticks: { color: '#64748b' }
        }
      }
    }
  })
}

// 📅 Filter Logs
const filteredLogs = computed(() => {
  if (!stats.value?.logs) return []

  const now = new Date()

  return stats.value.logs.filter(log => {
    const logDate = new Date(log.created_at)

    if (filterType.value === 'today') {
      return logDate.toDateString() === now.toDateString()
    }

    if (filterType.value === 'week') {
      const weekAgo = new Date()
      weekAgo.setDate(now.getDate() - 7)
      return logDate >= weekAgo
    }

    return true
  })
})

// Format date
const formatDate = (date) => {
  return new Date(date).toLocaleString()
}

onMounted(() => {
  fetchStats() // get data
  setInterval(fetchStats, 10000) // re fetch
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
.leaderboard,
.admin-details {
  background: white;
  border-radius: 16px;
  padding: 1.5rem;
  border: 1px solid #f1f5f9;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
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
  margin-bottom: 1rem;
}

/* ===== Chart ===== */
.chart-section canvas {
  max-height: 200px;
}


/* ===== Leaderboard ===== */
.leader-row {
  display: flex;
  justify-content: space-between;
  padding: 0.9rem 0.5rem;
  border-bottom: 1px solid #f1f5f9;
  font-size: 0.9rem;
}

.leader-row strong {
  color: #7c3aed;
}

/* ===== Logs ===== */
.logs-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  font-weight: 600;
}

.logs-row {
  display: flex;
  justify-content: space-between;
  padding: 0.9rem 0.5rem;
  border-bottom: 1px solid #f8fafc;
  font-size: 0.9rem;
}

.logs-row small {
  color: #94a3b8;
}

.logs-row.empty {
  justify-content: center;
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