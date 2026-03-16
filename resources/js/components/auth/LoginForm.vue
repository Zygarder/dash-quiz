<template>
    <form class="login-box" @submit.prevent="handleLogin">

        <input type="email" v-model.trim="email" placeholder="Email" :class="{ 'invalid-input': errors.email }" />

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
            {{ loading ? "Logging in..." : "Log In" }}
        </button>

        <div class="small-text">
            Forgot password?
            <router-link to="/forgot" class="forgot">
                click here
            </router-link>
        </div>

        <router-link class="register-btn" to="/register">
            Register Now!
        </router-link>

    </form>
</template>

<script setup>
import { ref } from "vue"
import { useRouter } from "vue-router"
import axios from "axios"

const router = useRouter()

const email = ref("")
const password = ref("")
const loading = ref(false)
const errors = ref({})
const generalError = ref("")

const handleLogin = async () => {

    loading.value = true
    errors.value = {}
    generalError.value = ""

    try {

        // Sanctum CSRF
        await axios.get("/sanctum/csrf-cookie")

        const response = await axios.post("/api/login", {
            email: email.value,
            password: password.value
        })

        if (response.status === 200 || response.status === 204) {

            localStorage.setItem("isLoggedIn", "true")

            if (response.data.role) {
                localStorage.setItem("userRole", response.data.role)
            }

            if (response.data.role === "admin") {
                router.push("/admin/dashboard")
            } else {
                router.push("/home")
            }
        }

    } catch (err) {

        localStorage.removeItem("isLoggedIn")

        const { status, data } = err.response || {}

        if (status === 422 && data.errors) {
            errors.value = data.errors
        }

        if (status === 401 || status === 419) {
            generalError.value =
                data?.message || "Invalid credentials or session expired."
        }

    } finally {
        loading.value = false
    }
}
</script>