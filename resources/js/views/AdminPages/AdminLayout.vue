<template>
    <div class="admin-wrapper">
        <!-- Mobile overlay -->
        <div class="sidebar-overlay" :class="{ active: isSidebarOpen && isMobile }" @click="isSidebarOpen = false" />

        <AdminSidebar :isSidebarOpen="isSidebarOpen" @logout="handleLogout" @closeSidebar="closeSidebar" />

        <div class="main-wrapper" :class="{ 'sidebar-open': isSidebarOpen && isMobile }">
            <AdminTopbar :currentPageTitle="currentPageTitle" @toggleSidebar="toggleSidebar" @logout="handleLogout" />

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
import { ref, watch, computed, onMounted, onUnmounted } from 'vue'
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

const showMenuButton = computed(() => screenWidth.value <= 1024)
const showPageTitle = computed(() => screenWidth.value > 768)
const showUserMeta = computed(() => screenWidth.value > 640)
const showAvatar = computed(() => screenWidth.value > 480)

onMounted(() => {
    window.addEventListener('resize', updateWindowWidth)
})

onUnmounted(() => {
    window.removeEventListener('resize', updateWindowWidth)
})

const isMobile = computed(() => windowWidth.value <= 1024)
const isTablet = computed(() => windowWidth <= 1024 && windowWidth > 768)
const isPhone = computed(() => windowWidth <= 640)

const pageTitles = {
    '/admin/dashboard': 'Dashboard',
    '/admin/manage-quizzes': 'Quizzes',
    '/admin/users': 'Users',
    '/admin/records': 'Records',
}

const currentPageTitle = computed(() => pageTitles[route.path] ?? 'Dashboard')

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
}

const closeSidebar = () => {
    isSidebarOpen.value = false
}

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
/* ROOT RESET */
:deep(*),
:deep(*::before),
:deep(*::after) {
    box-sizing: border-box;
}

/* MAIN WRAPPER */
.admin-wrapper {
    width: 100%;
    display: flex;
    min-height: 100vh;
    background: #f8fafc;
}

/* DESKTOP: Sidebar always visible */
.main-wrapper {
    display: flex;
    width: 100%;
    flex-direction: column;
    min-height: 100vh;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* MAIN CONTENT */
.admin-main {
    flex: 1;
    padding: clamp(1rem, 3vw, 2rem);
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    overflow-x: auto;
}

/* ========================================
   RESPONSIVE BREAKPOINTS
======================================== */

/* TABLET: Narrower sidebar */
@media (max-width: 1024px) {
    .main-wrapper {
        margin-left: 220px;
    }
}

/* MOBILE: Full-width, overlay sidebar */
@media (max-width: 768px) {
    .admin-wrapper {
        position: relative;
    }

    .main-wrapper {
        margin-left: 0 !important;
    }

    .admin-main {
        padding: clamp(0.75rem, 4vw, 1.25rem);
    }
}

/* SMALL MOBILE: Extra compact */
@media (max-width: 480px) {
    .admin-main {
        padding: 1rem 0.75rem;
    }
}

/* MOBILE SIDEBAR OVERLAY */
.sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 999;
}

.sidebar-overlay.active {
    opacity: 1;
    pointer-events: all;
}

/* MOBILE: Prevent body scroll when sidebar open */
:deep(body.sidebar-open) {
    overflow: hidden;
}

/* Ensure content doesn't jump */
.admin-main>* {
    width: 100%;
    min-width: 0;
}

/* PERFECT RESPONSIVE PADDING */
.admin-main {
    padding-left: clamp(0.75rem, 3vw, 2rem);
    padding-right: clamp(0.75rem, 3vw, 2rem);
}

/* TABLET OPTIMIZATION */
@media (max-width: 1024px) and (min-width: 769px) {
    .admin-main {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }
}

/* PREVENT HORIZONTAL SCROLL */
.admin-wrapper {
    overflow-x: hidden;
}

.admin-main {
    overflow-x: visible;
}

/* TOUCH FRIENDLY ON MOBILE */
@media (hover: none) and (pointer: coarse) {
    .menu-trigger {
        min-height: 44px;
        min-width: 44px;
    }
}
</style>