import { ref, computed, watch } from "vue"
import axios from "axios"

// shared state
const user = ref(null)
let loading = false

const fetchUser = async () => {

    loading = true

    try {
        const { data } = await axios.get("/api/me")
        user.value = data.data
    } catch (err) {
        console.error("Failed to fetch user:", err)
    } finally {
        loading = false
    }

    return user.value
}

/**
 *  clears current auth user
 */
const clearUser = () => {
    user.value = null
}

const avatar = computed(() => {
    const photo = user.value?.profile_photo || "default.png"
    return `/storage/images/profiles/${photo}`
})

watch(() => avatar, (newAvatar) => {
    avatar.value = newAvatar;
})

export function useUser() {
    return {
        user,
        fetchUser,
        avatar,
        clearUser
    }
}