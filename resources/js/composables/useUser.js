import { ref, computed } from "vue"
import axios from "axios"

const user = ref(null)
const isLoading = ref(false)

let lastFetchTime = 0
const MIN_INTERVAL = 5000 // 5 seconds cooldown

const fetchUser = async (force = false) => {

    const now = Date.now()

    if (
        !force &&
        user.value &&
        now - lastFetchTime < MIN_INTERVAL
    ) {
        return user.value
    }

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
    }
}

const userFullName = computed(() =>
    `${user.value?.first_name ?? ""} ${user.value?.last_name ?? ""}`.trim()
)

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