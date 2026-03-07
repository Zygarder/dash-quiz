import { ref, computed } from "vue"
import axios from "axios"

// Shared user state across components
const user = ref({}) // start as object so template access safe
let loading = false

const fetchUser = async () => {
    // don't refetch if we already have an id or a request in-flight
    if ((user.value && user.value.id) || loading) {
        return user.value
    }
    loading = true
    try {
        const { data } = await axios.get("/api/me")
        // if API returns wrapped in { status, data }, adjust accordingly
        user.value = data.data
    } catch (err) {
        console.error("Failed to fetch user:", err)
    } finally {
        loading = false
    }
    return user.value
}

const clearUser = () => {
    user.value = {}
}

const avatar = computed(() => {
    if (!user.value) return "storage/app/public/images/profiles/69abed331049a.jpg"
    
    return user.value.profile_photo
})


export function useUser() {
    return {
        user,
        fetchUser,
        avatar,
        clearUser
    }
}