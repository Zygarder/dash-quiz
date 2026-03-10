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

        // hashed before sending, to prevent sending plain text
        const hashed_password = form.password
        const hashed_confirmation = form.password_confirmation

        // 3. Post to Register API
        const response = await axios.post('/api/register', {
            'first_name': form.first_name,
            'last_name': form.last_name,
            'email': form.email,
            'password': hashed_password,
            'password_confirmation': hashed_confirmation
        })

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
/* Scoped ensures these styles don't bleed out into other pages */
.top-bar {
    background-color: #4b3fc2;
    color: #fff;
    padding: 10px 20px;
    text-align: center;
}

.center-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    min-height: 80vh;
}

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
}

.register-box h2 {
    color: #3f2f87;
    text-align: center;
    margin-bottom: 15px;
}

.register-box input {
    padding: 10px 5px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 13px;
    text-indent: 10px;
    width: 100%;
    box-sizing: border-box;
}

.register-box input:focus {
    border-color: #4b3fc2;
    outline: none;
    box-shadow: 0 0 5px rgba(75, 63, 194, 0.5);
}

.register-btn {
    padding: 10px 5px;
    width: 100%;
    background-color: #4b3fc2;
    color: #fff;
    font-weight: bold;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.5s ease-out;
    margin-top: 10px;
}

.register-btn:hover {
    background-color: #3f2ea3;
}

.register-btn:disabled {
    background-color: #ccc;
}

.error {
    color: red;
    font-size: 12px;
    margin-top: -5px;
    margin-bottom: 5px;
}

.invalid-input {
    border: 1px solid red !important;
}


.small-text {
    text-align: center;
    margin-top: 15px;
    font-size: 0.85rem;
    color: #999;
}

.small-text a {
    color: #4b3fc2;
    text-decoration: none;
    font-weight: bold;
    cursor: pointer;
}

footer {
    width: 100%;
    position: fixed;
    left: 0;
    bottom: 0;
    background-color: #4b3fc2;
    color: #fff;
    text-align: center;
    padding: 10px;
}

@media (max-width: 576px) {
    .register-box {
        padding: 25px 15px;
        max-width: 90%;
    }
}
</style>