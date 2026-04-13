<template>
  <div class="records-page">
    <div class="records-container">

      <!-- HEADER -->
      <div>
        <h3 class="records-title">Performance Analytics</h3>
        <p class="subtitle">Track your quiz progress</p>
      </div>

      <!-- LOADING -->
      <div v-if="loading" class="loading">
        <div class="spinner"></div>
      </div>

      <!-- CONTENT -->
      <template v-else>

        <!-- STATS -->
        <div class="stats-grid" v-if="records.length">

          <div class="chart-card">
            <h4>Score Distribution</h4>
            <div class="chart-container">
              <Doughnut :data="chartData" :options="chartOptions" />
            </div>
          </div>

          <div class="stats-summary">
            <div class="stat-box">
              <span>Average</span>
              {{ averageScore }}%
            </div>

            <div class="stat-box">
              <span>Quizzes</span>
              {{ records.length }}
            </div>

            <div class="stat-box">
              <span>Best</span>
              {{ maxScore }} / 10
            </div>
          </div>

        </div>

        <!-- SEARCH -->
        <div class="filter-bar">
          <i class="fas fa-search"></i>
          <input v-model="searchQuery" placeholder="Search..." />
        </div>

        <!-- EMPTY -->
        <div v-if="!records.length" class="empty">
          <p>No records yet 📊</p>
        </div>

        <!-- TABLE -->
        <div v-else class="table-wrapper">
          <table class="records-table">
            <thead>
              <tr>
                <th>Dates</th>
                <th>Quizzes Titles</th>
                <th>Scores</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="record in filteredRecords" :key="record.id">
                <td>{{ formatDate(record.created_at) }}</td>
                <td>{{ record.quiz_title || 'Untitled' }}</td>
                <td>
                  <span :class="['badge', record.score >= 7 ? 'pass' : 'fail']">
                    {{ record.score }} / 10
                  </span>
                </td>
              </tr>

              <tr v-if="filteredRecords.length === 0">
                <td colspan="3" class="empty-msg">No match found</td>
              </tr>
            </tbody>
          </table>
        </div>

      </template>
    </div>
  </div>
</template>

<script setup>
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js'
import { ref, computed, onMounted } from "vue"
import { Doughnut } from 'vue-chartjs'
import axios from "axios"
import { useUser } from "@/composables/useUser"

ChartJS.register(Title, Tooltip, Legend, ArcElement)

const { fetchUser } = useUser()

const records = ref([])
const searchQuery = ref("")
const loading = ref(true)

/* ===============================
   ANALYTICS
================================ */
const averageScore = computed(() => {
  if (!records.value.length) return 0
  const total = records.value.reduce((sum, r) => sum + r.score, 0)
  return ((total / (records.value.length * 10)) * 100).toFixed(1)
})

const maxScore = computed(() => {
  return records.value.length
    ? Math.max(...records.value.map(r => r.score))
    : 0
})

const chartData = computed(() => {
  const passed = records.value.filter(r => r.score >= 7).length
  const failed = records.value.length - passed

  return {
    labels: ['Passed', 'Needs Review'],
    datasets: [{
      data: [passed, failed],
      backgroundColor: ['#6366f1', '#f43f5e'],
      borderWidth: 0
    }]
  }
})

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { position: 'bottom' }
  }
}

/* ===============================
   FETCH
================================ */
const fetchRecords = async () => {
  loading.value = true
  try {
    const res = await axios.get('/api/records')
    records.value = res.data.results || []
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
}

/* ===============================
   FILTER
================================ */
const filteredRecords = computed(() => {
  const query = searchQuery.value.toLowerCase()

  return records.value.filter(record => {
    const title = record.quiz_title?.toLowerCase() || ''
    const score = record.score?.toString() || ''
    return title.includes(query) || score.includes(query)
  })
})

/* ===============================
   FORMAT
================================ */
const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

/* ===============================
   INIT
================================ */
onMounted(async () => {
  await fetchUser()
  fetchRecords()
})
</script>

<style scoped>
* {
  box-sizing: border-box;
}
.records-page {
  width: 100%;
}

.records-container {
  width: 100%;
  max-width: 100%;
  overflow: hidden;

  background: #fff;
  border-radius: 16px;

  padding: clamp(1rem, 2.5vw, 1.5rem);

  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
}

/* HEADER */
.records-title {
  font-size: clamp(1.2rem, 2vw, 1.5rem);
  font-weight: 800;
  color: #1e1b4b;
}

.subtitle {
  font-size: 0.85rem;
  color: #6b7280;
}

/* LOADING */
.loading {
  display: flex;
  justify-content: center;
  padding: 30px;
}

.spinner {
  width: 35px;
  height: 35px;
  border: 4px solid #eee;
  border-top: 4px solid #6366f1;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* GRID */
.stats-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin: 20px 0;
}

/* CHART */
.chart-container {
  height: 230px;

}

.chart-container canvas {
  width: 100px;
}

/* STATS */
.stats-summary {
  display: flex;
  flex-direction: column;
  box-shadow: #23204a 0px 2px 2px 1px;
  justify-content: space-evenly;
  background-color: #3c3d91;
  padding: 10px;
  border-radius: 10px;
}

.stat-box {
  background: #f9fafb;
  padding: 10px;
  border-radius: 5px;
  display: flex;
  justify-content: space-between;
}

.stat-box span {
  font-weight: bold;
}

/* SEARCH */
.filter-bar {
  width: 100%;
  position: relative;
  display: flex;
  margin-left: 10px;
}

.filter-bar input {
  max-width: 100%;
  padding: 8px 10px 8px 30px;
}

.filter-bar i {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
}

/* TABLE */
.table-wrapper {
  overflow-x: auto;
  margin-top: 20px;
  padding: 10px;
}

.records-table {
  width: 100%;
  background-color: #1e1b4b;
}


.records-table tr {
  padding: 10px;
  text-align: center;
  background: white;
}

.records-table td,
.records-table th {
  padding: 10px;
}

.records-table th {
  color: #fff;
  background-color: #3c3d91;
}

/* BADGE */
.badge {
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 0.75rem;
}

.pass {
  background: rgba(16, 185, 129, 0.1);
  color: #10b981;
}

.fail {
  background: rgba(244, 63, 94, 0.1);
  color: #f43f5e;
}

/* EMPTY */
.empty {
  text-align: center;
  padding: 30px;
  color: #9ca3af;
}

@media (max-width: 320px) {}

@media (max-width: 425px) {}

/* MOBILE */
@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }

  .records-table th {
    font-size: 0.75rem;
  }

  .records-table td {
    font-size: 0.85rem;
  }
}
</style>