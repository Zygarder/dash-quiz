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
                <input type="email" v-model.trim="email" placeholder="Email"
                    :class="{ 'invalid-input': errors.email }" />
                <div v-if="errors.email" class="error">
                    {{ errors.email[0] }}
                </div>

                <input type="password" v-model.trim="password" autocomplete="off" placeholder="Password"
                    :class="{ 'invalid-input': errors.password }" />
                <div v-if="errors.password" class="error">
                    {{ errors.password[0] }}
                </div>

                <div v-if="generalError" class="error">
                    {{ generalError }}
                </div>

                <button type="submit" class="login-btn" :disabled="loading">
                    {{ loading ? 'Logging in...' : 'Log In' }}
                </button>

                <div class="small-text">
                    Forgot password?
                    <router-link to="/forgot" class="forgot">click here</router-link>
                </div>

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
import CryptoJS from 'crypto-js';
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

        //hashed before to prevent submitting plain text 
        if (password.value !== '') {
            password.value = CryptoJS.SHA256(password.value).toString()
        }

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
                router.push('/home');
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
/* === TOP BAR / HEADER === */
.top-bar {
    background-color: #4b3fc2;
    color: #fff;
    padding: 14px 20px;
    text-align: center;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    font-size: 1rem;
}

/* === MAIN CONTAINER === */
.container {
    min-height: calc(100vh - 70px);
    /* adjust for header */
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 35px;
    flex-wrap: wrap;
    padding: 20px;
    background: #f5f5f8;
    animation: fadeIn 0.3s ease;
}

/* === LEFT TEXT SECTION === */
.left-section {
    flex: 400px;
    max-width: 500px;
}

.left-section h1 {
    font-size: clamp(2rem, 5vw, 2.8rem);
    text-align: center;
    font-weight: 800;
    line-height: 1.2;
    color: #2d2d2d;
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.06);
}

/* === LOGIN BOX === */
.login-box {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.08);
    padding: 30px 28px;
    width: 350px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 34px rgba(0, 0, 0, 0.12);
}

/* === INPUT FIELDS === */
input {
    padding: 13px;
    border: 1.5px solid #eee;
    border-radius: 8px;
    font-size: 0.95rem;
    outline: none;
    background: #fdfdfd;
    transition: all 0.25s ease;
}

input:focus {
    border-color: #4b3fc2;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(75, 63, 194, 0.1);
}

.invalid-input {
    border-color: #ff4d4d !important;
    background-color: #fff5f5;
}

.error {
    color: #ff4d4d;
    font-size: 0.85rem;
    font-weight: 500;
    margin-top: -4px;
}

/* === BUTTONS === */
.login-btn {
    background-color: #4b3fc2;
    color: #fff;
    border: none;
    padding: 13px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.25s ease;
    margin-top: 10px;
}

.login-btn:hover:not(:disabled) {
    background-color: #3a32a8;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(75, 63, 194, 0.3);
}

.login-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.register-btn {
    background-color: #00a02b;
    color: #fff;
    border: none;
    padding: 11px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 700;
    text-align: center;
    text-decoration: none;
    transition: background 0.25s, transform 0.25s;
}

.register-btn:hover {
    background-color: #008822;
    transform: translateY(-1px);
}

/* === SMALL HELPER TEXT === */
.small-text {
    text-align: center;
    font-size: 0.88rem;
    color: #666;
    margin: 5px 0;
}

.forgot {
    color: #4b3fc2;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: color 0.2s;
}

.forgot:hover {
    text-decoration: underline;
    color: #3a32a8;
}

/* === FOOTER === */
footer {
    padding: 18px;
    text-align: center;
    background-color: #f8f9fc;
    border-top: 1px solid #eee;
    color: #888;
    font-size: 0.85rem;
}

/* === RESPONSIVE === */
@media screen and (min-width: 480px) and (max-width: 768px) {
    .container {
        flex-direction: column;
        text-align: center;
        padding-top: 40px;
    }

    .left-section {
        max-width: 100%;
    }

    .left-section h1 {
        text-align: center;
    }

    .login-box {
        padding: 25px 20px;
    }
}

/* === ANIMATION === */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(8px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
