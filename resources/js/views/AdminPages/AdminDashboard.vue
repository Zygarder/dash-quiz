<template>
  <div class="dashboard-wrapper">
    
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
            <button @click="handleLogout">LOG OUT</button>
          </div>
        </div>
      </section>

    </div>

    <div v-else class="loading-state">
      <p>Loading dashboard data...</p>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const stats = ref(null)

// Fetch data from Laravel API
const fetchStats = async () => {
  try {
    const { data } = await axios.get('/api/admin/dashboard')
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
  return date.toLocaleString(); 
}

onMounted(fetchStats)
</script>

<style scoped>
.loading-state {
  padding: 40px;
  text-align: center;
  font-size: 1.2rem;
  color: #555;
}
</style>