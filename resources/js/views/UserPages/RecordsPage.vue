<template>
  <div class="records-container">
    <svg width="0" height="0" style="position: absolute;">
      <defs>
        <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" stop-color="#4f8cff" />
          <stop offset="100%" stop-color="#7c3aed" />
        </linearGradient>
      </defs>
    </svg>

    <div class="header-flex">
      <div class="title-wrap">
        <div class="icon-box">
          <svg class="icon" viewBox="0 0 24 24">
            <path d="M13 2L2 14h9l-1 8L22 10h-9l1-8z" />
          </svg>
        </div>
        <div>
          <h3 class="records-title">Performance Analytics</h3>
          <p class="subtitle">Track your quiz progress and accuracy</p>
        </div>
      </div>

      <div class="filter-bar">
        <div class="input-group">
          <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input v-model="searchQuery" placeholder="Search quiz name..." />
          <button v-if="searchQuery" @click="searchQuery = ''" class="clear-btn">✕</button>
        </div>
        <input type="date" v-model="dateFilter" class="date-input" />
      </div>
    </div>

    <div v-if="loading" class="loading">
      <div class="spinner"></div>
    </div>

    <template v-else>
      <div v-if="filteredRecords.length" class="main-grid">
        <div class="charts-wrapper">
          <div class="chart-card">
            <h4>Success Rate</h4>
            <div class="chart-container">
              <Doughnut :data="chartData" :options="chartOptions" />
            </div>
          </div>

          <div class="chart-card">
            <h4>Score Trend (Recent)</h4>
            <div class="chart-container">
              <Line :data="lineData" :options="lineOptions" />
            </div>
          </div>
        </div>

        <div class="stats-summary">
          <div class="stat-box">
            <label>Avg. Score</label>
            <strong>{{ averageScore }}%</strong>
          </div>
          <div class="stat-box">
            <label>Personal Best</label>
            <strong>{{ maxScore }}/10</strong>
          </div>
          <div class="stat-box">
            <label>Needs Review</label>
            <strong class="text-fail">{{ needsImprovement }}</strong>
          </div>
          <div class="stat-box">
            <label>Total Quizzes</label>
            <strong>{{ filteredRecords.length }}</strong>
          </div>
        </div>
      </div>

      <div v-if="filteredRecords.length" class="table-wrapper">
        <!-- Fixed Header -->
        <table class="records-table header-table">
          <thead>
            <tr>
              <th class="text-left col-date">Date</th>
              <th class="text-left col-quiz">Quiz Name</th>
              <th class="col-score">Score</th>
              <th class="col-accuracy">Accuracy</th>
              <th class="col-time">Time</th>
              <th class="col-action"></th>
            </tr>
          </thead>
        </table>
        <!-- Scrollable Body -->
        <div class="tbody-scroll">
          <table class="records-table body-table">
            <tbody>
              <tr v-for="r in filteredRecords" :key="r.id">
                <td class="text-left date-cell col-date">{{ formatDate(r.created_at) }}</td>
                <td class="text-left col-quiz">
                  <div class="quiz-info">
                    <span class="quiz-name">{{ r.quiz_title || 'Untitled Quiz' }}</span>
                    <span class="attempt-tag" v-if="r.attempt">Attempt #{{ r.attempt }}</span>
                  </div>
                </td>
                <td class="col-score">
                  <span :class="['badge', r.score >= 7 ? 'pass' : 'fail']">
                    {{ r.score }} / 10
                  </span>
                </td>
                <td class="accuracy-cell col-accuracy">{{ (r.score / 10 * 100).toFixed(0) }}%</td>
                <td class="time-cell col-time">{{ r.duration || '—' }}</td>
                <td class="col-action">
                  <button class="btn-view" @click="openView(r)">Details</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div v-if="!filteredRecords.length" class="empty">
        <p>No records found.</p>
        <small>
          {{ records.length ? 'Try adjusting your search or date filter.' : 'Complete a quiz to see your data here.' }}
        </small>
      </div>

      <RecordModal v-model="showModal" :record="selectedRecord" />
    </template>
  </div>
</template>

<script setup>
import {
  Chart as ChartJS, Title, Tooltip, Legend, ArcElement,
  LineElement, CategoryScale, LinearScale, PointElement, Filler
} from 'chart.js'
import { ref, computed, onMounted } from "vue"
import { Doughnut, Line } from 'vue-chartjs'
import axios from "axios"
import { useUser } from "@/composables/useUser"
import RecordModal from "@/components/UserSide/RecordModal.vue"

