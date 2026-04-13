<template>
  <main class="wrapper">
    <div class="card">
      <div class="header">
        <div class="logo">DQ</div>
        <h2>Reset Password</h2>
        <p>Enter your new password below.</p>
      </div>

      <form @submit.prevent="handleReset" class="form">
        <div class="field">
          <input
            type="email"
            v-model="email"
            placeholder="Email address"
            required
            readonly
          />
        </div>

        <div class="field">
          <input
            type="password"
            v-model="password"
            placeholder="New password"
            required
          />
        </div>

        <div class="field">
          <input
            type="password"
            v-model="password_confirmation"
            placeholder="Confirm password"
            required
          />
        </div>

        <button class="btn" :disabled="loading">
          {{ loading ? 'Resetting...' : 'Reset Password' }}
        </button>
      </form>

      <div class="footer">
        <router-link to="/">Back to login</router-link>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()

const email = ref('')
const token = ref('')
const password = ref('')
const password_confirmation = ref('')
const loading = ref(false)

onMounted(() => {
  email.value = route.query.email || ''
  token.value = route.query.token || ''
})

const handleReset = async () => {
  if (password.value !== password_confirmation.value) {
    alert('Passwords do not match')
    return
  }

  loading.value = true
  try {
    await axios.post('/api/reset-password', {
      email: email.value,
      token: token.value,
      password: password.value,
      password_confirmation: password_confirmation.value
    })

    alert('Password reset successful!')
  } catch (e) {
    console.log(e)
    alert('Reset failed or token expired.')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
* { box-sizing: border-box; }

.wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #ffffff;
  padding: 20px;
}

.card {
  width: 100%;
  max-width: 400px;
  padding: 32px;
  border-radius: 16px;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  box-shadow: 0 10px 30px rgba(0,0,0,0.05);
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.header {
  text-align: center;
}

.logo {
  width: 40px;
  height: 40px;
  margin: 0 auto 10px;
  border-radius: 10px;
  background: #6366f1;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
}

.header h2 {
  margin: 0;
  font-size: 1.5rem;
  color: #111827;
}

.header p {
  font-size: 0.9rem;
  color: #6b7280;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.field input {
  width: 100%;
  padding: 14px;
  border-radius: 10px;
  border: 1px solid #d1d5db;
  font-size: 14px;
  transition: 0.2s;
}

.field input:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
}

.btn {
  padding: 14px;
  border-radius: 10px;
  border: none;
  background: #6366f1;
  color: white;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s;
}

.btn:hover {
  background: #4f46e5;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.footer {
  text-align: center;
  font-size: 13px;
  color: #6b7280;
}

.footer a {
  margin-left: 6px;
  color: #6366f1;
  text-decoration: none;
  font-weight: 500;
}

.footer a:hover {
  text-decoration: underline;
}

@media (max-width: 480px) {
  .card {
    padding: 24px;
  }
}
</style>