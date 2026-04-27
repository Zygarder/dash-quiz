import axios from "axios";
import { useUser } from "./useUser";

const time = 1 * 60 * 1000;

let heartbeat = null;

export const startHeartbeat = (router) => {
    const { user } = useUser();

    if (heartbeat) {
        clearInterval(heartbeat);
        heartbeat = null;
    }

    heartbeat = setInterval(async () => {
        // STOP completely if no user
        if (!user.value) return;

        //  STOP on guest routes
        const guestRoutes = ['/', '/register', '/forgot', '/reset'];
        if (guestRoutes.includes(router.currentRoute.value.path)) return;

        try {
            await axios.post("/api/heartbeat");
        } catch (err) {
            if ([401, 403].includes(err.response?.status)) {
                clearInterval(heartbeat);
                heartbeat = null;

                localStorage.clear();
                router.push("/");
            }
        }
    }, time);
};