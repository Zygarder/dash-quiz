<template>
    <section class="admin-section">
        <div class="header-row">
            <h3 class="section-title">Edit Quiz</h3>
            <button @click="$router.push('/admin/manage-quizzes')" class="cancel-btn">
                Cancel
            </button>
        </div>

        <div v-if="initialLoading" style="text-align: center; padding: 50px">
            <h3>Loading Quiz Data...</h3>
        </div>

        <div v-else class="quiz-form-card">
            <div class="form-group">
                <label>Quiz Name:</label>
                <input v-model="form.title" type="text" placeholder="e.g. Motherboard" required />
            </div>

            <div class="form-group">
                <label>Topic / Description:</label>
                <input v-model="form.description" type="text" placeholder="e.g. Parts of the Motherboard" required />
            </div>

            <hr class="form-divider" />

            <div class="questions-header">
                <h3>Questions</h3>

            </div>

            <div v-for="(q, index) in form.questions" :key="index" class="question-block">
                <div class="question-meta">
                    <span>Question #{{ index + 1 }}</span>
                    <button type="button" @click="removeQuestion(index)" class="delete-link">
                        Remove
                    </button>
                </div>

                <input v-model="q.text" type="text" placeholder="Enter your question here" class="question-input"
                    required />

                <div class="options-grid">
                    <div v-for="(opt, optIndex) in 4" :key="optIndex" class="option-item">
                        <input class="correct_answer" type="radio" :name="'correct_' + index" :value="optIndex"
                            v-model="q.correct_option" required />
                        <input v-model="q.options[optIndex]" type="text" :placeholder="'Option ' + (optIndex + 1)"
                            required />
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" @click="addQuestion" class="btn-outline">
                    + Add Question
                </button>
                <button @click="updateQuiz" class="btn-primary" :disabled="saving">
                    {{ saving ? "Saving Changes..." : "Save Changes" }}
                </button>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import axios from "axios";

const router = useRouter();
const route = useRoute();

const initialLoading = ref(true);
const saving = ref(false);

const form = ref({
    title: "",
    description: "",
    questions: [],
});

// Fetch data when component loads
onMounted(async () => {
    try {
        const response = await axios.get(
            `/api/admin/quizzes/${route.params.id}/edit`
        );
        const d = response.data;

        form.value.title = d.quiz.title;
        form.value.description = d.quiz.description;

        // Map the questions and options perfectly!
        form.value.questions = d.questions.map((q) => {
            // Find which option matches the correct answer text
            const correctIndex = q.options.findIndex(
                (opt) => opt.option_text === q.correct_answer_text
            );

            return {
                id: q.id,
                text: q.question_text,
                options: q.options.map((opt) => opt.option_text), // Extract just the text strings
                correct_option: correctIndex !== -1 ? correctIndex : 0, // Set the radio button!
            };
        });
    } catch (err) {
        console.error("Error fetching quiz data:", err);
        alert("Failed to load quiz data.");
    } finally {
        initialLoading.value = false;
    }
});

const addQuestion = () => {
    form.value.questions.push({
        text: "",
        options: ["", "", "", ""],
        correct_option: 0,
    });
};

const removeQuestion = (index) => {
    if (form.value.questions.length > 1) {
        form.value.questions.splice(index, 1);
    } else {
        alert("You must have at least one question.");
    }
};

const updateQuiz = async () => {
    saving.value = true;
    try {
        // Use PUT or PATCH for updates depending on your Laravel route setup
        await axios.put(`/api/admin/quizzes/${route.params.id}`, form.value);
        alert("Quiz updated successfully!");
        router.push("/admin/manage-quizzes");
    } catch (e) {
        console.error("Update Error:", e.response?.data);
    } finally {
        saving.value = false;
    }
};

const handleLogout = () => {
    // Implement your logout logic here
    console.log("Logout clicked");
};
</script>

<style scoped>
/* Same styles as your create form */
.quiz-form-card {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* ACTIONS */
.form-actions {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 10px;
}

/* MOBILE */
@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
}

.form-group {
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 5px;
}

.form-group input {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

.form-divider {
    margin: 25px 0;
    border: 0;
    border-top: 1px solid #eee;
}

.cancel-btn {
    padding: 8px 14px;
    font-size: 12 px;
    background: #f3f4f6;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}

.question-block {
    background: #fdfdfd;
    border: 1px solid #eee;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.question-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-weight: bold;
    color: #3f2ea3;
}

.question-input {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

.options-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.btn-primary {
    background: #6366f1;
    color: white;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    background: #4f46e5;
}

.btn-outline {
    border: 1px dashed #6366f1;
    background: transparent;
    padding: 10px 14px;
    border-radius: 10px;
    cursor: pointer;
}

.option-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.option-item input[type="text"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.delete-link {
    background: none;
    border: none;
    color: brown;
    cursor: pointer;
    font-size: 12px;
}

.save-btn {
    width: 100%;
    padding: 15px;
    font-size: 16px;
    background: #3f2ea3;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.save-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.add-btn {
    background: #333;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
}
</style>
