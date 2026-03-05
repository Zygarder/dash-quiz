import { createRouter, createWebHistory } from 'vue-router'
// User Pages
import Dashboard from './views/UserPages/Dashboard.vue'
import Profile from './views/UserPages/ProfilePage.vue'
import Records from './views/UserPages/RecordsPage.vue'
import QuizPage from './views/UserPages/QuizPage.vue'
// Landing Page
import LoginPage from './views/LoginPage.vue'
import RegisterPage from './views/RegisterPage.vue'

const routes = [{
    path: "/",
    component: LoginPage,
},
{
    path: "/register",
    component: RegisterPage,
},
{
    path: '/dashboard',
    component: Dashboard
},
{
    path: '/profile',
    component: Profile
},
{
    path: '/records',
    component: Records
}, {
    path: '/quizzes',
    component: QuizPage
}
]

export default createRouter({
    history: createWebHistory(),
    routes
})