<template>
  <div class="auth-wrapper">
    <header class="auth-header">
      <div class="header-inner">
        <div class="brand">
          <div class="brand-icon">D</div>
          <span class="brand-name">Dash<span>Quiz</span></span>
        </div>
        <div class="header-links">
          <span>Taft CSS Assessment Portal</span>
        </div>
      </div>
    </header>

    <main class="auth-container">
      <section class="hero-content">
        <div class="badge">V 1.0.1</div>
        <h1 class="main-heading">
          Precision in <br />
          <span class="text-gradient">Assessment.</span>
        </h1>
        <p class="sub-heading">
          Empowering TVL-CSS students at Taft National High School with real-world NCII diagnostic tools and performance
          analytics.
        </p>

        <div class="feature-list">
          <div class="feature-item">
            <div class="check">✓</div>
            <span>Real-time Performance Analytics</span>
          </div>
          <div class="feature-item">
            <div class="check">✓</div>
            <span>Secure COC Mock Assessments</span>
          </div>
        </div>
      </section>

      <section class="form-section">
        <form class="login-card" @submit.prevent="handleLogin">
          <div class="card-intro">
            <h2>Account Login</h2>
            <p>Enter your credentials to continue</p>
          </div>

          <div class="form-group">
            <label for="email">School Email</label>
            <div class="input-wrapper">
              <input id="email" type="email" v-model.trim="form.email" placeholder="name@test.com"
                autocomplete="username" :class="{ 'error-border': errors.email }" />
            </div>
            <span v-if="errors.email" class="error-text">{{ errors.email[0] }}</span>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
              <input id="password" type="password" v-model.trim="form.password" placeholder="••••••••"
                autocomplete="current-password" :class="{ 'error-border': errors.password }" />
            </div>
            <span v-if="errors.password" class="error-text">{{ errors.password[0] }}</span>
          </div>

          <div v-if="generalError" class="alert-box">
            {{ generalError }}
          </div>

          <button type="submit" class="btn-submit" :disabled="loading">
            <span v-if="!loading">Continue to Dashboard</span>
            <span v-else class="loader-dots">
              <span v-if="loading">
              <i class="fas fa-spinner fa-spin"></i></span>
              Logging...</span>
          </button>

          <div class="form-footer">
            <p>Need help? <router-link to="/forgot" class="v-link">Forgot Password</router-link></p>
            <div class="separator"><span>OR</span></div>
            <router-link to="/register" class="btn-secondary">New Student? Register</router-link>
          </div>
        </form>
      </section>
    </main>

    <footer class="auth-footer">
      <p>&copy; 2026 Dash Quiz. Authorized SNSU IT Capstone Project.</p>
    </footer>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const form = reactive({
  email: '',
  password: ''
})
const loading = ref(false);
const errors = ref({});
const generalError = ref('');

const router = useRouter();

