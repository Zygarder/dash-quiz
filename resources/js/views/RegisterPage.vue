<template>
    <main class="auth-wrapper">
        <nav>
            Register New Account
        </nav>
        <section class="auth-card">
            <header class="auth-header">
                <h1>Create account</h1>
                <p>Join Dash Quiz</p>
            </header>

            <form @submit.prevent="handleRegister" class="form">
                <!-- Name row -->
                <div class="grid-2">
                    <div class="field">
                        <input v-model.trim="form.first_name" type="text" placeholder="First name" required
                            :class="{ error: errors.first_name }" />
                        <small v-if="errors.first_name">{{ errors.first_name[0] }}</small>
                    </div>

                    <div class="field">
                        <input v-model.trim="form.last_name" type="text" placeholder="Last name"
                            :class="{ error: errors.last_name }" />
                        <small v-if="errors.last_name">{{ errors.last_name[0] }}</small>
                    </div>
                </div>

                <!-- Email -->
                <div class="field">
                    <input v-model.trim="form.email" type="email" placeholder="Email address" required
                        :class="{ error: errors.email }" />
                    <small v-if="errors.email">{{ errors.email[0] }}</small>
                </div>

                <!-- Password -->
                <div class="field">
                    <input v-model.trim="form.password" type="password" placeholder="Password" required
                        :class="{ error: errors.password }" />
                    <small v-if="errors.password">{{ errors.password[0] }}</small>
                </div>

                <!-- Confirm -->
                <div class="field">
                    <input v-model.trim="form.password_confirmation" type="password" placeholder="Confirm password"
                        required />
                </div>

                <div v-if="generalError" class="alert">
                    {{ generalError }}
                </div>

                <button class="btn" :disabled="loading">
                    <span v-if="!loading">Create account</span>
                    <span v-else>Creating...</span>
                </button>

                <p class="meta">
                    Already have an account?
                    <router-link to="/">Login</router-link>
                </p>
            </form>
        </section>

        <!-- FOOTER -->
        <footer class="auth-footer">
            © 2026 Dash Quiz • SNSU Capstone Project
        </footer>
    </main>
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

const handleRegister = async () => {
    loading.value = true
    errors.value = {}
    generalError.value = ''

    try {
        await axios.get('/sanctum/csrf-cookie')
        await axios.post('/api/register', form)
        router.push('/')
    } catch (err) {
        if (err.response?.data?.errors) {
            errors.value = err.response.data.errors
        } else {
            generalError.value = err.response?.data?.message || 'Something went wrong.'
        }
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
/* Reset fix for overflow issues */
* {
    box-sizing: border-box;
}

.auth-wrapper {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    background: #fff;
}

nav {
    height: auto;
    padding: 10px;
    position: relative;
    left: 0;
    top: 0;
    color: white;
    background-color: #6366f1;
    width: 100%;
    display: flex;
}

.auth-card {
    width: 100%;
    max-width: 420px;
    padding: 28px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    background: #fff;
    border: 1px solid #e5e7eb;
}

.auth-header {
    text-align: center;
    margin-bottom: 20px;
}

.auth-header h1 {
    font-size: 1.6rem;
    margin-bottom: 4px;
}

.auth-header p {
    font-size: 0.85rem;
    color: #646973;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.field input {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    background: #fff;
    font-size: 14px;
    transition: 0.2s;
}

.field input:focus {
    outline: none;
    border-color: #6366f1;
}

.field input.error {
    border-color: #ef4444;
}

.field small {
    color: #ef4444;
    font-size: 11px;
}

.alert {
    background: rgba(239, 68, 68, 0.1);
    color: #f87171;
    padding: 10px;
    border-radius: 8px;
    font-size: 13px;
    text-align: center;
}

.btn {
    margin-top: 6px;
    padding: 12px;
    border: none;
    border-radius: 10px;
    background: #4f46e5;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.btn:hover {
    background: #6366f1;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.meta {
    text-align: center;
    font-size: 13px;
    color: #9ca3af;
}

.meta a {
    color: #818cf8;
    text-decoration: none;
}

.meta a:hover {
    text-decoration: underline;
}

/* FOOTER */
.auth-footer {
    text-align: center;
    padding: 10px;
    width: 100%;
    background-color: #6366f1;
    font-size: 12px;
    color: #e5e7eb;
}

@media (max-width: 500px) {
    .grid-2 {
        grid-template-columns: 1fr;
    }
}
</style>