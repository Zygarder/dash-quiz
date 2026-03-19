import axios from "axios"
import router from "./router"

// Global defaults
axios.defaults.baseURL = "http://127.0.0.1:8000"
axios.defaults.withCredentials = true   
axios.defaults.withXSRFToken = true

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"
axios.defaults.headers.common["Accept"] = "application/json"


/*
|--------------------------------------------------------------------------
| Axios Response Interceptor
|--------------------------------------------------------------------------
| Handles authentication errors globally.
|
| 419 = CSRF expired
| 401 = Unauthenticated
|
*/

axios.interceptors.response.use(

    (response) => response,

    async (error) => {

        // Prevent crash when response is undefined (CORS / network errors)
        if (!error.response) {
            console.error("Network or CORS error:", error.message)
            return Promise.reject(error)
        }

        const { status, config } = error.response

        if (status === 419 || status === 401) {

            localStorage.removeItem("isLoggedIn")
            localStorage.removeItem("userRole")

            // Retry once if CSRF expired
            if (status === 419 && !config._retry) {

                config._retry = true

                try {
                    await axios.get("/sanctum/csrf-cookie")
                    return axios(config)
                } catch (csrfError) {
                    return Promise.reject(csrfError)
                }

            }

            // Redirect to login
            if (router.currentRoute.value.path !== "/") {
                router.push({ path: "/" })
            }

        }

        return Promise.reject(error)

    }

)

export default axios