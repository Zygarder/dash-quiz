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
            <li class="active"><router-link to="/admin/dashboard">Dashboard</router-link></li>
            <li><router-link to="/admin/quizzes/manage">Manage Quizzes</router-link></li>
            <li><router-link to="/admin/users">Users Table</router-link></li>
            <li><router-link to="/admin/records">Student Records</router-link></li>
          </ul>
        </nav>
      </aside>

      <main class="admin-main">
        
        <div v-if="stats">
          
          <section class="admin-stats">
            <div class="admin-card total-registered">Total Registered: {{ stats.total_users }}</div>
            <div class="admin-card total-quizzes">Total Quiz: {{ stats.total_quizzes }}</div>
            <div class="admin-card total-active">Active User/s: {{ stats.active_users }}</div>
          </section>

          <section class="admin-details">
            <div class="logs-table">
              <div class="logs-header logs-table-header">
                Recent Logs
              </div>

              <div 
                v-for="log in stats.logs" 
                :key="log.id" 
                class="logs-row logs-table-row"
              >
                <span>{{ log.description }} at {{ formatDate(log.created_at) }}</span>
              </div>
              
              <div v-if="!stats.logs || stats.logs.length === 0" class="logs-row logs-table-row">
                <span>No recent logs found.</span>
              </div>
            </div>
          </section>

        </div>

        <div v-else>
          <p>Loading dashboard data...</p>
        </div>

      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const stats = ref(null)
const router = useRouter()

// Fetch data from Laravel API
const fetchStats = async () => {
  try {
    const { data } = await axios.get('/api/admin/dashboard')
    // We assume your Laravel controller returns a JSON object with these keys:
    // total_users, total_quizzes, active_users, and an array of logs
    stats.value = data.data || data
  } catch (e) {
    console.error("Error fetching dashboard stats:", e)
  }
}

// Handle Logout via API
const handleLogout = async () => {
  try {
    await axios.post('/api/logout');
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('userRole');
    router.push('/');
  } catch (e) {
    console.error("Logout failed", e);
  }
}

// Helper function to format the date nicely
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleString(); // Adjusts to local time and formats nicely
}

onMounted(fetchStats)
</script>

<style scoped>
.dashboard {
    padding: 20px;
}
</style>