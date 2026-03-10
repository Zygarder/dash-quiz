<template>
  <section class="admin-section">
      
      <div v-if="successMessage" class="alert-success">
          {{ successMessage }}
      </div>

      <div class="header-row">
          <h3 class="section-title">Registered Dashers</h3>
      </div>

      <div v-if="loading" class="loading-state">
          <h3>Loading Dashers...</h3>
      </div>

      <div v-else class="table-card">
          <table class="styled-table">
              <thead>
                  <tr>
                      <th>Dasher ID</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Email</th>
                      <th>Date Registered</th>
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-if="users.length === 0">
                      <td colspan="6" class="empty-state">No users registered yet.</td>
                  </tr>
                  <tr v-for="user in users" :key="user.id">
                      <td><strong>#{{ user.id }}</strong></td>
                      <td>{{ user.first_name }}</td>
                      <td>{{ user.last_name }}</td>
                      <td>{{ user.email }}</td>
                      <td>{{ formatDate(user.created_at) }}</td>
                      <td>
                          <button @click="confirmDelete(user.id)" class="action-btn delete-btn">
                              Delete
                          </button>
                      </td>
                  </tr>
              </tbody>
          </table>
      </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const users = ref([])
const loading = ref(true)
const successMessage = ref('')

// Fetch all users
const fetchUsers = async () => {
  try {
      const response = await axios.get('/api/admin/users')
      users.value = response.data.data || response.data
  } catch (error) {
      console.error("Error fetching users:", error)
      alert("Failed to load users.")
  } finally {
      loading.value = false
  }
}

// Confirm and Delete User
const confirmDelete = async (id) => {
  if (window.confirm(`Are you sure you want to remove Dasher ID: ${id}?`)) {
      try {
          await axios.delete(`/api/admin/users/${id}`)
          successMessage.value = `Dasher ID: ${id} was successfully removed.`
          
          await fetchUsers()

          setTimeout(() => {
              successMessage.value = ''
          }, 3000)

      } catch (error) {
          console.error("Error deleting user:", error)
          alert("Failed to delete user.")
      }
  }
}

// Date formatter
const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-GB', {
      day: '2-digit', month: 'short', year: 'numeric'
  }).replace(/ /g, '/')
}

onMounted(fetchUsers)
</script>
