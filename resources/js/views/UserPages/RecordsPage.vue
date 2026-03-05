<template>
    <div class="records-page">

        <SideBarComp :show="showSidebar" @close="showSidebar = false" />

        <!-- Top Bar -->
        <header class="top-bar">
            <button class="menu-btn" @click="toggleSidebar">&#9776;</button>

            <router-link to="/profile">
                <img :src="profilePhoto" class="top-avatar" style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:1px solid #ccc;"/>
            </router-link>
        </header>

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

                            <tr v-for="record in filteredRecords" :key="record.quiz_id">

                                <td>{{ record.created_at }}</td>
                                <td>{{ record.topic }}</td>
                                <td>{{ record.score }}</td>

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

const records = ref([])
const searchQuery = ref("")
const showSidebar = ref(false)

const profilePhoto = ref("/images/profiles/person.jpg")

/*
|--------------------------------------------------------------------------
| Fetch Records
|--------------------------------------------------------------------------
*/

const fetchRecords = async () => {
    try {
        const { data } = await axios.get("/records")

        records.value = data.records.map(r => ({
            quiz_id: r.quiz_id,
            topic: r.quiz_title || "N/A",
            score: `${r.score}`,
            created_at: r.completed_at
        }))

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
        record.topic.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        record.score.includes(searchQuery.value)
    )
})

/*
|--------------------------------------------------------------------------
| Sidebar
|--------------------------------------------------------------------------
*/

const toggleSidebar = () => {
    showSidebar.value = !showSidebar.value
}

/*
|--------------------------------------------------------------------------
| Lifecycle
|--------------------------------------------------------------------------
*/

onMounted(() => {
    fetchRecords()
})
</script>

<style scoped>

</style>