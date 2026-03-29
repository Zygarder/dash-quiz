<template>
  <div class="page-wrapper">
    <section class="admin-section">

      <!-- Success Alert -->
      <transition name="fade-slide">
        <div v-if="successMessage" class="alert-success">
          <i class="fas fa-check-circle"></i>
          {{ successMessage }}
        </div>
      </transition>

      <!-- Header -->
      <div class="header-row">
        <div>
          <h3 class="section-title">Manage Quizzes</h3>
          <p class="section-subtitle">
            Create, edit, or manage quizzes
          </p>
        </div>

        <button @click="goToAddQuiz" class="add-btn">
          + New Quiz
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="loading-state">
        <div class="spinner"></div>
        <p>Loading quizzes...</p>
      </div>

      <!-- Table -->
      <div v-else class="table-card">

        <!-- Header -->
        <div class="table-header">
          <span @click="sortBy('id')">ID</span>
          <span @click="sortBy('title')">Title</span>
          <span>Description</span>
          <span>Actions</span>
        </div>

        <!-- Rows -->
        <div v-if="sortedQuizzes.length">
          <div v-for="quiz in sortedQuizzes" :key="quiz.id" class="table-row">
            <span class="id">#{{ quiz.id }}</span>

            <span class="title">{{ quiz.title }}</span>

            <span class="desc">
              {{ quiz.description || 'No description' }}
            </span>

            <div class="actions">
              <button @click="goToEditQuiz(quiz.id)" class="btn edit">
                Edit
              </button>

              <button @click="deleteQuiz(quiz.id, quiz.title)" class="btn delete">
                Delete
              </button>
            </div>
          </div>
        </div>

        <!-- Empty -->
        <div v-else class="empty-state">
          <p>No quizzes yet.</p>
        </div>

      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const quizzes = ref([])
const loading = ref(true)
const successMessage = ref('')

// Sorting
const sortKey = ref('id')
const sortOrder = ref('asc')

const fetchQuizzes = async (retry = 0) => {
  try {
    const { data } = await axios.get('/api/admin/quizzes')
    quizzes.value = data.data || data

  } catch (e) {

    if (e.response?.status === 429 && retry < 3) {
      setTimeout(() => fetchQuizzes(retry + 1), 1000)
    } else {
      console.error("Failed to fetch quizzes", e)
    }

  } finally {
    loading.value = false
  }
}

/**
 *  Sorting logic
 */
const sortedQuizzes = computed(() => {
  return [...quizzes.value].sort((a, b) => {
    let valA = a[sortKey.value] || ''
    let valB = b[sortKey.value] || ''

    if (typeof valA === 'string') valA = valA.toLowerCase()
    if (typeof valB === 'string') valB = valB.toLowerCase()

    if (valA < valB) return sortOrder.value === 'asc' ? -1 : 1
    if (valA > valB) return sortOrder.value === 'asc' ? 1 : -1
    return 0
  })
})

// Sort handler
const sortBy = (key) => {
  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortKey.value = key
    sortOrder.value = 'asc'
  }
}

// Navigation
const goToAddQuiz = () => router.push('/admin/quizzes/create')
const goToEditQuiz = (id) => router.push(`/admin/quizzes/${id}/edit`)

// Delete
const deleteQuiz = async (id, title) => {
  if (!confirm(`Are you sure you want to delete "${title}"?`)) return

  try {
    await axios.delete(`/api/admin/quizzes/${id}`)
    successMessage.value = "Quiz deleted successfully!"

    await fetchQuizzes()

    setTimeout(() => successMessage.value = '', 3000)
  } catch (e) {
    alert("Error deleting quiz.")
  }
}

onMounted(fetchQuizzes)
</script>

<style scoped>
.admin-section {
  animation: fadeIn 0.3s ease;
}

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

/* BUTTON */
.add-btn {
  background: #4f46e5;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 8px;
  font-size: 0.85rem;
  cursor: pointer;
  transition: 0.2s;
}

.add-btn:hover {
  background: #6366f1;
}

/* ALERT */
.alert-success {
  background: #eef2ff;
  color: #3730a3;
  padding: 10px 14px;
  border-radius: 8px;
  margin-bottom: 1rem;
  font-size: 0.85rem;
}

/* TABLE */
.table-card {
  background: #fff;
  border-radius: 14px;
  border: 1px solid #e5e7eb;
  overflow: hidden;
}

/* HEADER */
.table-header {
  display: grid;
  text-align: center;
  grid-template-columns: 80px 1.5fr 2fr 150px;
  padding: 12px 16px;
  font-size: 0.75rem;
  font-weight: 600;
  color: #6b7280;
  background: #f9fafb;
}

/* ROW */
.table-row {
  display: grid;
  text-align: center;
  grid-template-columns: 80px 1.5fr 2fr 150px;
  padding: 14px 16px;
  border-top: 1px solid #f1f5f9;
  align-items: center;
  font-size: 12px;
  transition: background 0.2s;
}

.table-row:hover {
  background: #f9fafb;
}

/* TEXT */
.id {
  color: #6366f1;
  font-weight: 600;
}

.title {
  font-weight: 500;
  color: #111827;
}

.desc {
  color: #6b7280;
  font-size: 0.85rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* ACTIONS */
.actions {
  display: flex;
  gap: 6px;
  justify-content: flex-end;
}

.btn {
  font-size: 0.75rem;
  padding: 5px 10px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
}

/* EDIT */
.btn.edit {
  background: #eef2ff;
  color: #4f46e5;
}

.btn.edit:hover {
  background: #4f46e5;
  color: white;
}

/* DELETE */
.btn.delete {
  background: #fee2e2;
  color: #dc2626;
}

.btn.delete:hover {
  background: #dc2626;
  color: white;
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

/* ANIMATION */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(4px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-slide-enter-active {
  transition: all 0.25s ease;
}

.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(-5px);
}
</style>