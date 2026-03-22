import { ref, computed } from "vue"
import axios from "axios"

const user = ref('')
let isLoading = false

const fetchUser = async () => {

    if (user.value || isLoading) return user.value

    isLoading = true

    try {
        const { data } = await axios.get("/api/me")
        user.value = data.results

    } catch (err) {
        console.error("Failed to fetch USER:", err.response || err.message)
    } finally {
        isLoading = false
    }
}
const userFullName = computed(() => `${user.value.first_name} ${user.value.last_name}`)

const clearUser = () => {
    user.value = null
}


const userAvatar = computed(() => {
    const photo = user.value?.profile_photo || "default.png"
    return `/storage/images/profiles/${photo}`
})

export function useUser() {
    return {
        user,
        userFullName,
        fetchUser,
        userAvatar,
        clearUser
    }
}