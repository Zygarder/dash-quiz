<template>
  <div>
    <header class="admin-header">
      <h2>Dash Quiz Admin Dashboard</h2>
      <a href="#" @click.prevent="handleLogout" class="logout-btn">Log Out</a>
    </header>

    <div class="admin-container">
      <aside class="admin-sidebar">
        <h3 class="sidebar-title">Admin Menu</h3>
        <nav>
          <ul>
            <li><router-link to="/admin/dashboard">Dashboard</router-link></li>
            <li class="active"><router-link to="/admin/quizzes">Manage Quizzes</router-link></li>
            <li><router-link to="/admin/users">Users Table</router-link></li>
            <li><router-link to="/admin/records">Student Records</router-link></li>
          </ul>
        </nav>
      </aside>

      <main class="admin-main">
        <section class="admin-section">

          <div v-if="successMessage" style="padding:10px; background:lightgreen; margin-bottom:10px; border:1px solid green;">
            {{ successMessage }}
          </div>

          <h3 class="section-title">Manage Quizzes</h3>

          <button @click="goToAddQuiz" class="add-btn">+ New Quiz</button>

          <div class="quiz-table quiz-admin-table">
            
            <div class="quiz-table quiz-table-header">
              <span>Quiz ID</span>
              <span>Title</span>
              <span>Description</span>
              <span>Actions</span>
            </div>

            <div v-for="quiz in quizzes" :key="quiz.id" class="quiz-table-row">
              <span>{{ quiz.id }}</span>
              <span>{{ quiz.title }}</span>
              <span>{{ quiz.description }}</span>
              
              <span>
                <button @click="goToEditQuiz(quiz.id)" class="action-btn edit">Edit</button>
                
                <button 
                  class="action-btn delete"
                  @click="deleteQuiz(quiz.id, quiz.title)">
                  Delete
                </button>
              </span>
            </div>

            <div v-if="quizzes.length === 0" class="quiz-table-row" style="text-align: center; padding: 20px;">
              No quizzes found.
            </div>

          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

// State variables
const quizzes = ref([])
const successMessage = ref('')

// 1. Fetch Quizzes from API
const fetchQuizzes = async () => {
  try {
    const { data } = await axios.get('/api/admin/quizzes')
    quizzes.value = data.data || data
  } catch (e) {
    console.error("Failed to fetch quizzes", e)
  }
}

// 2. Navigation Methods
const goToAddQuiz = () => {
  router.push('/admin/quizzes/create') // Update to match your actual route
}

const goToEditQuiz = (id) => {
  router.push(`/admin/quizzes/${id}/edit`) // Update to match your actual route
}

// 3. Delete Method (Replaces the Blade <form>)
const deleteQuiz = async (id, title) => {
  // Built-in JS confirm dialogue, just like your Blade file
  if (!confirm(`Are you sure you want to delete quiz ${title}?`)) {
    return
  }

  try {
    await axios.delete(`/api/admin/quizzes/${id}`)
    
    // Remove the deleted quiz from the screen instantly without reloading
    quizzes.value = quizzes.value.filter(quiz => quiz.id !== id)
    
    // Show success message and hide it after 3 seconds
    successMessage.value = "Quiz deleted successfully!"
    setTimeout(() => successMessage.value = '', 3000)
    
  } catch (e) {
    console.error("Failed to delete quiz", e)
    alert("There was an error deleting the quiz.")
  }
}

// 4. Logout Method
const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
    localStorage.removeItem('isLoggedIn')
    localStorage.removeItem('userRole')
    router.push('/login')
  } catch (e) {
    console.error("Logout failed", e)
  }
}

// Run this when the page loads
onMounted(fetchQuizzes)
</script>

<style scoped>
/* Added your span alignment from the original Blade head */
span {
  text-align: center;
}

.admin-manage-questions {
  padding: 20px;
}
</style>