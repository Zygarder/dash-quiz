<template>
  <div class="admin-dashboard">
    <h1>Admin Dashboard</h1>
    <!-- you can fetch stats from /api/admin/dashboard -->
    <div v-if="stats">
      <p>Total users: {{ stats.total_users }}</p>
      <p>Total quizzes: {{ stats.total_quizzes }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const stats = ref(null)

const fetchStats = async () => {
  try {
    const { data } = await axios.get('/api/admin/dashboard')
    stats.value = data.data
  } catch (e) {
    console.error(e)
  }
}

onMounted(fetchStats)
</script>

<style scoped>
.admin-dashboard {
  padding: 20px;
}
</style>