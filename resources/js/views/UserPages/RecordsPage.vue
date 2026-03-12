<template>
  <div class="records-page">
    <TopBar />

    <main class="main-content">
      <div class="records-container">
        <h3 class="records-title">Performance Analytics</h3>

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
import { ref, computed, onMounted } from "vue"
import axios from "axios"
import { useUser } from "@/composables/useUser"
import TopBar from "../../components/TopBar.vue"
import { Doughnut } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale } from 'chart.js'

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
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

onMounted(async () => {
  await fetchUser()
  fetchRecords()
})
</script>

<style scoped>
/* === PAGE CONTAINER === */
.records-page {
  background: #f5f5f8;
  min-height: 100vh;
  padding-bottom: 2rem;
  font-family: "Inter", sans-serif;
}

.main-content {
  max-width: 1000px;
  margin: 2rem auto;
  padding: 0 1rem;
}

.records-container {
  background: #ffffff;
  border-radius: 16px;
  padding: 2rem 1.8rem;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
  animation: fadeIn 0.3s ease;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* === ANALYTICS GRID === */
.stats-grid {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 20px;
  margin-bottom: 2rem;
}

.chart-card {
  background: #fcfaff;
  padding: 20px;
  border-radius: 12px;
  border: 1px solid #e3e0ff;
}

.chart-card h4 {
  text-align: center;
  margin-bottom: 15px;
  color: #4b32a8;
}

.chart-container {
  height: 200px;
  position: relative;
}

.stats-summary {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.stat-box {
  background: white;
  padding: 15px 20px;
  border-radius: 12px;
  border-left: 4px solid #4b32a8;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.stat-box .label { color: #666; font-size: 0.9rem; }
.stat-box .value { font-size: 1.2rem; font-weight: 800; color: #4b32a8; }

/* === TABLE & SEARCH === */
.records-title {
  font-size: 1.6rem;
  font-weight: 800;
  color: #4b32a8;
  text-align: center;
}

.sub-title {
  text-align: left;
  font-size: 1.2rem;
  margin-top: 1rem;
}

.filter-bar {
  position: relative;
  width: 100%;
  max-width: 400px;
}

.search-icon {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #999;
}

.filter-bar input {
  width: 100%;
  padding: 10px 10px 10px 35px;
  border-radius: 10px;
  border: 1.5px solid #ddd;
  outline: none;
}

.table-wrapper {
  overflow-x: auto;
  border: 1px solid #e3e0ff;
  border-radius: 12px;
}

.records-table {
  width: 100%;
  border-collapse: collapse;
}

.records-table thead {
  background: #4b32a8;
  color: white;
}

.records-table th, .records-table td {
  padding: 14px;
  text-align: left;
}

.records-table tbody tr:nth-child(even) { background: #fcfaff; }
.records-table tbody tr:hover { background: #f0eeff; }

.score-badge {
  padding: 4px 10px;
  border-radius: 20px;
  font-weight: 700;
  font-size: 0.85rem;
}

.score-badge.pass { background: #e6ffed; color: #218838; }
.score-badge.fail { background: #ffeeee; color: #dc3545; }

.empty-msg { text-align: center; color: #888; font-style: italic; }

@media (max-width: 768px) {
  .stats-grid { grid-template-columns: 1fr; }
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>