import axios from "axios";

export const startHeartbeat = (router) => { // Accept router here
    setInterval(async () => {
        try {
            const { data } = await axios.post('/api/heartbeat');
            console.log('Heartbeat OK', data);
        } catch (err) {
            console.log("Heartbeat caught an error:", err.response?.status); // Check this in Inspect > Console
            if (err.response?.status === 403 || err.response?.status === 401) {
                localStorage.clear();
                router.push('/');
            }
        }
    }, 5000);
};