ChartJS.register(
  Title, Tooltip, Legend, ArcElement,
  LineElement, CategoryScale, LinearScale, PointElement, Filler
)

const { fetchUser } = useUser()
const records = ref([])
const searchQuery = ref("")
const dateFilter = ref("")
const loading = ref(true)
const selectedRecord = ref(null)
const showModal = ref(false)

const openView = (record) => {
  selectedRecord.value = record
  showModal.value = true
}

const formatDate = (d) =>
  new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })

const toDateKey = (d) => {
  const date = new Date(d)
  const y = date.getFullYear()
  const m = String(date.getMonth() + 1).padStart(2, '0')
  const day = String(date.getDate()).padStart(2, '0')
  return `${y}-${m}-${day}`
}

const filteredRecords = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  const df = dateFilter.value
  return records.value.filter(r => {
    const matchSearch = !q || (r.quiz_title || '').toLowerCase().includes(q)
    const matchDate = !df || toDateKey(r.created_at) === df
    return matchSearch && matchDate
  })
})

const averageScore = computed(() => {
  if (!filteredRecords.value.length) return 0
  const total = filteredRecords.value.reduce((s, r) => s + r.score, 0)
  return ((total / (filteredRecords.value.length * 10)) * 100).toFixed(1)
})

const maxScore = computed(() =>
  filteredRecords.value.length
    ? Math.max(...filteredRecords.value.map(r => r.score))
    : 0
)

const needsImprovement = computed(() =>
  filteredRecords.value.filter(r => r.score < 7).length
)

const chartData = computed(() => {
  const passed = filteredRecords.value.filter(r => r.score >= 7).length
  return {
    labels: ['Passed', 'Needs Review'],
    datasets: [{
      data: [passed, filteredRecords.value.length - passed],
      backgroundColor: ['#6366f1', '#f43f5e'],
      borderWidth: 0,
      hoverOffset: 4,
    }]
  }
})

const lineData = computed(() => {
  const sorted = [...filteredRecords.value].sort(
    (a, b) => new Date(a.created_at) - new Date(b.created_at)
  )
  return {
    labels: sorted.map(r =>
      new Date(r.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
    ),
    datasets: [{
      label: 'Score',
      data: sorted.map(r => r.score),
      borderColor: '#6366f1',
      borderWidth: 3,
      backgroundColor: (context) => {
        if (!context.chart.chartArea) return
        const { ctx, chartArea } = context.chart
        const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom)
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)')
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)')
        return gradient
      },
      fill: true,
      tension: 0.4,
      pointRadius: 1,
      pointHoverRadius: 6,
      pointHitRadius: 20,
      pointBackgroundColor: '#6366f1',
      pointBorderColor: 'black',
      pointBorderWidth: 2,
    }]
  }
})

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
      labels: { boxWidth: 12, usePointStyle: true }
    }
  }
}

const lineOptions = {
  responsive: true,
  maintainAspectRatio: false,
  interaction: { intersect: false, mode: 'index' },
  plugins: {
    legend: { display: true },
    tooltip: {
      enabled: true,
      backgroundColor: '#1e293b',
      padding: 5,
      borderRadius: 10,
      titleColor: '#94a3b8',
      bodyColor: '#fff',
      bodyFont: { weight: 'bold', size: 14 },
      displayColors: false,
      callbacks: {
        label: (context) => `Score: ${context.parsed.y} / 10`
      }
    }
  },
  scales: {
    y: {
      min: 0,
      max: 10,
      ticks: { stepSize: 2, color: '#94a3b8', font: { size: 11 } },
      grid: { color: 'rgba(241, 245, 249, 1)', drawBorder: false }
    },
    x: {
      ticks: { color: '#94a3b8', font: { size: 11 }, maxRotation: 0 },
      grid: { display: false }
    }
  }
}

const fetchRecords = async () => {
  loading.value = true
  try {
    const res = await axios.get('/api/records')
    records.value = res.data.results || []
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await fetchUser()
  fetchRecords()
})
</script>

<style scoped>
*,
*::before,
*::after {
  box-sizing: border-box;
}

/* ── CONTAINER ── */
.records-container {
  width: 100%;
  min-height: 100%;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  padding: 1.5rem;
  background-color: #ffffff;
  border-radius: 1rem;
  box-shadow:
    0 10px 25px -5px rgba(0, 0, 0, 0.05),
    0 8px 10px -6px rgba(0, 0, 0, 0.05);
}

/* ── HEADER ── */
.header-flex {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-start;
  justify-content: space-between;
  gap: 0.75rem;
}

