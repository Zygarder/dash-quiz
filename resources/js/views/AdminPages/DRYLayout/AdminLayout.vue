<template>
  <div class="admin-container-wrapper">
    
    <!-- SIDEBAR -->
    <aside class="admin-sidebar" :class="{ open: isSidebarOpen }">
      
      <div class="logo-section">
        <div class="logo-hexagon">DQ</div>
        <h2 class="logo-text">Dash Quiz</h2>
      </div>

      <nav class="nav-container">
        <ul>
          <li>
            <router-link to="/admin/dashboard" active-class="active">
              <i class="fas fa-chart-line"></i>
              <span class="link-text">Dashboard</span>
            </router-link>
          </li>

          <li>
            <router-link to="/admin/quizzes/manage" active-class="active">
              <i class="fas fa-file-alt"></i>
              <span class="link-text">Quizzes</span>
            </router-link>
          </li>

          <li>
            <router-link to="/admin/users" active-class="active">
              <i class="fas fa-user"></i>
              <span class="link-text">Users</span>
            </router-link>
          </li>

          <li>
            <router-link to="/admin/records" active-class="active">
              <i class="fas fa-database"></i>
              <span class="link-text">Records</span>
            </router-link>
          </li>
        </ul>
      </nav>

      <div class="sidebar-footer">
        <button @click="handleLogout" class="logout-btn">
          <i class="fas fa-sign-out-alt"></i>
          <span class="link-text">Logout</span>
        </button>
      </div>
    </aside>

    <!-- MAIN -->
    <div class="main-content-wrapper">
      
      <header class="admin-header">
        <button class="menu-toggle" @click="isSidebarOpen = !isSidebarOpen">
          <i class="fas fa-bars"></i>
        </button>

        <h2 class="header-title">Admin Dashboard</h2>

        <div class="admin-user-info">
          <i class="fas fa-user-circle"></i>
          <span>Admin</span>
          <div class="status-dot"></div>
        </div>
      </header>

      <main class="admin-main">
        <div class="content-view">
          <router-view />
        </div>
      </main>

      <footer class="admin-footer">
        <p>&copy; 2026 Dash Quiz. All rights reserved.</p>
      </footer>

    </div>
  </div>
</template>

<script setup>
import axios from 'axios';
import { useUser } from '@/composables/useUser'
import { ref } from 'vue';

const { clearUser } = useUser();
const isSidebarOpen = ref(false) // for side bar
const loggingOut = ref(false)

const handleLogout = async () => {

    const confirmLogout = confirm("Are you sure you want to logout?")

    if (!confirmLogout) return

    try {

        loggingOut.value = true

        await axios.post("/api/logout")

        localStorage.removeItem("isLoggedIn")
        localStorage.removeItem("userRole")

        clearUser()

        router.push("/")

    } catch (err) {

        console.error("Logout failed", err)

    } finally {

        loggingOut.value = false

    }
}

</script>

<style scoped>
/* 1. Base Layout */
.admin-container-wrapper {
    display: flex;
    min-height: 100vh;
    background-color: #fcfaff;
    /* Slightly lighter/cleaner purple bg */
}

/* 2. Minimalist Sidebar */
.admin-sidebar {
    width: 240px;
    /* Slimmer sidebar */
    background-color: #1e1b4b;
    /* Deeper, more neutral navy-purple */
    color: white;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    display: flex;
    flex-direction: column;
    padding: 1.5rem;
    border-right: 1px solid rgba(255, 255, 255, 0.05);
}

.logo-section {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 2.5rem;
    padding-left: 0.5rem;
}

.logo-hexagon {
    width: 35px;
    /* Smaller, more subtle */
    height: 35px;
    background: #8b5cf6;
    clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 0.9rem;
}

.logo-text {
    font-size: 1.1rem;
    font-weight: 700;
    letter-spacing: -0.5px;
}

/* 3. Navigation Links */
.admin-sidebar ul {
    list-style: none;
    padding: 0;
}

.admin-sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.7rem 1rem;
    color: #94a3b8;
    /* Muted slate color */
    text-decoration: none;
    border-radius: 8px;
    margin-bottom: 0.2rem;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.admin-sidebar a:hover {
    color: white;
    background: rgba(255, 255, 255, 0.05);
}

.admin-sidebar a.active {
    background-color: #4c1d95;
    /* Focus color */
    color: white;
    font-weight: 600;
}

/* 4. Main Area */
.main-content-wrapper {
    flex: 1;
    margin-left: 240px;
    display: flex;
    flex-direction: column;
}

.admin-header {
    height: 64px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 2rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    /* Extremely soft shadow */
}

.header-title {
    color: #1e1b4b;
    font-size: 1.1rem;
    font-weight: 600;
}

.admin-user-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    color: #475569;
}

.status-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
}

/* 5. Main Content Padding */
.admin-main {
    display: flex;
    width: 100%;
    margin: 0;
    padding: 0;
}

.content-view {
    max-width: 1200px;
    /* Keeps tables from stretching too wide on 4k monitors */
    margin: 0 auto;
    width: 100%;
}

/* 6. Footer & Buttons */
.admin-footer {
    padding: 1.5rem;
    text-align: center;
    color: #94a3b8;
    font-size: 0.8rem;
}

.logout-btn {
    width: 100%;
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #94a3b8;
    padding: 0.6rem;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.9rem;
    transition: 0.3s;
}

.logout-btn:hover {
    background: #ef4444;
    /* Red on hover for logout */
    color: white;
    border-color: #ef4444;
}

/* ===== TOGGLE BUTTON ===== */
.menu-toggle {
    display: none;
    font-size: 1.3rem;
    background: none;
    border: none;
    cursor: pointer;
    color: #1e1b4b;
}

/* ===== MEDIUM SCREEN (Tablet) ===== */
@media (max-width: 1024px) {
    .admin-sidebar {
        width: 80px;
        padding: 1rem 0.5rem;
    }

    .logo-text {
        display: none;
    }

    .admin-sidebar a {
        justify-content: center;
        font-size: 1.2rem;
    }

    .admin-sidebar a span {
        display: none;
        /* if you wrap text later */
    }

    .main-content-wrapper {
        margin-left: 80px;
    }
}

/* ===== SMALL SCREEN (Mobile) ===== */
@media (max-width: 768px) {
    .menu-toggle {
        display: block;
    }

    .admin-sidebar {
        position: fixed;
        left: -100%;
        top: 0;
        width: 220px;
        height: 100vh;
        z-index: 1000;
        transition: 0.3s ease;
    }

    .admin-sidebar.open {
        left: 0;
    }

    .main-content-wrapper {
        margin-left: 0;
    }

    /* Optional dark overlay */
    .admin-sidebar.open::after {
        content: "";
        position: fixed;
        top: 0;
        left: 220px;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.3);
    }
}

.link-text {
    transition: 0.3s;
}

@media (max-width: 1024px) {
    .link-text {
        display: none;
    }
}
</style>