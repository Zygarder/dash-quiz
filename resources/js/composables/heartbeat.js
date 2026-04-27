import axios from "axios"
import { useUser } from "./useUser"

const TIME = 1 * 60 * 1000 // 1 minute
let heartbeat = null

export const startHeartbeat = (router) => {
    const { user } = useUser()

    // Clear any existing interval before starting a new one
    if (heartbeat) {
        clearInterval(heartbeat)
        heartbeat = null
    }

    heartbeat = setInterval(async () => {
        if (!user.value) return

        const guestRoutes = ['/', '/register', '/forgot', '/reset']
        if (guestRoutes.includes(router.currentRoute.value.path)) return

        try {
            await axios.post("/api/heartbeat")
        } catch (err) {
            if ([401, 403].includes(err?.response?.status)) {
                clearInterval(heartbeat)
                heartbeat = null
                user.value = null  // clear session cache
                router.push("/")
            }
        }
    }, TIME)
}