<template>
  <div class="admin-quiz-add">
    <h2>Create Quiz</h2>
    <form @submit.prevent="submitQuiz">
      <input v-model="title" placeholder="Title" required />
      <textarea v-model="description" placeholder="Description"></textarea>
      <button type="submit">Create</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
const title = ref('')
const description = ref('')
const submitQuiz = async () => {
  await axios.post('/api/admin/quizzes/create', { title: title.value, description: description.value, questions: [] })
}
</script>

<style scoped>
.admin-quiz-add{padding:20px;}
</style>