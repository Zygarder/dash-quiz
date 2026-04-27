<template>
    <div class="auth-wrapper">

        <header class="auth-header">
            <div class="header-inner">
                <div class="brand">
                    <img src="/public/bolt.png" alt="Logo" width="32" height="32">
                    <span class="brand-name">Dash<span>Quiz</span></span>
                </div>
                <span class="portal">Assessment Portal</span>
            </div>
        </header>

        <main class="container">
            <form class="card" @submit.prevent="handleRegister">
                <h2>Create account</h2>
                <p class="subtitle">Join Dash Quiz — it's free</p>

                <div class="grid-2">
                    <div class="field">
                        <input v-model.trim="form.first_name" type="text" name="first_name" placeholder="First name"
                            :class="{ error: errors.first_name }" autocomplete="off"/>
                        <small v-if="errors.first_name">{{ errors.first_name[0] }}</small>
                    </div>
                    <div class="field">
                        <input v-model.trim="form.last_name" type="text" name="last_name" placeholder="Last name"
                            :class="{ error: errors.last_name }" autocomplete="off"/>
                        <small v-if="errors.last_name">{{ errors.last_name[0] }}</small>
                    </div>
                </div>

                <div class="field">
                    <input v-model.trim="form.email" type="email" name="email" placeholder="Email address"
                        :class="{ error: errors.email }" autocomplete="off" />
                    <small v-if="errors.email">{{ errors.email[0] }}</small>
                </div>

                <div class="field">
                    <input v-model.trim="form.password" type="password" name="password" placeholder="Password"
                        :class="{ error: errors.password }" autocomplete="off" />
                    <small v-if="errors.password">{{ errors.password[0] }}</small>
                </div>

                <div class="field">
                    <input v-model.trim="form.password_confirmation" type="password" name="confirm_password" placeholder="Confirm password"
                        autocomplete="off" />
                    <small v-if="errors.password_confirmation">{{ errors.password_confirmation[0] }}</small>
                </div>

                <div v-if="generalError" class="alert">
                    {{ generalError }}
                </div>

                <button class="btn" type="submit" :disabled="loading">
                    <span v-if="!loading">Create account</span>
                    <span v-else class="loader"></span>
                </button>

                <div class="btn-login">
                    Already have an account? &nbsp;
                    <router-link to="/" class="login-link">Login</router-link>
                </div>
            </form>
        </main>

        <footer class="auth-footer">
            © {{ new Date().getFullYear() }} Dash Quiz • SNSU Capstone Project
        </footer>

    </div>
</template>

<script setup>
import axios from 'axios'
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = reactive({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: ''
})

const errors = ref({})
const generalError = ref('')
const loading = ref(false)

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)
}

function validateForm() {
    errors.value = {}
    generalError.value = ''
    let valid = true

    if (!form.first_name) { errors.value.first_name = ['First name is required']; valid = false }
    if (!form.last_name) { errors.value.last_name = ['Last name is required']; valid = false }

    if (!form.email) {
        errors.value.email = ['Email is required']; valid = false
    } else if (!isValidEmail(form.email)) {
        errors.value.email = ['Invalid email format']; valid = false
    }

    if (!form.password) {
        errors.value.password = ['Password is required']; valid = false
    } else if (form.password.length < 6) {
        errors.value.password = ['Password must be at least 6 characters']; valid = false
    }

    if (form.password !== form.password_confirmation) {
        errors.value.password_confirmation = ['Passwords do not match']; valid = false
    }

    return valid
}

const handleRegister = async () => {
    if (loading.value) return
    if (!validateForm()) return

    loading.value = true

    try {
        await axios.get('/sanctum/csrf-cookie')
        await axios.post('/api/register', form)
        router.push('/')
    } catch {
        generalError.value = 'Registration failed. Please try again.'
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

/* ── Header (matches login exactly) ── */
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
    background: #f3f4f6;
    padding: 4px 10px;
    border-radius: 20px;
}

/* ── Main ── */
.container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
    width: 100%;
}

/* ── Card ── */
.card {
    width: 100%;
    max-width: 400px;
    padding: 32px;
    border-radius: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 20px 40px rgba(0, 0, 0, 0.06);
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.card h2 {
    margin: 0;
    color: #111827;
    font-size: 1.4rem;
}

.subtitle {
    color: #9ca3af;
    font-size: 13px;
    margin-top: -6px;
}

/* ── Grid — fix expanding inputs ── */
.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    min-width: 0;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}

.field input {
    width: 100%;
    min-width: 0;
    padding: 11px 14px;
    border-radius: 10px;
    border: 1.5px solid #e5e7eb;
    font-size: 14px;
    color: #111827;
    background: #fafafa;
    transition: all 0.15s;
}

.field input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    background: #fff;
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
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 13px;
    border-left: 3px solid #ef4444;
}

/* ── Buttons ── */
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
    font-size: 14px;
    transition: all 0.15s;
}

.btn:hover {
    background: #4338ca;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
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

/* ── Login link (kept your text, polished wrapper) ── */
.btn-login {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 13px;
    color: #6b7280;
}

.login-link {
    text-decoration: none;
    color: #4f46e5;
    font-weight: 600;
}

.login-link:hover {
    text-decoration: underline;
    color: #4338ca;
}

/* ── Footer (matches login exactly) ── */
.auth-footer {
    text-align: center;
    padding: 12px;
    background-color: #6366f1;
    font-size: 12px;
    color: rgba(255, 255, 255, 0.8);
}

@media (max-width: 500px) {
    .grid-2 {
        grid-template-columns: 1fr;
    }
}
</style>