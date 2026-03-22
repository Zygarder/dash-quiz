<template>
  <section class="admin-section">

    <!-- Header -->
    <div class="header-row">
      <h3 class="section-title">Dasher Records</h3>
      <p class="section-subtitle">Viewing all completed quiz attempts</p>
      <input type="text" v-model="searchQuery" placeholder="Search by dasher or quiz..." class="search-input" />
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="table-card">
      <div class="loading-state">
        <div class="spinner"></div>
        <h3>Loading Records...</h3>
      </div>
    </div>

    <!-- Records Table -->
    <div v-else class="table-card">
      <table class="styled-table">
        <thead>
          <tr>
            <th @click="sortBy('user_name')">
              Dasher Name
              <span v-if="sortKey === 'user_name'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
            </th>
            <th @click="sortBy('quiz_title')">
              Quiz Title
              <span v-if="sortKey === 'quiz_title'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
            </th>
            <th class="text-center" @click="sortBy('score')">
              Score
              <span v-if="sortKey === 'score'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
            </th>
            <th @click="sortBy('created_at')">
              Date Completed
              <span v-if="sortKey === 'created_at'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="paginatedRecords.length === 0">
            <td colspan="5" class="empty-state">No student records found.</td>
          </tr>
          <tr v-for="rec in paginatedRecords" :key="rec.id">
            <td class="user-name">{{ rec.user ? rec.user.first_name + ' ' + rec.user.last_name : 'ID: ' + rec.user_id }}
            </td>
            <td class="quiz-title">{{ rec.quiz ? rec.quiz.title : 'Deleted Quiz' }}</td>
            <td class="text-center">
              <span class="score-badge" :class="getScoreClass(rec)">
                {{ rec.score }} / 10
              </span>
            </td>
            <td class="date-col">{{ formatDate(rec.created_at) }}</td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="pagination" v-if="totalPages > 1">
        <button :disabled="currentPage === 1" @click="currentPage--">Prev</button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button :disabled="currentPage === totalPages" @click="currentPage++">Next</button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

const records = ref([])
const loading = ref(true)

// Pagination & Sorting
const currentPage = ref(1)
const perPage = 10
const sortKey = ref('id')
const sortOrder = ref('asc')

// Search
const searchQuery = ref('')

// Fetch records
const fetchRecords = async (reply = 0) => {
  try {
    const response = await axios.get('/api/admin/records')
    records.value = response.data.data || response.data

  } catch (error) {
    if (error.response?.status === 429 && retry < 3) {
      setTimeout(() => fetchQuizzes(retry + 1), 1000)
    } else {
      console.error("Failed to fetch quizzes", error)
    }
  } finally {
    loading.value = false
  }
}

// Format date
const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    day: '2-digit', month: 'short', year: 'numeric'
  }).replace(/ /g, '/')
}

// Score Badge Classes
const getScoreClass = (rec) => {
  if (!rec.total_questions) return 'neutral'
  const percent = (rec.score / rec.total_questions) * 100
  if (percent >= 80) return 'score-high'
  if (percent >= 50) return 'score-mid'
  return 'score-low'
}

// Computed: add virtual fields for sorting
const mappedRecords = computed(() =>
  records.value.map(r => ({
    ...r,
    user_name: r.user ? r.user.first_name + ' ' + r.user.last_name : '',
    quiz_title: r.quiz ? r.quiz.title : ''
  }))
)

// Filtered + sorted
const filteredRecords = computed(() => {
  let filtered = mappedRecords.value.filter(
    r =>
      r.user_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      r.quiz_title.toLowerCase().includes(searchQuery.value.toLowerCase())
  )

  filtered.sort((a, b) => {
    let valA = a[sortKey.value] || ''
    let valB = b[sortKey.value] || ''

    if (sortKey.value === 'created_at') {
      valA = new Date(valA)
      valB = new Date(valB)
    }
    if (sortKey.value === 'score') {
      valA = valA || 0
      valB = valB || 0
    }

    if (valA < valB) return sortOrder.value === 'asc' ? -1 : 1
    if (valA > valB) return sortOrder.value === 'asc' ? 1 : -1
    return 0
  })

  return filtered
})

// Pagination
const totalPages = computed(() => Math.ceil(filteredRecords.value.length / perPage))
const paginatedRecords = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return filteredRecords.value.slice(start, start + perPage)
})

// Keep page in bounds
watch([filteredRecords, currentPage], () => {
  if (currentPage.value > totalPages.value) currentPage.value = totalPages.value || 1
})

// Sorting
const sortBy = (key) => {
  if (sortKey.value === key) sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  else {
    sortKey.value = key
    sortOrder.value = 'asc'
  }
}

onMounted(fetchRecords)
</script>

<style scoped>
/* Section Header */
.admin-section {
  animation: fadeIn 0.4s ease-out;
  padding: 1rem;
}

.section-title {
  color: #1e1b4b;
  font-size: 1.4rem;
  font-weight: 700;
  margin-bottom: 4px;
}

.section-subtitle {
  color: #64748b;
  font-size: 0.9rem;
  margin-bottom: 1rem;
}

.search-input {
  padding: 6px 12px;
  border-radius: 8px;
  border: 1px solid #cbd5e1;
  font-size: 0.85rem;
  margin-left: auto;
}

/* Table */
.table-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
  overflow: hidden;
  border: 1px solid #f1f5f9;
  margin-top: 1rem;
  overflow-x: auto;
}

.styled-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.95rem;
}

.styled-table thead tr {
  background-color: #f8fafc;
  border-bottom: 2px solid #f1f5f9;
  text-align: left;
}

.styled-table th {
  padding: 1rem 1.5rem;
  color: #475569;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0.05em;
  cursor: pointer;
}

.styled-table td {
  padding: 1rem 1.5rem;
  color: #334155;
  border-bottom: 1px solid #f1f5f9;
}

.record-id {
  font-weight: 600;
  color: #8b5cf6;
}

.user-name {
  font-weight: 500;
}

.quiz-title {
  color: #4c1d95;
  font-weight: 500;
}

.text-center {
  text-align: center;
}

/* Score Badges */
.score-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 700;
}

.score-high {
  background: #dcfce7;
  color: #166534;
}

.score-mid {
  background: #fef9c3;
  color: #854d0e;
}

.score-low {
  background: #fee2e2;
  color: #991b1b;
}

.neutral {
  background: #f1f5f9;
  color: #475569;
}

/* Loading State */
.loading-state {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  min-height: 150px;
  gap: 12px;
  color: #4c1d95;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f5f3ff;
  border-top: 4px solid #8b5cf6;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

.empty-state {
  text-align: center;
  padding: 3rem;
  color: #94a3b8;
  font-style: italic;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 1rem;
  gap: 12px;
}

.pagination button {
  padding: 6px 12px;
  border-radius: 8px;
  border: 1px solid #cbd5e1;
  background: white;
  cursor: pointer;
}

.pagination button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>