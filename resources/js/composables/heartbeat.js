import axios from "axios";

const time = 1 * 60 * 1000 // 1 min

export const startHeartbeat = (router) => { // Accept router here
    setInterval(async () => {
        if (!window.location.origin) return; // Skip if origin is not set (e.g., during SSR or certain test environments)
        try {
            await axios.post('/api/heartbeat');
        } catch (err) {
            if (err.response?.status === 403 || err.response?.status === 401) {
                localStorage.clear();
                router.push('/');
            }
        }
    }, time);
};