<template>
    <!-- Top Header -->
    <header class="top-bar">
        <p>Dash Quiz - Learn and test yourself!</p>
    </header>
    <main class="container">
        <div class="left-section">
            <h1>
                Learning is<br />
                better when we<br />
                do it together.
            </h1>
        </div>

        <div class="right-section">
            <div v-if="successMessage" class="success-register">
                {{ successMessage }}
            </div>

            <form class="login-box" method="POST" @submit.prevent="handleLogin">
                <input type="email" v-model="email" placeholder="Email" :class="{ 'invalid-input': errors.email }" />
                <p v-if="errors.email" class="error">
                    {{ errors.email[0] }}
                </p>

                <input type="password" v-model="password" placeholder="Password"
                    :class="{ 'invalid-input': errors.password }" />
                <p v-if="errors.password" class="error">
                    {{ errors.password[0] }}
                </p>

                <p v-if="generalError" class="error">
                    {{ generalError }}
                </p>

                <button type="submit" class="login-btn" :disabled="loading">
                    {{ loading ? 'Logging in...' : 'Log In' }}
                </button>

                <p class="small-text">
                    Forgot password?
                    <a @click="goForgot" class="forgot">click here</a>
                </p>

                <button type="button" class="register-btn" @click="goRegister">
                    Register Now!
                </button>
            </form>
        </div>
    </main>
    <footer>
        <p>© 2025 Dash Quiz All Rights Reserved.</p>
    </footer>
</template>

<script setup>
import { ref } from 'vue'

const email = ref('')
const password = ref('')
const errors = ref({})
const generalError = ref('')
const loading = ref(false)

// Routes
const goRegister = () => window.location.href = './register-account'
const goForgot = () => window.location.href = './forgot-password'

const handleLogin = async () => {
    loading.value = true
    errors.value = {}
    generalError.value = ''
    const getCookie = (name) => {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
        return null;
    }

    const csrfToken = getCookie('XSRF-TOKEN')

    try {
        // 1. Get CSRF Cookie
        await fetch('/sanctum/csrf-cookie', { credentials: 'include' })




        // 3. Login Request
        const response = await fetch('/api/login', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-XSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                email: email.value,
                password: password.value
            })
        })

        const data = await response.json()

        if (response.status === 422) {
            errors.value = data.errors
        } else if (response.status === 401 || response.status === 419) {
            generalError.value = data.message
        } else if (response.ok) {
            if (data.status === 'success') {
                window.location.href = data.redirect;
            }
        }
    } catch (err) {
        generalError.value = `Server error. ${err.message}`
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
/* High-priority styles from your Blade file */
.error {
    color: red;
    font-size: 13.5px;
    margin-top: 5px;
}

.invalid-input {
    border: 1px solid red !important;
}

.success-register {
    padding: 10px;
    background: #00b430;
    color: white;
    margin-bottom: 10px;
    text-align: center;
    font-weight: 600;
    box-shadow: 0px 1px 0.5px 1px rgba(255, 255, 255, 1);
}

/* Add your existing .container, .login-box, etc. styles here */
</style>