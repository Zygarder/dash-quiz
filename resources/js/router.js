import { createRouter, createWebHistory } from 'vue-router'
// User Pages
import Dashboard from './views/UserPages/Dashboard.vue'
import Profile from './views/UserPages/ProfilePage.vue'
import Records from './views/UserPages/RecordsPage.vue'
import QuizPage from './views/UserPages/QuizPage.vue'
import TakeQuizPage from './views/UserPages/TakeQuizPage.vue'
import QuizResult from './views/UserPages/QuizResult.vue'

// Landing Page
import LoginPage from './views/LoginPage.vue'
import RegisterPage from './views/RegisterPage.vue'
import ForgotPage from './views/ForgotPage.vue'

// Admin Pages
import AdminDashboard from './views/AdminPages/AdminDashboard.vue'
import UsersTable from './views/AdminPages/UsersTable.vue'
import StudentRecords from './views/AdminPages/StudentRecords.vue'
import QuizAdd from './views/AdminPages/QuizAdd.vue'
import QuizEdit from './views/AdminPages/QuizEdit.vue'
import ManageQuestions from './views/AdminPages/ManageQuestions.vue'

const routes = [
    {
        path: "/",
        name: 'login', // Added name so next({ name: 'login' }) works
        component: LoginPage,
    },
    {
        path: "/register",
        component: RegisterPage,
    },
    {
        path: "/forgot",
        component: ForgotPage
    },
    {
        path: '/dashboard',
        component: Dashboard,
        meta: { requiresAuth: true } // Added meta tag
    },
    {
        path: '/profile',
        component: Profile,
        meta: { requiresAuth: true } // Added meta tag
    },
    {
        path: '/records',
        component: Records,
        meta: { requiresAuth: true } // Added meta tag
    }, 
    {
        path: '/quizzes',
        component: QuizPage,
        meta: { requiresAuth: true } // Added meta tag
    },
    {
        path: '/quiz/:quiz_id',
        name: 'quiz-start',
        component: TakeQuizPage,
        meta: { requiresAuth: true }
    },
    {
        path: '/quiz-result',
        component: QuizResult,
        meta: { requiresAuth: true }
    },
    // Admin routes
    {
        path: '/admin/dashboard',
        component: AdminDashboard,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/admin/users',
        component: UsersTable,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/admin/records',
        component: StudentRecords,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/admin/quizzes/create',
        component: QuizAdd,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/admin/quizzes/edit/:id',
        component: QuizEdit,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/admin/quizzes/manage',
        component: ManageQuestions,
        meta: { requiresAuth: true, requiresAdmin: true }
    }
]

// 1. Create the router instance first
const router = createRouter({
    history: createWebHistory(),
    routes
})

// 2. Attach the guard to the 'router' instance
router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('isLoggedIn') === 'true';

    // If the route requires auth and user is NOT logged in, kick to login
    if (to.meta.requiresAuth && !isAuthenticated) {
        next({ name: 'login' });
    } 
    // Prevent logged-in users from seeing the login page again
    else if (to.name === 'login' && isAuthenticated) {
        next({ path: '/dashboard' });
    }
    // Admin-only route check
    else if (to.meta.requiresAdmin) {
        const role = localStorage.getItem('userRole');
        if (role !== 'admin') {
            next({ path: '/' });
        } else {
            next();
        }
    }
    else {
        next();
    }
})

// 3. Export the instance
export default router