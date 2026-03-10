<template>
  <div class="records-page">

    <!-- Top Bar Component here -->
    <TopBar />

    <!-- Main Content -->
    <main class="main-content">

      <div class="records-container">

        <h3 class="records-title">Your Quiz History</h3>

        <!-- Search Filter -->
        <div class="filter-bar">
          <input type="text" v-model="searchQuery" placeholder="Search topic or score..." />
        </div>

        <!-- Table -->
        <div class="table-wrapper">
          <table class="records-table">

            <thead>
              <tr>
                <th>Date Taken</th>
                <th>Topics</th>
                <th>Scores</th>
              </tr>
            </thead>

            <tbody>

              <tr v-for="record in filteredRecords">

                <td>{{ record.created_at }}</td>
                <td>{{ record.quiz_title }}</td>
                <td>{{ record.score }} / 10</td>

              </tr>

              <tr v-if="filteredRecords.length === 0">
                <td colspan="3">No records found.</td>
              </tr>

            </tbody>

          </table>
        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue"
import axios from "axios"
import SideBarComp from "@/components/SideBar.vue"
import { useUser } from "@/composables/useUser"
import TopBar from "../../components/TopBar.vue"

const { user, fetchUser, avatar } = useUser()
const records = ref([])
const searchQuery = ref("")


/*
|--------------------------------------------------------------------------
| Fetch Records
|--------------------------------------------------------------------------
*/

const fetchRecords = async () => {
  try {
    const { data } = await axios.get("api/records")
    records.value = data.result
  } catch (err) {
    console.error(err)
  }
}

/*
|--------------------------------------------------------------------------
| Filter Records
|--------------------------------------------------------------------------
*/

const filteredRecords = computed(() => {
  if (!searchQuery.value) return records.value

  return records.value.filter(record =>
    record.quiz_title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    record.score.toString().includes(searchQuery.value)
  )
})

/*
|--------------------------------------------------------------------------
| Lifecycle
|--------------------------------------------------------------------------
*/

onMounted(async () => {
  await fetchUser()
  fetchRecords()
})
</script>
<style scoped>
/* === PAGE CONTAINER === */
.records-page {
  background: #f5f5f8;
  min-height: 100vh;
  padding-bottom: 2rem;
  font-family: "Inter", sans-serif;
}

/* === MAIN CONTENT === */
.main-content {
  max-width: 900px;
  margin: 2rem auto;
  padding: 0 1rem;
}

/* === RECORDS CONTAINER === */
.records-container {
  background: #ffffff;
  border-radius: 16px;
  padding: 2rem 1.8rem;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
  animation: fadeIn 0.3s ease;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* === TITLE === */
.records-title {
  font-size: 1.8rem;
  font-weight: 800;
  color: #4b32a8;
  text-align: center;
  margin-bottom: 1rem;
}

/* === SEARCH FILTER === */
.filter-bar {
  display: flex;
  justify-content: center;
  margin-bottom: 1rem;
}

.filter-bar input {
  width: 100%;
  max-width: 320px;
  padding: 10px 16px;
  border-radius: 12px;
  border: 1.5px solid #ccc;
  font-size: 0.95rem;
  outline: none;
  transition: border 0.25s ease, box-shadow 0.25s ease;
}

.filter-bar input:focus {
  border-color: #4b32a8;
  box-shadow: 0 0 12px rgba(75, 50, 168, 0.25);
}

/* === TABLE WRAPPER === */
.table-wrapper {
  overflow-x: auto;
  border-radius: 12px;
  border: 1px solid #e3e0ff;
}

/* === TABLE STYLES === */
.records-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  font-size: 0.95rem;
}

.records-table th,
.records-table td {
  padding: 12px 16px;
  text-align: left;
}

.records-table thead {
  background: #4b32a8;
  color: #fff;
  font-weight: 600;
  border-radius: 12px 12px 0 0;
}

.records-table tbody tr {
  background: #fff;
  transition: background 0.25s, transform 0.25s;
  cursor: pointer;
}

.records-table tbody tr:nth-child(even) {
  background: #f8f6ff;
}

.records-table tbody tr:hover {
  background: #e8e4ff;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(75, 50, 168, 0.1);
}

.records-table td {
  border-bottom: 1px solid #e3e0ff;
  vertical-align: middle;
}

/* === EMPTY STATE === */
.records-table td[colspan] {
  text-align: center;
  font-style: italic;
  color: #7d7d7d;
  padding: 1rem 0;
}

/* === RESPONSIVE === */
@media (max-width: 600px) {
  .records-container {
    padding: 1.5rem 1rem;
  }

  .records-title {
    font-size: 1.5rem;
  }

  .filter-bar input {
    max-width: 100%;
  }

  .records-table th,
  .records-table td {
    padding: 10px 12px;
  }
}

/* === ANIMATION === */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(6px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>