<template>
  <aside class="admin-sidebar" :class="{ open: isSidebarOpen }">
    <div class="logo-section">
      <div class="logo-mark">
        <svg width="20" height="20" viewBox="0 0 20 20">
          <polygon points="10,1 19,6 19,14 10,19 1,14 1,6" fill="white" opacity="0.9" />
          <polygon points="10,5 15,8 15,12 10,15 5,12 5,8" fill="#1e1b4b" />
        </svg>
      </div>
      <span class="logo-text">DashQuiz</span>
    </div>

    <div class="nav-label">MAIN MENU</div>

    <nav class="sidebar-nav">
      <router-link to="/admin/dashboard" active-class="active" @click="$emit('closeSidebar')">
        <span class="nav-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7" />
            <rect x="14" y="3" width="7" height="7" />
            <rect x="14" y="14" width="7" height="7" />
            <rect x="3" y="14" width="7" height="7" />
          </svg>
        </span>
        <span class="nav-text">Dashboard</span>
      </router-link>

      <router-link to="/admin/quizzes/manage" active-class="active" @click="$emit('closeSidebar')">
        <span class="nav-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
            <polyline points="14 2 14 8 20 8" />
            <line x1="16" y1="13" x2="8" y2="13" />
            <line x1="16" y1="17" x2="8" y2="17" />
          </svg>
        </span>
        <span class="nav-text">Quizzes</span>
      </router-link>

      <router-link to="/admin/users" active-class="active" @click="$emit('closeSidebar')">
        <span class="nav-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
          </svg>
        </span>
        <span class="nav-text">Users</span>
      </router-link>

      <router-link to="/admin/records" active-class="active" @click="$emit('closeSidebar')">
        <span class="nav-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <ellipse cx="12" cy="5" rx="9" ry="3" />
            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3" />
            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5" />
          </svg>
        </span>
        <span class="nav-text">Records</span>
      </router-link>
    </nav>

    <div class="sidebar-spacer" />

    <div class="sidebar-footer">
      <div class="admin-badge">
        <div class="admin-avatar">A</div>
        <div class="admin-info">
          <span class="admin-name">Admin</span>
          <span class="admin-role">Administrator</span>
        </div>
      </div>
      <button class="minimal-logout" @click="$emit('logout')">
        <span class="btn-text">Logout</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
defineProps({
  isSidebarOpen: Boolean
})
</script>

<style scoped>
/* Keep the sidebar styles from your original code */
</style>