<template>
  <div class="user-wrapper">
    <UserSidebar :isSidebarOpen="isSidebarOpen" @logout="handleLogout" @closeSidebar="closeSidebar" />
    <!-- Mobile overlay -->
    <div class="sidebar-overlay" :class="{ active: isSidebarOpen && isMobile }" @click="isSidebarOpen = false" />

    <div class="main-wrapper" :class="{ 'sidebar-open': isSidebarOpen && isMobile }">
      <UserTopbar :currentPageTitle="currentPageTitle" @toggleSidebar="toggleSidebar" />
      <main class="user-main">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import UserSidebar from '@/components/UserSide/testSide.vue'
import UserTopbar from '@/components/UserSide/testTop.vue'
import axios from 'axios'
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import SuccessAlert from '@/components/ToastNotification.vue'

const router = useRouter()
const route = useRoute()

const isSidebarOpen = ref(false)
let windowWidth = window.innerWidth

const updateWindowWidth = () => {
  windowWidth = window.innerWidth
  if (windowWidth > 1024 && isSidebarOpen.value) {
    isSidebarOpen.value = false
  }
}

onMounted(() => window.addEventListener('resize', updateWindowWidth))
onUnmounted(() => window.removeEventListener('resize', updateWindowWidth))

const isMobile = computed(() => windowWidth <= 1024)

const pageTitles = {
  '/user/': 'Home',
  '/user/quizzes': 'Available Quizzes',
  '/user/records': 'My Records',
  '/user/profile': 'Profile'
}

const currentPageTitle = computed(() => pageTitles[route.path] ?? 'Home')
console.log(route.path)

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
</script>

<style scoped>
.user-wrapper {
  display: flex;
  width: 100%;
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.main-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.user-main {
  display: flex;
  padding: clamp(1.5rem, 4vw, 2.5rem);
  max-width: 100%;
  overflow-x: auto;
}

/* ========================================
   RESPONSIVE BREAKPOINTS
======================================== */
/* Computer: */
@media (max-width: 1024px) {
  .main-wrapper {
    margin-left: 260px;
  }
}


/* TABLET: Narrower sidebar */
@media (max-width: 1024px) {
  .main-wrapper {
    margin-left: 260px;
  }
}

/* MOBILE: Full-width, overlay sidebar */
@media (max-width: 768px) {
  .user-wrapper {
    position: relative;
  }

  .main-wrapper {
    margin-left: 0 !important;
  }


  .user-main {
    padding: clamp(1rem, 5vw, 1.75rem);
  }
}

/* SMALL MOBILE: Extra compact */
@media (max-width: 480px) {
  .user-main {
    padding: 1rem 0.875rem;
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
  backdrop-filter: blur(4px);
}

.sidebar-overlay.active {
  opacity: 1;
  pointer-events: all;
}

/* MOBILE: Prevent body scroll when sidebar open */
:deep(body.sidebar-open) {
  overflow: hidden;
  position: fixed;
  width: 100%;
}

/* Ensure content doesn't jump */
.user-main>* {
  width: 100%;
  min-width: 0;
}

/* PERFECT RESPONSIVE PADDING SYSTEM */
.user-main {
  padding-left: clamp(1rem, 3.5vw, 2.5rem);
  padding-right: clamp(1rem, 3.5vw, 2.5rem);
  padding-top: clamp(1rem, 2vw, 1.75rem);
  padding-bottom: clamp(1rem, 2vw, 1.75rem);
}

/* TABLET OPTIMIZATION */
@media (max-width: 1024px) and (min-width: 769px) {
  .user-main {
    padding-top: 1.75rem;
    padding-bottom: 1.75rem;
  }
}

/* PREVENT HORIZONTAL SCROLL */
.user-wrapper {
  overflow-x: hidden;
}

.user-main {
  overflow-x: visible;
}

/* TOUCH FRIENDLY ON MOBILE */
@media (hover: none) and (pointer: coarse) {
  .menu-trigger {
    min-height: 48px;
    min-width: 48px;
  }
}

/* HIGH DPI / RETINA DISPLAYS */
@media (-webkit-min-device-pixel-ratio: 2),
(min-resolution: 192dpi) {
  .user-navbar {
    border-bottom: 1px solid rgba(102, 126, 234, 0.15);
  }
}

/* PERFECT CONTENT WRAPPING */
.user-main :deep(.card),
.user-main :deep(.table-container) {
  width: 100%;
  overflow-x: auto;
}

/* RESPONSIVE MAX-WIDTH FOR CONTENT */
.user-main {
  max-width: clamp(1000px, 95vw, 1400px);
}
</style>