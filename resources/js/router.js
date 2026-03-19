import { createRouter, createWebHistory } from 'vue-router'
// User Pages
import HomePage from './views/UserPages/HomePage.vue'
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
import AdminLayout from './views/AdminPages/DRYLayout/AdminLayout.vue'
import UsersTable from './views/AdminPages/UsersTable.vue'
import StudentRecords from './views/AdminPages/StudentRecords.vue'
import QuizAdd from './views/AdminPages/QuizAdd.vue'
import QuizEdit from './views/AdminPages/QuizEdit.vue'
import ManageQuestions from './views/AdminPages/ManageQuestions.vue'


const routes = [

    {
        path: "/",
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
        path: '/home',
        component: HomePage,
        meta: { requiresAuth: true, requiresStudent: true }
    },
    {
        path: '/profile',
        component: Profile,
        meta: { requiresAuth: true, requiresStudent: true }
    },
    {
        path: '/records',
        component: Records,
        meta: { requiresAuth: true, requiresStudent: true }
    },
    {
        path: '/quizzes',
        component: QuizPage,
        meta: { requiresAuth: true, requiresStudent: true }
    },
    {
        path: '/quiz/:quiz_id',
        name: 'quiz-start',
        component: TakeQuizPage,
        meta: { requiresAuth: true, requiresStudent: true }
    },
    {
        path: '/quiz-result',
        component: QuizResult,
        meta: { requiresAuth: true, requiresStudent: true }
    },

    // Admin routes
    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true, requiresAdmin: true },
        children: [
            {
                path: '/', // /admin
                component: AdminDashboard
            },
            {
                path: 'dashboard', // /admin/dashboard
                component: AdminDashboard
            },
            {
                path: 'users',
                component: UsersTable
            },
            {
                path: 'records',
                component: StudentRecords
            },
            {
                path: 'quizzes/create',
                component: QuizAdd
            },
            {
                path: 'quizzes/:id/edit',
                component: QuizEdit
            },
            {
                path: 'quizzes/manage',
                component: ManageQuestions
            }
        ]
    },
    // No Page Dound / 404 Error page, 
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: () => import('./views/404.vue')
    }

]

// 1. Create the router instance first
const router = createRouter({
    history: createWebHistory(),
    routes
})

// Attach the guard to the 'router' instance
router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('isLoggedIn') === 'true';
    const userRole = localStorage.getItem('userRole');

    // 1. Unauthenticated users must go to Login
    if (to.meta.requiresAuth && !isAuthenticated) {
        return next({ path: '/' });
    }

    // 2. Prevent logged-in users from seeing the Login/Register page
    if ((to.path === '/' || to.path === '/register') && isAuthenticated) {
        return next(userRole === 'admin' ? '/admin/dashboard' : '/home');
    }

    // 3. Admin Protection: Stop students from seeing Admin pages
    if (to.meta.requiresAdmin && userRole !== 'admin') {
        return next({ path: '/home' });
    }

    // 4. Student Protection: Stop admins from seeing Student pages
    if (to.meta.requiresStudent && userRole === 'admin') {
        return next({ path: '/admin/dashboard' });
    }

    // Default: Allow through
    next();
});

export default router