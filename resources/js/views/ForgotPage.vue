<template>
  <div class="forgot-page">
    <header class="top-bar">
      <p>Reset Password</p>
    </header>

    <main class="center-container">
      <div class="reset-box">
        <h2>Forgot Password?</h2>
        <p>Please enter your email address and we'll send you a link to reset your password.</p>
        <form @submit.prevent="handleReset">
          <input type="email" v-model="email" placeholder="Email" required />
          <button :disabled="loading">{{ loading ? 'Sending...' : 'Send Reset Link' }}</button>
        </form>
        <p class="small-text">
          Remembered? <router-link to="/">Go back to login</router-link>
        </p>
      </div>
    </main>
  </div>


</template>

<script setup>
import { ref } from 'vue'

const email = ref('')
const loading = ref(false)
const handleReset = async () => {
  loading.value = true
  try {
    // placeholder - implement API call if available  
    await fetch('/api/password/forgot', { method: 'POST', body: JSON.stringify({ email: email.value }) })
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.center-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 80vh;
}

.top-bar {
  background: #4b3fc2;
  color: #fff;
  padding: 10px;
  text-align: center;
}

.reset-box {
  max-width: 400px;
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.reset-box input {
  width: 100%;
  padding: 8px;
  margin: 5px 0;
}

.reset-box button {
  padding: 8px 16px;
  margin-top: 10px;
}

.small-text {
  margin-top: 10px;
  font-size: 0.9em;
}
</style>