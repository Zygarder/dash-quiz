<template>
    <div class="admin-wrapper">
        <div class="sidebar-overlay" :class="{ active: isSidebarOpen && isMobile }" @click="isSidebarOpen = false" />

        <AdminSidebar :isSidebarOpen="isSidebarOpen" @logout="handleLogout" @closeSidebar="closeSidebar" />

        <div class="main-wrapper">
            <AdminTopbar :currentPageTitle="currentPageTitle" @toggleSidebar="toggleSidebar" />
            <main class="admin-main">
                <router-view />
            </main>
        </div>
    </div>
</template>

<script setup>
import AdminSidebar from '@/components/AdminSide/AdminSideBar.vue'
import AdminTopbar from '@/components/AdminSide/AdminTopBar.vue'
import axios from 'axios'
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

const isSidebarOpen = ref(false)
const windowWidth = ref(window.innerWidth)

const updateWindowWidth = () => {
    windowWidth.value = window.innerWidth
    if (windowWidth.value > 1024) {
        isSidebarOpen.value = false
    }
}

onMounted(() => window.addEventListener('resize', updateWindowWidth))
onUnmounted(() => window.removeEventListener('resize', updateWindowWidth))

const isMobile = computed(() => windowWidth.value <= 768)

const pageTitles = {
    '/admin': 'Dashboard',
    '/admin/dashboard': 'Dashboard',
    '/admin/manage-quizzes': 'Quizzes',
    '/admin/users': 'Users',
    '/admin/records': 'Records',
}

const currentPageTitle = computed(() => pageTitles[route.path] ?? 'Dashboard')

const toggleSidebar = () => { isSidebarOpen.value = !isSidebarOpen.value }
const closeSidebar = () => { isSidebarOpen.value = false }

const handleLogout = async () => {
    if (confirm('Are you sure you want to logout?')) {
        await axios.post('/api/logout')
        localStorage.removeItem('isLoggedIn')
        localStorage.removeItem('userRole')
        router.push('/')
    }
}

watch(isSidebarOpen, (val) => {
    if (val && isMobile.value) {
        document.body.classList.add('sidebar-open')
    } else {
        document.body.classList.remove('sidebar-open')
    }
})
</script>

<style scoped>
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* ── WRAPPER ── */
.admin-wrapper {
    display: flex;
    width: 100%;
    min-height: 100vh;
    background: #f8fafc;
    overflow-x: hidden;
}

/* ── MAIN AREA ── */
.main-wrapper {
    flex: 1;
    margin-left: 240px;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    min-width: 0;
    transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ── PAGE CONTENT ── */
.admin-main {
    flex: 1;
    width: 100%;
    padding: clamp(1rem, 3vw, 2rem);
    overflow-x: hidden;
}

.admin-main>* {
    width: 100%;
    min-width: 0;
}

/* ── OVERLAY ── */
.sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 999;
    backdrop-filter: blur(2px);
}

.sidebar-overlay.active {
    opacity: 1;
    pointer-events: all;
}

/* ── TABLET: 769px – 1024px ── */
@media (max-width: 1024px) and (min-width: 769px) {
    .main-wrapper {
        margin-left: 220px;
    }
}

/* ── MOBILE: ≤768px ── */
@media (max-width: 768px) {
    .main-wrapper {
        margin-left: 0;
    }

    .admin-main {
        padding: clamp(0.875rem, 4vw, 1.5rem);
    }
}

/* ── SMALL MOBILE ── */
@media (max-width: 480px) {
    .admin-main {
        padding: 0.875rem;
    }
}

/* ── BODY LOCK ── */
:deep(body.sidebar-open) {
    overflow: hidden;
}
</style>