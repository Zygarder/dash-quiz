<template>
  <section class="admin-section">

    <!-- Success Alert -->
    <transition name="slide-fade">
      <div v-if="successMessage" class="alert-success">
        <span class="alert-icon">✓</span>
        {{ successMessage }}
      </div>
    </transition>

    <!-- Header -->
    <div class="header-row">
      <div>
        <h3 class="section-title">Manage Quizzes</h3>
        <p class="section-subtitle">Create, edit, or remove academic challenges</p>
      </div>

      <button @click="goToAddQuiz" class="add-btn">
        <span class="plus-icon">+</span> New Quiz
      </button>
    </div>

    <!-- 🔄 Loading -->
    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <h3>Loading Quizzes...</h3>
    </div>

    <!-- Table -->
    <div v-else class="table-card">

      <!-- Table Header (Clickable Sort) -->
      <div class="table-header">
        <span class="col-id" @click="sortBy('id')">
          ID
          <span v-if="sortKey === 'id'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
        </span>

        <span class="col-title" @click="sortBy('title')">
          Title
          <span v-if="sortKey === 'title'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
        </span>

        <span class="col-desc">Description</span>
        <span class="col-actions">Actions</span>
      </div>

      <!-- Rows -->
      <div v-if="sortedQuizzes.length > 0">
        <div v-for="quiz in sortedQuizzes" :key="quiz.id" class="table-row">
          <span class="col-id quiz-id">#{{ quiz.id }}</span>
          <span class="col-title quiz-name">{{ quiz.title }}</span>
          <span class="col-desc desc-text">
            {{ quiz.description || 'No description provided.' }}
          </span>

          <div class="col-actions actions-group">
            <button @click="goToEditQuiz(quiz.id)" class="btn-icon edit">
              Edit
            </button>
            <button @click="deleteQuiz(quiz.id, quiz.title)" class="btn-icon delete">
              Delete
            </button>
          </div>
        </div>
      </div>

      <!-- Empty -->
      <div v-else class="empty-state">
        <div class="empty-icon">📝</div>
        <p>No quizzes found. Start by creating your first one!</p>
      </div>

    </div>
  </section>
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

// Fetch
const fetchQuizzes = async () => {
  try {
    const { data } = await axios.get('/api/admin/quizzes')
    quizzes.value = data.data || data
  } catch (e) {
    console.error("Failed to fetch quizzes", e)
  } finally {
    loading.value = false
  }
}

// Sorting logic
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

onMounted(() => {
  fetchQuizzes()

  // optional auto-refresh like users page
  setInterval(fetchQuizzes, 5000)
})
</script>

<style scoped>
/* 1. Header & Section */
.admin-section {
  animation: fadeIn 0.4s ease-out;
}

.header-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 2rem;
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
}

/* 2. Success Alert */
.alert-success {
  background: #f5f3ff;
  border-left: 4px solid #8b5cf6;
  color: #4c1d95;
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 500;
}

.alert-icon {
  font-weight: bold;
  font-size: 1.1rem;
}

/* 3. Add Button - Using Accent Purple */
.add-btn {
  background: #4c1d95;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 8px;
}

.add-btn:hover {
  background: #8b5cf6;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
}

/* 4. Table Card & Grid */
.table-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
  border: 1px solid #f1f5f9;
  overflow: hidden;
}

.table-header {
  display: grid;
  grid-template-columns: 80px 1.5fr 3fr 180px;
  padding: 1rem 1.5rem;
  background: #f8fafc;
  color: #64748b;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border-bottom: 1px solid #f1f5f9;
}

.table-row {
  display: grid;
  grid-template-columns: 80px 1.5fr 3fr 180px;
  padding: 1.2rem 1.5rem;
  align-items: center;
  border-bottom: 1px solid #f8fafc;
  transition: background 0.2s;
}

.table-row:last-child {
  border-bottom: none;
}

.table-row:hover {
  background: #fcfaff;
}

/* 5. Column Styles */
.quiz-id {
  color: #8b5cf6;
  font-weight: 700;
}

.quiz-name {
  color: #1e1b4b;
  font-weight: 600;
}

.desc-text {
  color: #64748b;
  font-size: 0.9rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  padding-right: 20px;
}

/* 6. Action Buttons */
.actions-group {
  display: flex;
  gap: 10px;
}

.btn-icon {
  padding: 6px 14px;
  border-radius: 6px;
  font-size: 0.8rem;
  font-weight: 600;
  cursor: pointer;
  border: 1px solid transparent;
  transition: 0.2s;
}

.btn-icon.edit {
  background: #f5f3ff;
  color: #4c1d95;
}

.btn-icon.edit:hover {
  background: #4c1d95;
  color: white;
}

.btn-icon.delete {
  background: #fff1f2;
  color: #e11d48;
}

.btn-icon.delete:hover {
  background: #e11d48;
  color: white;
}

/* 7. Animations & States */
.empty-state {
  padding: 4rem;
  text-align: center;
  color: #94a3b8;
}

.empty-icon {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(5px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(20px);
  opacity: 0;
}

/* Loading State */
.loading-state {
  text-align: center;
  padding: 4rem;
  color: #4c1d95;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f5f3ff;
  border-top: 4px solid #8b5cf6;
  border-radius: 50%;
  margin: 0 auto 1rem;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>