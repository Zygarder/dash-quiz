<template>
  <aside class="user-sidebar" :class="{ 'open': isSidebarOpen }">
    <!-- Logo Section -->
    <div class="logo-section">
      <div class="logo-mark">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
          <line x1="3" y1="9" x2="21" y2="9" />
          <line x1="9" y1="21" x2="9" y2="9" />
        </svg>
      </div>
      <span class="logo-text">DASH<span>QUIZ</span></span>
    </div>

    <!-- Menu Label -->
    <div class="nav-label">MAIN MENU</div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
      <router-link to="/user" exact-active-class="active" @click="$emit('closeSidebar')" class="nav-link">
        <span class="nav-icon"><i class="fas fa-home"></i></span>
        <span class="nav-text">Home</span>
      </router-link>

      <router-link to="/user/quizzes" exact-active-class="active" @click="$emit('closeSidebar')" class="nav-link">
        <span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>
        <span class="nav-text">Quizzes</span>
      </router-link>

      <router-link to="/user/records" exact-active-class="active" @click="$emit('closeSidebar')" class="nav-link">
        <span class="nav-icon"><i class="fas fa-chart-simple"></i></span>
        <span class="nav-text">Records</span>
      </router-link>

      <router-link to="/user/profile" exact-active-class="active" @click="$emit('closeSidebar')" class="nav-link">
        <span class="nav-icon"><i class="fas fa-user"></i></span>
        <span class="nav-text">Profile</span>
      </router-link>
    </nav>

    <!-- Spacer -->
    <div class="sidebar-spacer"></div>

    <!-- Footer -->
    <div class="sidebar-footer">
      <div class="user-badge">
        <div class="user-avatar">U</div>
        <div class="user-info">
          <span class="user-name">User</span>
          <span class="user-role">Student</span>
        </div>
      </div>
      <button class="logout-btn" @click="$emit('logout')" title="Logout">
        <i class="fas fa-power-off"></i>
      </button>
    </div>
  </aside>
</template>

<script setup>
defineProps({
  isSidebarOpen: Boolean
})
defineEmits(['closeSidebar', 'logout'])
</script>

<style scoped>
.user-sidebar {
  --bg-primary: #1e1b4b;
  --bg-hover: rgba(99, 102, 241, 0.1);
  --accent: #818cf8;
  --accent-active: #6366f1;
  --logo-accent: #8b5cf6;
  --text-primary: #f8fafc;
  --text-secondary: #94a3b8;
  --text-muted: #4b5563;
  --border: rgba(255, 255, 255, 0.05);
  --user-avatar: linear-gradient(135deg, #6366f1, #4f46e5);

  width: 240px;
  background: #1e1b4b;
  height: auto;
  position: fixed;
  left: 0;
  top: 0;
  display: flex;
  flex-direction: column;
  padding: 1.5rem;
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 1001;
  border-right: 1px solid var(--border);
  overflow-y: auto;
  font-family: 'Inter', -apple-system, sans-serif;
}

/* === RESPONSIVE === */
@media (min-width: 1025px) {
  .user-sidebar {
    transform: none !important;
    position: sticky;
  }
}

@media (max-width: 1024px) and (min-width: 769px) {
  .user-sidebar {
    width: 220px;
    height: 100vh;
    padding: 1.25rem;
  }
}

@media (max-width: 768px) {
  .user-sidebar {
    transform: translateX(-100%);
    width: 280px;
    height: 100vh;
    padding: 1.25rem;
  }

  .user-sidebar.open {
    transform: translateX(0);
    box-shadow: 12px 0 40px rgba(0, 0, 0, 0.4);
  }
}

@media (max-width: 480px) {
  .user-sidebar.open {
    width: 85vw;
    max-width: 280px;
  }
}

/* === LOGO === */
.logo-section {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 2.5rem;
  padding-right: 0.5rem;
}

.logo-mark {
  width: 32px;
  height: 32px;
  background: #8b5cf6;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.logo-mark svg {
  stroke: white;
}

.logo-text {
  font-weight: 800;
  font-size: 1.1rem;
  color: #f8fafc;
  letter-spacing: 0.5px;
}

.logo-text span {
  color: var(--accent);
}

/* === NAV LABEL === */
.nav-label {
  font-size: 0.7rem;
  font-weight: 700;
  color: #4b5563;
  letter-spacing: 1.5px;
  margin-bottom: 1rem;
  padding-left: 0.25rem;
}

/* === NAVIGATION === */
.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 12px 12px 16px;
  border-radius: 10px;
  color: var(--text-secondary);
  text-decoration: none;
  font-weight: 500;
  font-size: 0.9375rem;
  transition: all 0.2s ease;
}

.nav-link:hover {
  background: var(--bg-hover);
  color: #f8fafc;
  border-left-color: var(--accent);
  padding-left: 19px;
  margin-left: -3px;
}

.nav-link.active {
  background: var(--bg-hover);
  color: #f8fafc;
  border-left-color: #6366f1;
}

.nav-link.active .nav-icon {
  color: var(--accent);
}

.nav-icon {
  width: 18px;
  font-size: 0.9375rem;
  opacity: 0.8;
}

.nav-link:hover .nav-icon,
.nav-link.active .nav-icon {
  opacity: 1;
}

.nav-text {
  opacity: 0.9;
}

/* === SPACER === */
.sidebar-spacer {
  flex: 1;
  min-height: 20px;
}

/* === FOOTER === */
.sidebar-footer {
  padding-top: 1.5rem;
  border-top: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-bottom: 1rem;
  gap: 12px;
}

.user-badge {
  display: flex;
  align-items: center;
  gap: 10px;
  flex: 1;
  min-width: 0;
}

.user-avatar {
  width: 36px;
  height: 36px;
  background: linear-gradient(135deg, #6366f1, #4f46e5);
  border-radius: 8px;
  display: grid;
  place-items: center;
  font-weight: 700;
  font-size: 0.875rem;
  color: white;
  flex-shrink: 0;
}

.user-info {
  min-width: 0;
  flex: 1;
}

.user-name {
  display: block;
  font-size: 0.875rem;
  font-weight: 600;
  color: #f8fafc;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-role {
  font-size: 0.6875rem;
  color: #4b5563;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.logout-btn {
  background: none;
  border: 1px solid var(--border);
  border-radius: 8px;
  color: #ef4444;
  cursor: pointer;
  font-size: 1rem;
  padding: 0.625rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  flex-shrink: 0;
  width: 40px;
  height: 40px;
}

.logout-btn:hover {
  background: rgba(239, 68, 68, 0.12);
  border-color: #ef4444;
  transform: scale(1.05);
}

/* === SCROLLBAR === */
.user-sidebar::-webkit-scrollbar {
  width: 4px;
}

.user-sidebar::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
}

.user-sidebar::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 2px;
}

.user-sidebar::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.3);
}
</style>