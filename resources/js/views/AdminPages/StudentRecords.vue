<template>
  <div class="admin-records">
    <h2>Student Records</h2>
    <table>
      <thead><tr><th>Quiz</th><th>User</th><th>Score</th><th>Date</th></tr></thead>
      <tbody>
        <tr v-for="rec in records" :key="rec.id">
          <td>{{ rec.quiz.title }}</td>
          <td>{{ rec.user.first_name }} {{ rec.user.last_name }}</td>
          <td>{{ rec.score }}</td>
          <td>{{ rec.created_at }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const records = ref([])

const fetchRecords = async () => {
  const { data } = await axios.get('/api/admin/records')
  records.value = data.data
}

onMounted(fetchRecords)
</script>

<style scoped>
.admin-records{padding:20px;}
table{width:100%;border-collapse:collapse;}
th,td{border:1px solid #ccc;padding:8px;}
</style>