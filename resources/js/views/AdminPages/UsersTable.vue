<template>
  <section class="admin-section">

    <!-- Success Message -->
    <div v-if="successMessage" class="alert-success">
      {{ successMessage }}
    </div>

    <!-- Header + Search -->
    <div class="header-row">
      <h3 class="section-title">Registered Dashers</h3>
      <input
        type="text"
        v-model="searchQuery"
        placeholder="Search by name or email..."
        class="search-input"
      />
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="table-card">
      <div class="loading-state">
        <div class="spinner"></div>
        <h3>Loading Dashers...</h3>
      </div>
    </div>

    <!-- Users Table -->
    <div v-else class="table-card">
      <table class="styled-table">
        <thead>
          <tr>
            <th @click="sortBy('id')">
              Dasher ID
              <span v-if="sortKey === 'id'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
            </th>
            <th @click="sortBy('first_name')">
              First Name
              <span v-if="sortKey === 'first_name'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
            </th>
            <th @click="sortBy('last_name')">
              Last Name
              <span v-if="sortKey === 'last_name'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
            </th>
            <th @click="sortBy('email')">
              Email
              <span v-if="sortKey === 'email'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
            </th>
            <th @click="sortBy('created_at')">
              Date Registered
              <span v-if="sortKey === 'created_at'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
            </th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="paginatedUsers.length === 0">
            <td colspan="6" class="empty-state">No users found.</td>
          </tr>
          <tr v-for="user in paginatedUsers" :key="user.id">
            <td><strong>#{{ user.id }}</strong></td>
            <td>{{ user.first_name }}</td>
            <td>{{ user.last_name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ formatDate(user.created_at) }}</td>
            <td class="actions-col">
              <button @click="editUser(user)" class="action-btn edit-btn">Edit</button>
              <button @click="confirmDelete(user.id)" class="action-btn delete-btn">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="pagination" v-if="totalPages > 1">
        <button :disabled="currentPage === 1" @click="currentPage--">Prev</button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button :disabled="currentPage === totalPages" @click="currentPage++">Next</button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import axios from 'axios'

const users = ref([])
const loading = ref(true)
const successMessage = ref('')

// Pagination & Sorting
const currentPage = ref(1)
const perPage = 10
const sortKey = ref('id')
const sortOrder = ref('asc')

// Search
const searchQuery = ref('')

// Fetch users
const fetchUsers = async () => {
  try {
    const response = await axios.get('/api/admin/users')
    users.value = response.data.data || response.data
  } catch (error) {
    console.error('Error fetching users:', error)
    //alert('Failed to load users.')<-for some reason sige ra mo tunga ya man tana ako nag open sa tab
  } finally {
    loading.value = false
  }
}

// Delete user
const confirmDelete = async (id) => {
  if (window.confirm(`Are you sure you want to remove Dasher ID: ${id}?`)) {
    try {
      await axios.delete(`/api/admin/users/${id}`)
      successMessage.value = `Dasher ID: ${id} was successfully removed.`
      await fetchUsers()
      setTimeout(() => (successMessage.value = ''), 3000)
    } catch (error) {
      console.error('Error deleting user:', error)
      alert('Failed to delete user.')
    }
  }
}

// Edit user
const editUser = (user) => {
  // You can open a modal or navigate to edit page
  alert(`Edit Dasher ID: ${user.id} - implement your modal or route here.`)
}

// Format date
const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  }).replace(/ /g, '/')
}

// Computed: filtered + sorted users
const filteredUsers = computed(() => {
  let filtered = users.value.filter(
    (u) =>
      u.first_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      u.last_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      u.email.toLowerCase().includes(searchQuery.value.toLowerCase())
  )

  filtered.sort((a, b) => {
    let valA = a[sortKey.value] || ''
    let valB = b[sortKey.value] || ''

    if (sortKey.value === 'created_at') {
      valA = new Date(valA)
      valB = new Date(valB)
    }

    if (valA < valB) return sortOrder.value === 'asc' ? -1 : 1
    if (valA > valB) return sortOrder.value === 'asc' ? 1 : -1
    return 0
  })

  return filtered
})

// Pagination
const totalPages = computed(() => Math.ceil(filteredUsers.value.length / perPage))
const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return filteredUsers.value.slice(start, start + perPage)
})

// Watch currentPage to stay in bounds
watch([filteredUsers, currentPage], () => {
  if (currentPage.value > totalPages.value) currentPage.value = totalPages.value || 1
})

// Sorting
const sortBy = (key) => {
  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortKey.value = key
    sortOrder.value = 'asc'
  }
}

onMounted(() => {
  fetchUsers()
  setInterval(fetchUsers, 30000) // auto refresh every 30 seconds
})
let pollingInterval;
onUnmounted(() => {
  if (pollingInterval) {
    clearInterval(pollingInterval)
  }
})
</script>

<style scoped>
/* Section wrapper */
.admin-section {
  padding: 1.2rem;
}

/* Header */
.header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.section-title {
  font-size: 1.4rem;
  font-weight: 600;
  color: #0f172a;
}

/* Search input */
.search-input {
  padding: 6px 12px;
  border-radius: 8px;
  border: 1px solid #cbd5e1;
  font-size: 0.85rem;
  width: 220px;
}

/* Success Alert */
.alert-success {
  background: #dcfce7;
  color: #166534;
  padding: 10px 14px;
  border-radius: 8px;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

/* Loading State */
.loading-state {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  min-height: 150px;
  gap: 12px;
  color: #4c1d95;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f5f3ff;
  border-top: 4px solid #8b5cf6;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

/* Card container */
.table-card {
  background: white;
  border-radius: 16px;
  padding: 1rem;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
  overflow-x: auto;
}

/* Table */
.styled-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.9rem;
}

.styled-table th {
  text-align: left;
  padding: 12px;
  font-weight: 600;
  color: #334155;
  border-bottom: 1px solid #e2e8f0;
  cursor: pointer;
}

.styled-table td {
  padding: 12px;
  border-bottom: 1px solid #f1f5f9;
}

.styled-table tbody tr:hover {
  background: #f9fafb;
}

.actions-col button {
  margin-right: 6px;
}

/* Empty state */
.empty-state {
  text-align: center;
  padding: 1.5rem;
  color: #94a3b8;
}

/* Action button */
.action-btn {
  padding: 6px 12px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  font-size: 0.8rem;
  transition: 0.2s;
}

.edit-btn {
  background: #dbeafe;
  color: #1e40af;
}

.edit-btn:hover {
  background: #3b82f6;
  color: white;
}

.delete-btn {
  background: #fee2e2;
  color: #b91c1c;
}

.delete-btn:hover {
  background: #ef4444;
  color: white;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 1rem;
  gap: 12px;
}

.pagination button {
  padding: 6px 12px;
  border-radius: 8px;
  border: 1px solid #cbd5e1;
  background: white;
  cursor: pointer;
}

.pagination button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>