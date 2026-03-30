<template>
  <div class="auth-wrapper">

    <!-- HEADER -->
    <header class="auth-header">
      <div class="header-inner">
        <div class="brand">
          <div class="logo">DQ</div>
          <span class="brand-name">Dash<span>Quiz</span></span>
        </div>
        <span class="portal">Assessment Portal</span>
      </div>
    </header>

    <!-- MAIN -->
    <main class="container">

      <!-- HERO -->
      <section class="hero">
        <h1>
          Learning is <span>better</span><br />
          when we do it <span>together</span>
        </h1>
        <p>Practice, learn, and improve your skills with Dash Quiz.</p>
      </section>

      <!-- LOGIN CARD -->
      <section class="form-section">
        <form class="card" @submit.prevent="handleLogin">
          <h2>Welcome back</h2>

          <div class="field">
            <input type="email" v-model.trim="form.email" placeholder="Email address"
              :class="{ error: errors.email }" />
            <small v-if="errors.email">{{ errors.email[0] }}</small>
          </div>

          <div class="field">
            <input type="password" v-model.trim="form.password" placeholder="Password"
              :class="{ error: errors.password }" autocomplete="false"/>
            <small v-if="errors.password">{{ errors.password[0] }}</small>
          </div>

          <div v-if="generalError" class="alert">
            {{ generalError }}
          </div>

          <button class="btn" type="submit" :disabled="loading">
            <span v-if="!loading">Login</span>
            <span v-else class="loader"></span>
          </button>

          <div class="footer-links">
            <router-link to="/forgot" class="forgot-link">Forgot password?</router-link>

            <div class="divider">
              <div class="line"></div>or<div class="line"></div>
            </div>

            <router-link to="/register" class="btn-register">Create account</router-link>
          </div>
        </form>
      </section>

    </main>

    <!-- FOOTER -->
    <footer class="auth-footer">
      © {{new Date().getFullYear()}} Dash Quiz • SNSU Capstone Project
    </footer>

  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const form = reactive({
  email: '',
  password: ''
})

const loading = ref(false)
const errors = ref({})
const generalError = ref('')

const handleLogin = async () => {
  if (loading.value) return

  loading.value = true
  errors.value = {}
  generalError.value = ''

  try {
    const { data } = await axios.post('/api/login', form)

    localStorage.setItem('isLoggedIn', 'true')
    if (data.role) localStorage.setItem('userRole', data.role)

    router.push(data.role === 'admin' ? '/admin' : '/user')
  } catch (err) {
    localStorage.removeItem('isLoggedIn')

    if (err.response?.data?.errors) {
      errors.value = err.response.data.errors
    } else {
      generalError.value = err.response?.data?.message || 'Login failed.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
* {
  box-sizing: border-box;
}

.auth-wrapper {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: #ffffff;
}

/* HEADER */
.auth-header {
  border-bottom: 1px solid #e5e7eb;
  background: #ffffff;
}

.header-inner {
  max-width: 1100px;
  margin: 0 auto;
  padding: 16px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.brand {
  display: flex;
  align-items: center;
  gap: 10px;
}

.logo {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  background: #6366f1;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
}

.brand-name {
  font-weight: 800;
  color: #111827;
}

.brand-name span {
  color: #6366f1;
}

.portal {
  font-size: 12px;
  color: #6b7280;
}

/* MAIN */
.container {
  flex: 1;
  max-width: 1100px;
  margin: auto;
  padding: 40px 20px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
}

.hero {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.hero h1 {
  font-size: 2.6rem;
  font-weight: 800;
  color: #111827;
}

.btn-register {
  padding: 12px;
  display: flex;
  justify-content: center;
  align-items: center;
  text-decoration: none;
  color: #fff;
  border-radius: 10px;
  background: #1b8023;
  cursor: pointer;
}

.btn-register:hover {
  background-color: #1e9328;
}

.hero span {
  color: #6366f1;
}

.hero p {
  margin-top: 12px;
  color: #6b7280;
}

/* CARD */
.form-section {
  display: flex;
  align-items: center;
  justify-content: center;
}

.form-footer {
  width: 100%;
  background-color: orange;
}

.card {
  width: 100%;
  max-width: 380px;
  padding: 28px;
  border-radius: 16px;
  border: 1px solid #e5e7eb;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.card h2 {
  margin: 0;
  color: #111827;
}

.field input {
  width: 100%;
  padding: 12px;
  border-radius: 10px;
  border: 1px solid #d1d5db;
}

.field input:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
}

.field input.error {
  border-color: #ef4444;
}

.field small {
  font-size: 11px;
  color: #ef4444;
}

.alert {
  background: #fef2f2;
  color: #b91c1c;
  padding: 10px;
  border-radius: 8px;
}

.btn {
  padding: 12px;
  border-radius: 10px;
  border: none;
  background: #4f46e5;
  color: white;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn:hover {
  background: #6366f1;
}

.loader {
  width: 18px;
  height: 18px;
  border: 3px solid rgba(255, 255, 255, 0.4);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.footer-links {
  text-align: center;
  font-size: 13px;
}

.forgot-link {
  margin-top: 10px;
  text-decoration: none;
  color: #6366f1;
}

.forgot-link:hover {
  text-decoration: underline;
}

.divider {
  margin: 15px 0;
  color: #9ca3af;
  font-size: 12px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
}

.line {
  width: 100%;
  background-color: grey;
  height: 1px;
}

/* FOOTER */
.auth-footer {
  text-align: center;
  padding: 10px;
  background-color: #6366f1;
  font-size: 12px;
  color: #e5e7eb;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .container {
    grid-template-columns: 1fr;
  }

  .hero {
    text-align: center;
  }

  .hero h1 {
    font-size: 2rem;
  }
}
</style>