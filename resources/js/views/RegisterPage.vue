<template>
    <header class="top-bar">
        Create New Account
    </header>

    <main class="center-container">

        <div class="register-box">
            <h2>Account Registration</h2>
            <form @submit.prevent="handleRegister">
                <input v-model.trim="form.first_name" type="text" maxlength="50" placeholder="First Name"
                    :class="{ 'invalid-input': errors.first_name }" />
                <div v-if="errors.first_name" class="error">{{ errors.first_name[0] }}</div>

                <input v-model.trim="form.last_name" type="text" maxlength="50" placeholder="Last Name"
                    :class="{ 'invalid-input': errors.last_name }" />
                <div v-if="errors.last_name" class="error">{{ errors.last_name[0] }}</div>

                <input v-model.trim="form.email" type="email" placeholder="Email Address" autocomplete="off"
                    :class="{ 'invalid-input': errors.email }" />
                <div v-if="errors.email" class="error">{{ errors.email[0] }}</div>

                <input v-model.trim="form.password" type="password" placeholder="Enter Password" autocomplete="off"
                    :class="{ 'invalid-input': errors.password }" />
                <div v-if="errors.password" class="error">{{ errors.password[0] }}</div>

                <input v-model.trim="form.password_confirmation" autocomplete="off" type="password"
                    placeholder="Re-type Password" :class="{ 'invalid-input': errors.password_confirmation }" />

                <div v-if="generalError" class="error">{{ generalError }}</div>
                <button type="submit" class="register-btn" :disabled="loading">
                    {{ loading ? 'Submitting...' : 'Submit' }}
                </button>

                <p class="small-text">
                    Already have an account? <a href="/">click here</a>
                </p>
            </form>
        </div>
    </main>
    <!--free comment for documentation purposes only, nakalimot ko pag apil sa commit-->
    <footer>
        © 2025 Dash Quiz All Rights Reserved.
    </footer>
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
        const response = await axios.post('/api/register',form)

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
/* Base typography applied to the container */
.center-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    min-height: 80vh;
    font-family: 'Inter', sans-serif; /* Modern Font */
}

/* Original Top Bar Colors */
.top-bar {
    background-color: #4b3fc2; 
    color: #fff;
    padding: 10px 20px;
    text-align: center;
    font-family: 'Inter', sans-serif;
    font-weight: 700;
}

/* Restored proportions with the new fade-in animation */
.register-box {
    background-color: #fff;
    padding: 20px 40px; 
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 350px; 
    display: flex;
    flex-direction: column;
    gap: 5px;
    animation: fadeIn 0.6s ease-out; /* Smooth entrance */
}

.register-box h2 {
    color: #3f2f87; 
    text-align: center;
    margin-bottom: 15px;
    font-weight: 800; /* Bolder, modern weight */
}

.register-box input {
    padding: 10px 15px; 
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    width: 100%;
    box-sizing: border-box;
    transition: all 0.2s ease; /* Smooth border transition */
}

.register-box input:focus {
    border-color: #4b3fc2;
    outline: none;
    box-shadow: 0 0 5px rgba(75, 63, 194, 0.5);
}

.register-btn {
    padding: 12px 5px; 
    width: 100%;
    background-color: #4b3fc2;
    color: #fff;
    font-weight: 700;
    font-family: 'Inter', sans-serif;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); /* Modern button physics */
    margin-top: 10px;
}

/* Added the 'lift' animation on hover */
.register-btn:hover:not(:disabled) {
    background-color: #3f2ea3;
    transform: translateY(-2px); 
    box-shadow: 0 6px 15px rgba(75, 63, 194, 0.3); 
}

.register-btn:active {
    transform: translateY(0);
}

.register-btn:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

.error {
    color: red;
    font-size: 12px;
    margin-top: -5px;
    margin-bottom: 5px;
    font-weight: 600;
}

.invalid-input {
    border: 1px solid red !important;
    background-color: #fef2f2; /* Kept the subtle red background for errors */
}

.small-text {
    text-align: center;
    margin-top: 15px;
    font-size: 0.85rem;
    color: #999;
    font-family: 'Inter', sans-serif;
}

.small-text a {
    color: #4b3fc2;
    text-decoration: none;
    font-weight: 700;
    cursor: pointer;
    transition: color 0.2s;
}

.small-text a:hover {
    color: #3f2ea3;
}

/* Original Fixed Footer */
footer {
    width: 100%;
    position: fixed;
    left: 0;
    bottom: 0;
    background-color: #4b3fc2;
    color: #fff;
    text-align: center;
    padding: 10px;
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    font-size: 0.9rem;
}

/* The Entrance Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 576px) {
    .register-box {
        padding: 25px 15px;
        max-width: 90%;
    }
}
</style>