const handleLogin = async () => {
  loading.value = true;
  errors.value = {};
  generalError.value = '';

  try {
    // Change this line
    const response = await axios.post('/api/login', form);
    const data = response.data; // This is your JSON body

    console.log(response.status); // Check the actual HTTP code

    // Update this condition
    if (response.status === 200 || response.status === 204) {
      localStorage.setItem('isLoggedIn', 'true');

      // Use data.role (from your JSON response)
      if (data.role) {
        localStorage.setItem('userRole', data.role);
      }

      if (data.role === 'admin') {
        router.push('/admin/dashboard');
      } else {
        router.push('/home');
      }
    }

  } catch (err) {
    // Reset local storage on failure
    localStorage.removeItem('isLoggedIn');

    if (err.response) {
      const { status, data } = err.response;
      console.log(status)
      if (status === 422 && data.errors) {
        // Validation errors
        errors.value = data.errors;
      } else if (status === 401 || status === 419) {
        // Wrong credentials or expired session
        generalError.value = data.message || 'Invalid credentials or session expired.';
        console.log(data.message)
      }
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Color Palette Variables */
:root {
  --v-deep: #2e1065;
  --v-main: #4c1d95;
  --v-accent: #8b5cf6;
  --v-bg: #f5f3ff;
  --text-h: #1e1b4b;
  --text-p: #475569;
}

.auth-wrapper {
  min-height: 100vh;
  background: radial-gradient(circle at top right, #f5f3ff 0%, #ffffff 100%);
  font-family: 'Inter', sans-serif;
  display: flex;
  flex-direction: column;
}

.auth-header {
  padding: 1.25rem 0;
  border-bottom: 1px solid rgba(139, 92, 246, 0.08);
  background-color: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(8px);
  position: sticky;
  top: 0;
  z-index: 10;
}

.header-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.brand {
  display: flex;
  align-items: center;
  gap: 12px;
}

.brand-icon {
  background: #4c1d95;
  color: white;
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  font-weight: 900;
  box-shadow: 0 4px 12px rgba(76, 29, 149, 0.2);
}

.brand-name {
  font-weight: 800;
  font-size: 1.3rem;
  color: #1e1b4b;
  letter-spacing: -0.5px;
}

.brand-name span {
  color: #8b5cf6;
}

.header-links {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: #94a3b8;
  font-weight: 800;
}

/* Layout */
.auth-container {
  flex: 1;
  display: grid;
  grid-template-columns: 1.1fr 0.9fr;
  max-width: 1200px;
  width: 100%;
  margin: 0 auto;
  align-items: center;
  padding: 4rem 2rem;
  gap: 4rem;
}

/* Hero Section */
.hero-content {
  animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.badge {
  display: inline-block;
  background: #ede9fe;
  color: #5b21b6;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 0.7rem;
  font-weight: 800;
  margin-bottom: 2rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.main-heading {
  font-size: clamp(3rem, 5vw, 4.5rem);
  font-weight: 900;
  line-height: 1;
  color: #1e1b4b;
  letter-spacing: -0.04em;
  margin-bottom: 2rem;
}

.text-gradient {
  background: linear-gradient(135deg, #4c1d95 0%, #8b5cf6 100%);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.sub-heading {
  font-size: 1.25rem;
  color: #475569;
  line-height: 1.6;
  margin-bottom: 3rem;
  max-width: 500px;
}

.feature-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 12px;
  font-weight: 600;
  color: #1e1b4b;
  font-size: 1rem;
}

.check {
  color: #8b5cf6;
  font-weight: 900;
  background: #f5f3ff;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
}

/* Login Card */
.login-card {
  background: #ffffff;
  padding: 3.5rem;
  border-radius: 32px;
  box-shadow: 0 25px 50px -12px rgba(76, 29, 149, 0.1);
  border: 1px solid #f1f5f9;
  animation: fadeIn 0.8s ease-out;
}

.card-intro {
  margin-bottom: 2.5rem;
}

.card-intro h2 {
  font-size: 1.85rem;
  font-weight: 800;
  color: #1e1b4b;
  letter-spacing: -0.02em;
}

.card-intro p {
  color: #64748b;
  margin-top: 6px;
  font-weight: 500;
}

.form-group {
  margin-bottom: 1.75rem;
}

.form-group label {
  display: block;
  font-size: 0.85rem;
  font-weight: 700;
  color: #475569;
  margin-bottom: 10px;
  margin-left: 4px;
}

input {
  width: 100%;
  padding: 1rem 1.25rem;
  border: 2px solid #f1f5f9;
  border-radius: 16px;
  font-size: 1rem;
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  background: #f8fafc;
  color: #1e1b4b;
}

input:focus {
  outline: none;
  border-color: #8b5cf6;
  background: #fff;
  box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.12);
}

.error-border {
  border-color: #ef4444 !important;
  background-color: #fef2f2;
}

.error-text {
  font-size: 0.75rem;
  color: #ef4444;
  font-weight: 600;
  margin-top: 6px;
  margin-left: 4px;
  display: block;
}

.btn-submit {
  width: 100%;
  background: #1e1b4b;
  color: white;
  padding: 1.1rem;
  border-radius: 16px;
  border: none;
  font-weight: 700;
  font-size: 1.05rem;
  cursor: pointer;
  margin-top: 1rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-submit:hover:not(:disabled) {
  background: #4c1d95;
  transform: translateY(-2px);
  box-shadow: 0 12px 20px -5px rgba(76, 29, 149, 0.25);
}

.btn-submit:active {
  transform: translateY(0);
}

.form-footer {
  margin-top: 2.5rem;
  text-align: center;
}

.v-link {
  color: #8b5cf6;
  text-decoration: none;
  font-weight: 700;
  transition: color 0.2s;
}

.v-link:hover {
  color: #4c1d95;
}

.separator {
  margin: 1.75rem 0;
  display: flex;
  align-items: center;
  color: #cbd5e1;
  font-size: 0.75rem;
  font-weight: 800;
}

.separator::before,
.separator::after {
  content: "";
  flex: 1;
  height: 1px;
  background: #f1f5f9;
}

.separator span {
  padding: 0 12px;
}

.btn-secondary {
  display: block;
  text-decoration: none;
  padding: 1rem;
  border-radius: 16px;
  border: 2px solid #f1f5f9;
  color: #475569;
  font-weight: 700;
  transition: all 0.2s;
}

.btn-secondary:hover {
  background: #fff;
  border-color: #ddd6fe;
  color: #5b21b6;
}

.auth-footer {
  padding: 3rem;
  text-align: center;
  color: #94a3b8;
  font-size: 0.8rem;
  font-weight: 600;
  margin-top: auto;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

@media (max-width: 1024px) {
  .auth-container {
    gap: 2rem;
  }

  .main-heading {
    font-size: 3.5rem;
  }
}

@media (max-width: 900px) {
  .auth-container {
    grid-template-columns: 1fr;
    padding-top: 2rem;
  }

  .hero-content {
    text-align: center;
  }

  .sub-heading {
    margin: 0 auto 3rem;
  }

  .feature-list {
    align-items: center;
    margin-bottom: 3rem;
  }

  .login-card {
    padding: 2.5rem;
  }
}
</style>