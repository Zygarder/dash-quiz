<template>
    <section class="admin-section">
        <div class="header-row">
            <h3 class="section-title">Dasher Records</h3>
        </div>

        <div v-if="loading" class="loading-state">
            <h3>Loading Records...</h3>
        </div>

        <div v-else class="table-card">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Record ID</th>
                        <th>Dasher Name</th>
                        <th>Quiz Title</th>
                        <th>Score</th>
                        <th>Date Completed</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="records.length === 0">
                        <td colspan="5" class="empty-state">
                            No student records found.
                        </td>
                    </tr>
                    <tr v-for="rec in records" :key="rec.id">
                        <td>#{{ rec.id }}</td>
                        <td>
                            <span v-if="rec.user"
                                >{{ rec.user.first_name }}
                                {{ rec.user.last_name }}</span
                            >
                            <span v-else>Dasher ID: {{ rec.user_id }}</span>
                        </td>
                        <td>
                            {{ rec.quiz ? rec.quiz.title : "Deleted Quiz" }}
                        </td>
                        <td>
                            <span class="score-badge">
                                {{ rec.score }} /
                                {{ rec.total_questions || "?" }}
                            </span>
                        </td>
                        <td>{{ formatDate(rec.created_at) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const records = ref([]);
const loading = ref(true);

const fetchRecords = async () => {
    try {
        const response = await axios.get("/api/admin/records");
        // Depending on how your API is structured, Laravel pagination usually puts data in .data
        records.value = response.data.data || response.data;
    } catch (error) {
        console.error("Error fetching student records:", error);
        alert("Failed to load records. Please try again.");
    } finally {
        loading.value = false;
    }
};

// Helper to format the date like your Blade template ('d/M/Y')
const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return date
        .toLocaleDateString("en-GB", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        })
        .replace(/ /g, "/"); // Turns "12 Oct 2023" into "12/Oct/2023"
};

const handleLogout = () => {
    // Implement your logout logic here
    console.log("Logout clicked");
};

onMounted(fetchRecords);
</script>
