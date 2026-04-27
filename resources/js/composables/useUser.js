import { ref, computed } from "vue"
import axios from "axios"

const user = ref(null)
const isLoading = ref(false)
const _fetched = ref(false)

let lastFetchTime = 0
const MIN_INTERVAL = 5000

const fetchUser = async (force = false) => {
    const now = Date.now()

    // Already cached and within cooldown
    if (!force && user.value && now - lastFetchTime < MIN_INTERVAL) {
        return user.value
    }

    // Already know they're a guest — don't re-hit the server
    if (!force && _fetched.value && user.value === null) {
        return null
    }

    // Prevent concurrent fetches
    if (isLoading.value && !force) {
        return user.value
    }

    isLoading.value = true

    try {
        const { data } = await axios.get("/api/me")
        user.value = data.results
        lastFetchTime = now
    } catch {
        user.value = null
    } finally {
        isLoading.value = false
        _fetched.value = true
    }
}

const userFullName = computed(() =>
    `${user.value?.first_name ?? ""} ${user.value?.last_name ?? ""}`.trim()
)

// Builds path consistently — backend stores only filename
const userAvatar = computed(() =>
    `/storage/images/profiles/${user.value?.profile_photo ?? "default.png"}`
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