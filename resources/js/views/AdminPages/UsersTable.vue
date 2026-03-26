<template>
  <div class="dashboard-wrapper">
    <section class="admin-section">

      <!-- Success Message -->
      <transition name="fade">
        <div v-if="successMessage" class="alert-success">
          {{ successMessage }}
        </div>
      </transition>

      <!-- Header -->
      <div class="header-row">
        <div>
          <h3 class="section-title">Registered Dashers</h3>
          <p class="section-subtitle">Manage all user accounts</p>
        </div>

        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search by name or email..."
          class="search-input"
        />
      </div>

      <!-- Loading -->
      <div v-if="loading" class="table-card">
        <div class="loading-state">
          <div class="spinner"></div>
          <p>Loading records...</p>
        </div>
      </div>

      <!-- Table -->
      <div v-else class="table-card">
        <table class="styled-table">
          <thead>
            <tr>
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

              <th class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-if="paginatedUsers.length === 0">
              <td colspan="5" class="empty-state">No users found.</td>
            </tr>

            <tr v-for="user in paginatedUsers" :key="user.id">
              <td class="name">{{ user.first_name }}</td>
              <td class="name">{{ user.last_name }}</td>
              <td class="email">{{ user.email }}</td>
              <td>{{ formatDate(user.created_at) }}</td>

              <td class="actions-col">
                <button @click="editUser(user)" class="btn edit">
                  Edit
                </button>
                <button @click="confirmDelete(user.id)" class="btn delete">
                  Delete
                </button>
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
  </div>
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
  } finally {
    loading.value = false
  }
}

// Delete
const confirmDelete = async (id) => {
  if (!confirm(`Delete user ID: ${id}?`)) return

  try {
    await axios.delete(`/api/admin/users/${id}`)
    successMessage.value = `User ${id} removed.`

    await fetchUsers()

    setTimeout(() => successMessage.value = '', 2500)
  } catch (error) {
    alert('Delete failed.')
  }
}

// Edit
const editUser = (user) => {
  alert(`Edit user ${user.id}`)
}

// Format date
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  }).replace(/ /g, '/')
}

// Filter + Sort
const filteredUsers = computed(() => {
  let filtered = users.value.filter(u =>
    u.first_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    u.last_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    u.email.toLowerCase().includes(searchQuery.value.toLowerCase())
  )

  filtered.sort((a, b) => {
    let A = a[sortKey.value] || ''
    let B = b[sortKey.value] || ''

    if (sortKey.value === 'created_at') {
      A = new Date(A)
      B = new Date(B)
    }

    if (A < B) return sortOrder.value === 'asc' ? -1 : 1
    if (A > B) return sortOrder.value === 'asc' ? 1 : -1
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

watch([filteredUsers, currentPage], () => {
  if (currentPage.value > totalPages.value) {
    currentPage.value = totalPages.value || 1
  }
})

// Sort
const sortBy = (key) => {
  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortKey.value = key
    sortOrder.value = 'asc'
  }
}

// Lifecycle
let interval

onMounted(() => {
  fetchUsers()
  interval = setInterval(fetchUsers, 30000)
})

onUnmounted(() => {
  clearInterval(interval)
})
</script>

<style scoped>
/* ===== Wrapper ===== */
.dashboard-wrapper {
  background: #f8fafc;
  min-height: 100vh;
  padding: 2rem;
}

/* ===== Section ===== */
.admin-section {
  max-width: 1200px;
  margin: auto;
}

/* ===== Header ===== */
.header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.section-title {
  font-size: 1.4rem;
  font-weight: 700;
  color: #1e293b;
}

.section-subtitle {
  font-size: 0.85rem;
  color: #64748b;
}

/* ===== Search ===== */
.search-input {
  padding: 8px 12px;
  border-radius: 10px;
  border: 1px solid #e2e8f0;
  font-size: 0.85rem;
  width: 230px;
}

/* ===== Card ===== */
.table-card {
  background: white;
  border-radius: 16px;
  border: 1px solid #f1f5f9;
  box-shadow: 0 6px 20px rgba(0,0,0,0.04);
  overflow: hidden;
}

/* ===== Table ===== */
.styled-table {
  width: 100%;
  border-collapse: collapse;
}

.styled-table th {
  padding: 1rem;
  font-size: 0.75rem;
  text-transform: uppercase;
  color: #64748b;
  border-bottom: 1px solid #f1f5f9;
  cursor: pointer;
}

.styled-table td {
  padding: 1rem;
  border-bottom: 1px solid #f8fafc;
  font-size: 0.9rem;
}

.styled-table tr:hover {
  background: #f9fafb;
}

/* ===== Cells ===== */
.name {
  font-weight: 500;
  color: #1e293b;
}

.email {
  color: #4c1d95;
}

/* ===== Buttons ===== */
.actions-col {
  display: flex;
  gap: 8px;
  justify-content: center;
}

.btn {
  padding: 6px 12px;
  border-radius: 8px;
  border: none;
  font-size: 0.75rem;
  cursor: pointer;
  transition: 0.2s;
}

.btn.edit {
  background: #f1f5f9;
  color: #1e293b;
}

.btn.edit:hover {
  background: #4c1d95;
  color: white;
}

.btn.delete {
  background: #fee2e2;
  color: #991b1b;
}

.btn.delete:hover {
  background: #ef4444;
  color: white;
}

/* ===== States ===== */
.empty-state {
  text-align: center;
  padding: 2rem;
  color: #94a3b8;
}

/* ===== Loading ===== */
.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
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

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* ===== Pagination ===== */
.pagination {
  display: flex;
  justify-content: center;
  gap: 10px;
  padding: 1rem;
}

.pagination button {
  padding: 6px 12px;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  background: white;
  cursor: pointer;
}

.pagination button:disabled {
  opacity: 0.5;
}

/* ===== Animation ===== */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>