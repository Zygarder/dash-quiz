<template>
  <div class="admin-users">
    <h2>Users Table</h2>
    <table>
      <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Actions</th></tr></thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.first_name }} {{ user.last_name }}</td>
          <td>{{ user.email }}</td>
          <td><button @click="deleteUser(user.id)">Delete</button></td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const users = ref([])

const fetchUsers = async () => {
  const { data } = await axios.get('/api/admin/users')
  users.value = data.data
}

const deleteUser = async (id) => {
  await axios.delete(`/api/admin/users/${id}`)
  fetchUsers()
}

onMounted(fetchUsers)
</script>

<style scoped>
.admin-users{padding:20px;}
table{width:100%;border-collapse:collapse;}
th,td{border:1px solid #ccc;padding:8px;}
</style>