.title-wrap {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex: 1 1 auto;
  min-width: 0;
}

.icon-box {
  flex-shrink: 0;
}

.icon {
  width: 1.5rem;
  height: 1.5rem;
  fill: url(#grad);
}

.records-title {
  font-size: clamp(1rem, 2.5vw, 1.25rem);
  font-weight: 800;
  color: #1e293b;
  margin: 0;
  line-height: 1.2;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.subtitle {
  font-size: 0.875rem;
  color: #64748b;
  margin: 0.25rem 0 0;
}

/* ── FILTER BAR ── */
.filter-bar {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  align-items: center;
  flex: 1 1 auto;
  min-width: 0;
  justify-content: flex-end;
}

.input-group {
  position: relative;
  flex: 1 1 160px;
  min-width: 0;
  max-width: 260px;
}

.input-group input {
  width: 100%;
  padding: 0.5rem 2.25rem 0.5rem 2.25rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  transition: border-color 0.2s;
}

.input-group input:focus {
  outline: none;
  border-color: #6366f1;
}

.input-icon {
  position: absolute;
  left: 0.65rem;
  top: 50%;
  transform: translateY(-50%);
  width: 1rem;
  height: 1rem;
  color: #94a3b8;
  pointer-events: none;
}

.clear-btn {
  position: absolute;
  right: 0.5rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #94a3b8;
  cursor: pointer;
  font-size: 0.75rem;
  padding: 0;
  line-height: 1;
}

.date-input {
  flex: 0 1 auto;
  padding: 0.5rem 0.625rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  color: #475569;
  font-size: 0.875rem;
}

/* ── MAIN GRID ── */
.main-grid {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

/* ── CHARTS ── */
.charts-wrapper {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

.chart-card {
  background-color: #f8fafc;
  border: 1px solid #f1f5f9;
  border-radius: 0.75rem;
  padding: 1rem;
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.chart-card h4 {
  margin: 0 0 0.75rem;
  font-size: 0.875rem;
  font-weight: 600;
  color: #475569;
}

.chart-container {
  width: 100%;
  height: 200px;
  position: relative;
}

/* ── STATS ── */
.stats-summary {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0.75rem;
}

.stat-box {
  background-color: #ffffff;
  border: 1px solid #f1f5f9;
  border-radius: 0.75rem;
  padding: 0.875rem 0.75rem;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  transition: transform 0.2s, box-shadow 0.2s;
}

.stat-box:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}

.stat-box label {
  font-size: 0.7rem;
  font-weight: 600;
  text-transform: uppercase;
  color: #94a3b8;
  letter-spacing: 0.04em;
}

.stat-box strong {
  font-size: 1.2rem;
  color: #1e293b;
  font-weight: 700;
}

.text-fail {
  color: #f43f5e;
}

/* ── TABLE WRAPPER ── */
.table-wrapper {
  width: 100%;
  border: 1px solid #f1f5f9;
  overflow: hidden;
}

/* ── SHARED TABLE STYLES ── */
.records-table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

/* ── COLUMN WIDTHS (shared between both tables) ── */
.col-date {
  width: 90px;
}

.col-quiz {
  width: auto;
}

.col-score {
  width: 90px;
}

.col-accuracy {
  width: 80px;
}

.col-time {
  width: 70px;
}

.col-action {
  width: 80px;
}

/* ── FIXED HEADER TABLE ── */
.header-table {
  display: table;
  min-width: 520px;
}

.header-table thead tr th {
  background-color: #3c3d91;
  color: #ffffff;
  padding: 0.75rem 0.875rem;
  font-size: 0.72rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  text-align: center;
  white-space: nowrap;
}

.header-table thead tr th.text-left {
  text-align: left;
}

/* ── SCROLLABLE BODY ── */
.tbody-scroll {
  max-height: 350px;
  overflow-y: auto;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.body-table {
  display: table;
  width: 98.5%;
  min-width: 520px;
}

.records-table td {
  padding: 0.5rem;
  text-align: center;
  border-bottom: 1px solid #f8fafc;
  color: #334155;
  font-size: 0.875rem;
}

.records-table tr:last-child td {
  border-bottom: none;
}

.records-table tr:hover td {
  background-color: #eee;
}

.text-left {
  text-align: left !important;
}

.quiz-info {
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
}

.quiz-name {
  font-weight: 500;
  color: #1e293b;
  font-size: 0.875rem;
}

.attempt-tag {
  font-size: 0.7rem;
  color: #94a3b8;
}

.date-cell {
  white-space: nowrap;
  font-size: 0.82rem;
  color: #64748b;
}

.accuracy-cell,
.time-cell {
  white-space: nowrap;
  font-size: 0.85rem;
}

/* ── BADGE ── */
.badge {
  display: inline-block;
  padding: 0.3rem 0.65rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 700;
  white-space: nowrap;
}

.pass {
  background-color: #ecfdf5;
  color: #10b981;
}

.fail {
  background-color: #fff1f2;
  color: #f43f5e;
}

/* ── BUTTON ── */
.btn-view {
  background-color: #6366f1;
  color: #ffffff;
  border: none;
  padding: 0.4rem 0.875rem;
  border-radius: 0.5rem;
  font-size: 0.78rem;
  font-weight: 600;
  cursor: pointer;
  white-space: nowrap;
  transition: background-color 0.2s;
}

.btn-view:hover {
  background-color: #4f46e5;
}

/* ── LOADING / EMPTY ── */
.loading {
  padding: 3rem;
  display: flex;
  justify-content: center;
}

.spinner {
  width: 2.25rem;
  height: 2.25rem;
  border: 3px solid #f1f5f9;
  border-top-color: #6366f1;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

.empty {
  text-align: center;
  padding: 3rem 1rem;
  color: #64748b;
}

.empty p {
  margin: 0 0 0.25rem;
  font-weight: 500;
}

.empty small {
  font-size: 0.8rem;
  color: #94a3b8;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* ── SCROLLBAR STYLING ── */
.tbody-scroll::-webkit-scrollbar {
  width: 5px;
  height: 5px;
}

.tbody-scroll::-webkit-scrollbar-track {
  background: #f8fafc;
}

.tbody-scroll::-webkit-scrollbar-thumb {
  background: #e2e8f0;
  border-radius: 99px;
}

.tbody-scroll::-webkit-scrollbar-thumb:hover {
  background: #cbd5e1;
}

/* ── RESPONSIVE ── */

/* LG: ≤992px */
@media (max-width: 991.98px) {
  .records-container {
    padding: 1.25rem;
  }

  .chart-container {
    height: 180px;
  }
}

/* MD: ≤768px */
@media (max-width: 767.98px) {
  .records-container {
    padding: 1rem;
    gap: 1rem;
  }

  .header-flex {
    flex-direction: column;
    align-items: stretch;
  }

  .filter-bar {
    justify-content: flex-start;
  }

  .input-group {
    flex: 1 1 auto;
    max-width: 100%;
  }

  .date-input {
    width: 100%;
  }

  .charts-wrapper {
    grid-template-columns: repeat(2, 1fr);
  }

  .chart-container {
    height: 170px;
  }

  .stats-summary {
    grid-template-columns: repeat(2, 1fr);
    gap: 0.6rem;
  }

  .stat-box {
    padding: 0.75rem;
  }

  .stat-box strong {
    font-size: 1.1rem;
  }
}

/* SM: ≤576px */
@media (max-width: 575.98px) {
  .records-container {
    padding: 0.875rem;
    gap: 0.875rem;
    border-radius: 0.75rem;
  }

  .subtitle {
    font-size: 0.8rem;
  }

  .filter-bar {
    flex-direction: column;
    gap: 0.5rem;
  }

  .input-group,
  .date-input {
    width: 100%;
    max-width: 100%;
    flex: 1 1 auto;
  }

  .charts-wrapper {
    grid-template-columns: 1fr;
    gap: 0.75rem;
  }

  .chart-container {
    height: 190px;
  }

  .stats-summary {
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
  }

  .stat-box {
    padding: 0.7rem 0.625rem;
  }

  .stat-box label {
    font-size: 0.65rem;
  }

  .stat-box strong {
    font-size: 1rem;
  }

  .records-table th,
  .records-table td {
    padding: 0.65rem 0.625rem;
    font-size: 0.8rem;
  }

  .btn-view {
    padding: 0.35rem 0.65rem;
    font-size: 0.74rem;
  }
}

/* XS: ≤400px */
@media (max-width: 399.98px) {
  .records-container {
    padding: 0.75rem;
    gap: 0.75rem;
  }

  .stats-summary {
    gap: 0.4rem;
  }

  .stat-box {
    padding: 0.6rem 0.5rem;
  }

  .stat-box strong {
    font-size: 0.95rem;
  }

  .chart-container {
    height: 170px;
  }

  .records-table th,
  .records-table td {
    padding: 0.55rem 0.5rem;
    font-size: 0.76rem;
  }
}
</style>