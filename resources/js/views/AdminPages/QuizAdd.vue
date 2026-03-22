<template>
    <section class="admin-section">
        <div class="header-row">
            <h3 class="section-title">Create New Quiz</h3>
            <button @click="$router.push('/admin/quizzes/manage')" class="logout-btn" style="background: #555">
                Cancel
            </button>
        </div>

        <div class="quiz-form-card">
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
                <h3 style="margin-bottom: 3%;">Questions</h3>

            </div>

            <div v-for="(q, index) in form.questions" :key="index" class="question-block">
                <div class="question-meta">
                    <span># {{ index + 1 }}</span>
                    <button type="button" @click="removeQuestion(index)" class="delete-link">
                        Remove
                    </button>
                </div>

                <input v-model="q.text" type="text" placeholder="Enter your question here" class="question-input" />

                <div class="options-grid">
                    <div v-for="(opt, optIndex) in ['A', 'B', 'C', 'D']" :key="optIndex" class="option-item">
                        <label for="opt">
                            <input type="radio" id="opt" class="correct_answer" :name="'correct_' + index"
                                :value="optIndex" v-model="q.correct_option" />
                        </label>

                        <input v-model="q.options[optIndex]" type="text" :placeholder="'Option ' + (opt)" />

                    </div>
                </div>

            </div><button type="button" @click="addQuestion" class="add-btn">
                + Add New Question
            </button>

            <div class="form-actions">
                <button @click="submitQuiz" class="add-btn save-btn" :disabled="loading">
                    {{ loading ? "Saving..." : "Save Quiz" }}
                </button>

            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();
const loading = ref(false);

// ADJUSTED: Keys now match your controller's $request->validate()
const form = ref({
    title: "",
    description: "",
    questions: Array.from({ length: 10 }, () => ({
        text: "", // Changed from question_text to match controller
        options: ["", "", "", ""],
        correct_option: 0, // Changed from correct_answer to match controller
    })),
});

const addQuestion = () => {
    form.value.questions.push({
        text: "",
        options: ["", "", "", ""],
        correct_option: 0,
    });
};

const removeQuestion = (index) => {
    // Keeping the 10-question minimum logic from your controller
    if (form.value.questions.length > 10) {
        form.value.questions.splice(index, 1);
    } else {
        alert("The backend requires at least 10 questions.");
    }
};

const submitQuiz = async () => {
    loading.value = true;
    try {
        // Note: Since your backend returns a redirect, Axios might
        // technically receive the HTML of the 'manage' page as a response.
        await axios.post("/api/admin/quizzes/create", form.value);

        alert("Quiz created successfully!");
        router.push("/admin/quizzes/manage");
    } catch (e) {
        console.error("Submission Error:", e.response?.data || console.error("Backend Crash Details:", exactError));
        alert(
            "Validation Error: Ensure all fields are filled and you have at least 10 questions."
        );

    } finally {
        loading.value = false;
    }
};
</script>
<style scoped>
.quiz-form-card {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

.option-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.option-item .correct_answer {
    accent-color: green;
    appearance: none;
    width: 15px;
    height: 15px;
    border: 2px solid maroon;
    border-radius: 50%;
    outline: none;
    background-color: white;
    cursor: pointer;
    display: inline-block;
    vertical-align: middle;
}

/* This makes the "click" visible */
.option-item .correct_answer:checked {
    background-color: lightgreen;
    border-color: green;
    /* Optional: creates a white inner dot */
}

/* This applies ONLY when it is NOT checked */
.option-item .correct_answer:not(:checked) {
    background-color: brown;
    /* light gray bg */
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

.mt-4 {
    margin-top: 1.5rem;
}
</style>
