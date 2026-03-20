<template>
  <div class="records-page">
    <TopBar />

    <main class="main-content">
      <div class="records-container">
        <h3 class="records-title">Performance Analytics</h3>
        <p class="subtitle">Track your quiz progress and performance</p>

        <div class="stats-grid" v-if="records.length > 0">
          <div class="chart-card">
            <h4>Score Distribution</h4>
            <div class="chart-container">
              <Doughnut :data="chartData" :options="chartOptions" />
            </div>
          </div>
          <div class="stats-summary">
            <div class="stat-box">
              <span class="label">Average Score</span>
              <span class="value">{{ averageScore }}%</span>
            </div>
            <div class="stat-box">
              <span class="label">Quizzes Taken</span>
              <span class="value">{{ records.length }}</span>
            </div>
            <div class="stat-box">
              <span class="label">Highest Score</span>
              <span class="value">{{ maxScore }}/10</span>
            </div>
          </div>
        </div>

        <h3 class="records-title sub-title">Detailed History</h3>

        <div class="filter-bar">
          <i class="fas fa-search search-icon"></i>
          <input type="text" v-model="searchQuery" placeholder="Search topic or score..." />
        </div>

        <div class="table-wrapper">
          <table class="records-table">
            <thead>
              <tr>
                <th>Date Taken</th>
                <th>Topics</th>
                <th>Scores</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="record in filteredRecords" :key="record.id">
                <td>{{ formatDate(record.created_at) }}</td>
                <td>{{ record.quiz_description }}</td>
                <td>
                  <span :class="['score-badge', record.score >= 7 ? 'pass' : 'fail']">
                    {{ record.score }} / 10
                  </span>
                </td>
              </tr>
              <tr v-if="filteredRecords.length === 0">
                <td colspan="3" class="empty-msg">No records found.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale } from 'chart.js'
import { ref, computed, onMounted } from "vue"
import { useUser } from "@/composables/useUser"
import TopBar from "@/components/UserSide/TopBar.vue"
import { Doughnut } from 'vue-chartjs'
import axios from "axios"

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale)

const { fetchUser } = useUser()
const records = ref([])
const searchQuery = ref("")

/*
|--------------------------------------------------------------------------
| Analytics Calculations
|--------------------------------------------------------------------------
*/
const averageScore = computed(() => {
  if (!records.value.length) return 0
  const total = records.value.reduce((acc, curr) => acc + curr.score, 0)
  return ((total / (records.value.length * 10)) * 100).toFixed(1)
})

const maxScore = computed(() => {
  return records.value.length ? Math.max(...records.value.map(r => r.score)) : 0
})

const chartData = computed(() => {
  const passed = records.value.filter(r => r.score >= 7).length
  const failed = records.value.length - passed

  return {
    labels: ['Passed (7-10)', 'Needs Review (<7)'],
    datasets: [{
      backgroundColor: ['#4b32a8', '#ffccd5'],
      hoverBackgroundColor: ['#3a2585', '#ffb3c1'],
      data: [passed, failed],
      borderWidth: 0,
      hoverOffset: 10
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

/*
|--------------------------------------------------------------------------
| Actions & Data Fetching
|--------------------------------------------------------------------------
*/
const fetchRecords = async () => {
  try {
    const { data } = await axios.get("api/records")
    records.value = data.results
  } catch (err) {
    console.error('Failed to fetch records:', err)
  }
}

const filteredRecords = computed(() => {
  if (!searchQuery.value) return records.value
  const query = searchQuery.value.toLowerCase()
  return records.value.filter(record =>
    record.quiz_title.toLowerCase().includes(query) ||
    record.score.toString().includes(query)
  )
})

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString(undefined, {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  })
}

onMounted(async () => {
  await fetchUser()
  fetchRecords()
})
</script>

<style scoped>
:root {
  --primary: #6366f1;
  --bg: #f9fafb;
  --card: #ffffff;
  --text: #0f172a;
  --muted: #6b7280;
  --border: #eef2f7;
}

/* === PAGE === */
.records-page {
  background: var(--bg);
  min-height: 100vh;
  font-family: "Inter", sans-serif;
}

.main-content {
  max-width: 1000px;
  margin: 2rem auto;
  padding: 0 1rem;
}

/* === CARD CONTAINER === */
.records-container {
  background: var(--card);
  border-radius: 18px;
  padding: 2rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* === TITLES === */
.records-title {
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--text);
}

.subtitle {
  font-size: 0.9rem;
  color: var(--muted);
  margin-top: -10px;
}

.sub-title {
  font-size: 1.1rem;
  font-weight: 700;
  margin-top: 10px;
}

/* === GRID === */
.stats-grid {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 20px;
}

/* === CHART CARD === */
.chart-card {
  background: #fafafa;
  padding: 20px;
  border-radius: 14px;
}

.chart-card h4 {
  text-align: center;
  font-weight: 600;
  margin-bottom: 10px;
}

.chart-container {
  height: 200px;
}

/* === STATS === */
.stat-box {
  background: #fafafa;
  padding: 16px;
  border-radius: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.stat-box .label {
  color: var(--muted);
  font-size: 0.85rem;
}

.stat-box .value {
  font-weight: 700;
  font-size: 1.2rem;
  color: var(--text);
}

/* === SEARCH === */
.filter-bar {
  position: relative;
  max-width: 320px;
}

.filter-bar input {
  width: 100%;
  padding: 10px 12px 10px 35px;
  border-radius: 999px;
  border: 1px solid var(--border);
  outline: none;
  transition: 0.2s;
}

.filter-bar input:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

.search-icon {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--muted);
}

/* === TABLE === */
.table-wrapper {
  overflow-x: auto;
}

.records-table {
  width: 100%;
  border-collapse: collapse;
}

/* header */
.records-table thead {
  background: transparent;
}

.records-table th {
  text-align: left;
  font-size: 0.75rem;
  color: var(--muted);
  text-transform: uppercase;
  padding: 12px;
}

/* body */
.records-table td {
  padding: 14px 12px;
  border-top: 1px solid var(--border);
}

/* hover effect */
.records-table tbody tr {
  transition: 0.15s;
}

.records-table tbody tr:hover {
  background: #f9fafb;
}

/* === BADGES === */
.score-badge {
  padding: 5px 12px;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 600;
}

.score-badge.pass {
  background: rgba(16, 185, 129, 0.1);
  color: #10b981;
}

.score-badge.fail {
  background: rgba(244, 63, 94, 0.1);
  color: #f43f5e;
}

/* === EMPTY === */
.empty-msg {
  text-align: center;
  padding: 30px;
  color: var(--muted);
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>