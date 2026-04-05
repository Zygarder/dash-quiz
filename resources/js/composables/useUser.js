import { ref, computed } from "vue"
import axios from "axios"

const user = ref(null)
const isLoading = ref(false)

const fetchUser = async (force = false) => {
    //  allow refresh when needed
    if (!force && user.value && isLoading.value === false) {
        return user.value
    }

    isLoading.value = true
    try {
        const { data } = await axios.get("/api/me")
        user.value = data.results
        console.log(user.value)
    } catch (err) {
        console.error("Failed to fetch USER:", err.response?.data?.message || err.message)
    } finally {
        isLoading.value = false
    }
}

const userFullName = computed(() =>
    `${user.value?.first_name || ''} ${user.value?.last_name || ''}`
)

const userAvatar = computed(() =>
    `/storage/images/profiles/${user.value?.profile_photo || "default.png"}`
)

export function useUser() {
    return {
        user,
        isLoading,
        userFullName,
        userAvatar,
        fetchUser,
    }
}