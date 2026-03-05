import { createRouter, createWebHistory } from "vue-router";
import LoginPage from "./pages/LoginPage.vue";
import RegisterPage from "./pages/RegisterPage.vue";


export default createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            component: LoginPage,
        },
        {
            path: "/registeration",
            component: RegisterPage,
        },
    ]
});