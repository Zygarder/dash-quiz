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
            <form class="login-box" @submit.prevent="handleLogin">
                <input type="email" v-model="email" placeholder="Email" :class="{ 'invalid-input': errors.email }" />
                <p v-if="errors.email" class="error">
                    {{ errors.email[0] }}
                </p>

                <input type="password" v-model="password" autocomplete="off" placeholder="Password"
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

                <router-link class="register-btn" to="register">Register Now!</router-link>
            </form>
        </div>
    </main>
    <footer>
        <p>© 2025 Dash Quiz All Rights Reserved.</p>
    </footer>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const email = ref('');
const password = ref('');

const loading = ref(false);
const errors = ref({});
const generalError = ref('');
const router = useRouter();

const handleLogin = async () => {
    loading.value = true;
    errors.value = {};
    generalError.value = '';

    try {
        // 1. Initialize CSRF protection 
        // Laravel sets a cookie named XSRF-TOKEN here
        await axios.get('/sanctum/csrf-cookie');

        // 2. Perform Login
        // Axios automatically picks up that cookie and sends it as a header
        const response = await axios.post('/login', {
            email: email.value,
            password: password.value
        });

        // 3. Success: Redirect to Dashboard using Vue Router
        if (response.status === 200 || response.status === 204) {
            router.push('/dashboard');
        }

    } catch (err) {
        // Axios puts the server response inside err.response
        if (err.response) {
            const status = err.response.status;
            const data = err.response.data;

            if (status === 422) {
                // Validation errors (e.g., "The password field is required")
                errors.value = data.errors;
            } else if (status === 401 || status === 419) {
                // Unauthorized or Session Expired
                generalError.value = data.message || "Session expired. Please refresh.";
            } else {
                generalError.value = "An unexpected server error occurred.";
            }
        } else {
            // Network error (Server is down)
            generalError.value = "Cannot connect to server. Check your connection.";
        }
    } finally {
        loading.value = false;
    }
};
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