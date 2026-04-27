import axios from "axios"
import router from "./router"

axios.defaults.baseURL = window.location.origin
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"
axios.defaults.headers.common["Accept"] = "application/json"

axios.interceptors.response.use(
    (response) => response,

    async (error) => {
        if (!error.response) return Promise.reject(error)

        const { status, config } = error.response

        if (status === 419 || status === 401) {

            // Retry once on CSRF expiry
            if (status === 419 && !config._retry) {
                config._retry = true
                try {
                    await axios.get("/sanctum/csrf-cookie")
                    return axios(config)
                } catch {
                    return Promise.reject(error)
                }
            }

            // Redirect to login if not already there
            if (router.currentRoute.value.path !== "/") {
                router.push({ path: "/" })
            }
        }

        return Promise.reject(error)
    }
)

export default axios