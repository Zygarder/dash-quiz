<template>
    <main class="auth-wrapper">
        <div class="register-card">
            <header class="card-header">
                <h1>Create Account</h1>
                <p>Join Dash Quiz and start your journey</p>
            </header>

            <form @submit.prevent="handleRegister" class="registration-form">
                <div class="input-group">
                    <input v-model.trim="form.first_name" type="text" id="first_name" placeholder="First Name" required
                        :class="{ 'input-error': errors.first_name }" />
                    <span v-if="errors.first_name" class="error-msg">{{ errors.first_name[0] }}</span>
                </div>

                <div class="input-group">
                    <input v-model.trim="form.last_name" type="text" id="last_name" placeholder="Last Name"
                        :class="{ 'input-error': errors.last_name }" />
                    <span v-if="errors.last_name" class="error-msg">{{ errors.last_name[0] }}</span>
                </div>

                <div class="input-group">
                    <input v-model.trim="form.email" type="email" id="email" placeholder="Email Address" required
                        :class="{ 'input-error': errors.email }" />
                    <span v-if="errors.email" class="error-msg">{{ errors.email[0] }}</span>
                </div>

                <div class="input-group">
                    <input v-model.trim="form.password" type="password" id="password" placeholder="Password" required
                        :class="{ 'input-error': errors.password }" />
                    <span v-if="errors.password" class="error-msg">{{ errors.password[0] }}</span>
                </div>

                <div class="input-group">
                    <input v-model.trim="form.password_confirmation" type="password" id="password_confirmation"
                        placeholder="Confirm Password" required />
                </div>

                <div v-if="generalError" class="general-error">{{ generalError }}</div>

                <button type="submit" class="submit-btn" :disabled="loading">
                    <span v-if="!loading">Create Account</span>
                    <span v-else class="loader-dots">
                        <span v-if="loading">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>Creating Account...
                    </span>
                </button>

                <p class="footer-text">
                    Already have an account? <router-link to="/">Login</router-link>
                </p>
            </form>
        </div>

        <footer class="legal-footer">
            © 2025 Dash Quiz • All Rights Reserved.
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
        // 1. Get CSRF Cookie
        await axios.get('/sanctum/csrf-cookie')

        // 3. Post to Register API
        const response = await axios.post('/api/register', form)

        if (response.status === 422) {
            errors.value = response.errors
        } else if (response.data) {
            // Success - redirect to login or dashboard
            router.push('/')
        } else {
            generalError.value = data.message || 'Something went wrong.'
        }

    } catch (err) {
        console.log(err.response.data)
        errors.value = err.response.data.errors
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
.auth-wrapper {
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
    background: radial-gradient(circle at top left, #6d5dfc, #4b3fc2);
}

.register-card {
    background: white;
    width: 100%;
    max-width: 380px;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.card-header {
    text-align: center;
}

.card-header h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.card-header p {
    color: #6b7280;
    font-size: 0.95rem;
}

.registration-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.input-group {
    position: relative;
    width: 100%;
}

.input-group input {
    width: 95%;
    padding: 15px 10px;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    outline: none;
    transition: all 0.2s ease;
    background: #fdfdfd;
}

.input-group label {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 14px;
    pointer-events: none;
    transition: all 0.2s ease;
    background: transparent;
}

/* Floating Label Logic */
.input-group input:focus~label,
.input-group input:not(:placeholder-shown)~label {
    top: 0;
    font-size: 12px;
    font-weight: 600;
    color: #4b3fc2;
    background: white;
    padding: 0 6px;
    transform: translateY(-50%);
}

.input-group input:focus {
    border-color: #4b3fc2;
    background: white;
    box-shadow: 0 0 0 4px rgba(75, 63, 194, 0.1);
}

/* Validation States */
.input-error {
    border-color: #ef4444 !important;
}

.error-msg {
    color: #ef4444;
    font-size: 11px;
    font-weight: 600;
    margin-top: 4px;
    display: block;
}

.general-error {
    background: #fef2f2;
    color: #b91c1c;
    padding: 12px;
    border-radius: 8px;
    font-size: 13px;
    text-align: center;
}

/* Button Styling */
.submit-btn {
    width: 100%;
    padding: 16px;
    background: #4b3fc2;
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 10px;
}

.submit-btn:hover:not(:disabled) {
    background: #3a2fad;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(75, 63, 194, 0.2);
}

.submit-btn:active {
    transform: translateY(0);
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Loading Spinner */
.loader {
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.footer-text {
    text-align: center;
    font-size: 14px;
    color: #6b7280;
}

.footer-text a {
    color: #4b3fc2;
    text-decoration: none;
    font-weight: 600;
}

.footer-text a:hover {
    text-decoration: underline;
}

.legal-footer {
    margin-top: 30px;
    color: rgba(255, 255, 255, 0.8);
    font-size: 12px;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Mobile Adjustments */
@media (max-width: 480px) {
    .input-row {
        grid-template-columns: 1fr;
    }

    .register-card {
        padding: 30px 20px;
    }
}
</style>