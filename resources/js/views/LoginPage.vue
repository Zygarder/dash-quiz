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
import { useUser } from '@/composables/useUser';

const email = ref('');
const password = ref('');

const loading = ref(false);
const errors = ref({});
const generalError = ref('');
const router = useRouter();
const { clearUser } = useUser();

const handleLogin = async () => {
    loading.value = true;
    errors.value = {};
    generalError.value = '';

    try {
        // 1. Initialize CSRF protection 
        await axios.get('/sanctum/csrf-cookie');

        // 2. Perform Login
        const response = await axios.post('/api/login', {
            email: email.value,
            password: password.value
        });

        // 3. Success Logic
        if (response.status === 200 || response.status === 204) {

            // --- THE CRITICAL UPDATE ---
            // Set the flag that the router guard is looking for
            localStorage.setItem('isLoggedIn', 'true');
            // store role returned by api for admin checks
            if (response.data.role) {
                localStorage.setItem('userRole', response.data.role);
            }

            // clear previous profile so next page will refetch
            clearUser();
            // redirect based on role
            if (response.data.role === 'admin') {
                router.push('/admin/dashboard');
            } else {
                router.push('/dashboard');
            }
        }

    } catch (err) {
        if (err.response) {
            const status = err.response.status;
            const data = err.response.data;

            if (status === 422) {
                errors.value = data.errors;
            } else if (status === 401 || status === 419) {
                // If login fails, ensure the flag is cleared
                localStorage.removeItem('isLoggedIn');
                generalError.value = data.message || "Invalid credentials or session expired.";
            } else {
                generalError.value = "An unexpected server error occurred.";
            }
        } else {
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

.register-btn {
    display: block;
    text-align: center;
    text-decoration: none; 
}
</style>