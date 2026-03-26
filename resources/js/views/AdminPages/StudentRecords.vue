<template>
  <div class="page-wrapper">
    <section class="admin-section">

      <!-- Header -->
      <div class="header-row">
        <div>
          <h3 class="section-title">Dasher Records</h3>
          <p class="section-subtitle">Viewing all completed quiz attempts</p>
        </div>

        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search..."
          class="search-input"
        />
      </div>

      <!-- Loading -->
      <div v-if="loading" class="table-card">
        <div class="loading-state">
          <div class="spinner"></div>
          <p>Loading records...</p>
        </div>
      </div>

      <!-- Table -->
      <div v-else class="table-card">

        <!-- Table Header -->
        <div class="table-header">
          <span @click="sortBy('user_name')">Dasher</span>
          <span @click="sortBy('quiz_title')">Quiz</span>
          <span class="text-center" @click="sortBy('score')">Score</span>
          <span @click="sortBy('created_at')">Date</span>
        </div>

        <!-- Rows -->
        <div v-if="paginatedRecords.length">
          <div
            v-for="rec in paginatedRecords"
            :key="rec.id"
            class="table-row"
          >
            <span class="user">
              {{ rec.user ? rec.user.first_name + ' ' + rec.user.last_name : 'User #' + rec.user_id }}
            </span>

            <span class="quiz">
              {{ rec.quiz ? rec.quiz.title : 'Deleted Quiz' }}
            </span>

            <span class="text-center">
              <span class="score-badge" :class="getScoreClass(rec)">
                {{ rec.score }} / {{ rec.total_questions || 10 }}
              </span>
            </span>

            <span class="date">
              {{ formatDate(rec.created_at) }}
            </span>
          </div>
        </div>

        <!-- Empty -->
        <div v-else class="empty-state">
          <p>No records found.</p>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="pagination">
          <button :disabled="currentPage === 1" @click="currentPage--">
            Prev
          </button>

          <span>Page {{ currentPage }} / {{ totalPages }}</span>

          <button :disabled="currentPage === totalPages" @click="currentPage++">
            Next
          </button>
        </div>

      </div>
    </section>
  </div>
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

/* WRAPPER */
.page-wrapper {
  padding: 1.75rem;
  background: #f8fafc;
  min-height: 100vh;
  display: flex;
  justify-content: center;
}

.admin-section {
  width: 100%;
  max-width: 1100px;
}

/* HEADER */
.header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
  gap: 1rem;
}

.section-title {
  font-size: 1.3rem;
  font-weight: 600;
  color: #1e1b4b;
}

.section-subtitle {
  font-size: 0.85rem;
  color: #64748b;
}

/* SEARCH */
.search-input {
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
  font-size: 0.85rem;
  min-width: 180px;
}

.search-input:focus {
  outline: none;
  border-color: #6366f1;
}

/* TABLE CARD */
.table-card {
  background: #fff;
  border-radius: 14px;
  border: 1px solid #e5e7eb;
  box-shadow: 0 6px 18px rgba(0,0,0,0.04);
  overflow: hidden;
}

/* HEADER */
.table-header {
  display: grid;
  grid-template-columns: 1.5fr 1.5fr 120px 160px;
  padding: 12px 16px;
  font-size: 0.75rem;
  font-weight: 600;
  color: #6b7280;
  background: #f9fafb;
}

.table-header span{
  text-align: center;
}

/* ROW */
.table-row {
  display: grid;
  grid-template-columns: 1.5fr 1.5fr 120px 160px;
  padding: 14px 16px;
  border-top: 1px solid #f1f5f9;
  align-items: center;
  font-size: 0.9rem;
  transition: background 0.2s;
}

.table-row:hover {
  background: #f9fafb;
}

/* TEXT */
.user {
  font-weight: 500;
  color: #111827;
}

.quiz {
  color: #4f46e5;
}

.date {
  color: #6b7280;
  font-size: 0.8rem;
}

.text-center {
  text-align: center;
}

/* SCORE BADGE */
.score-badge {
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 600;
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

/* EMPTY */
.empty-state {
  padding: 3rem;
  text-align: center;
  color: #9ca3af;
}

/* LOADING */
.loading-state {
  text-align: center;
  padding: 3rem;
}

.spinner {
  width: 35px;
  height: 35px;
  border: 3px solid #e0e7ff;
  border-top: 3px solid #6366f1;
  border-radius: 50%;
  margin: 0 auto 10px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* PAGINATION */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 12px;
  padding: 14px;
}

.pagination button {
  padding: 6px 12px;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
  background: white;
  cursor: pointer;
  font-size: 0.8rem;
}

.pagination button:hover {
  border-color: #6366f1;
  color: #6366f1;
}

.pagination button:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

</style>