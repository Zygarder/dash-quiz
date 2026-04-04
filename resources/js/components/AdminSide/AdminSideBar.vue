<template>
  <aside class="admin-sidebar" :class="{ 'open': isSidebarOpen }">
    <div class="logo-section">
      <div class="logo-mark">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
          <line x1="3" y1="9" x2="21" y2="9" />
          <line x1="9" y1="21" x2="9" y2="9" />
        </svg>
      </div>
      <span class="logo-text">DASH<span>QUIZ</span></span>
    </div>

    <div class="nav-label">MAIN MENU</div>

    <nav class="sidebar-nav">
      <router-link to="/admin" exact-active-class="active" @click="$emit('closeSidebar')">
        <span class="nav-icon"><i class="fas fa-th-large"></i></span>
        <span class="nav-text">Dashboard</span>
      </router-link>

      <router-link to="/admin/manage-quizzes" active-class="active" @click="$emit('closeSidebar')">
        <span class="nav-icon"><i class="fas fa-book-open"></i></span>
        <span class="nav-text">Quizzes</span>
      </router-link>

      <router-link to="/admin/users" active-class="active" @click="$emit('closeSidebar')">
        <span class="nav-icon"><i class="fas fa-user-shield"></i></span>
        <span class="nav-text">Users</span>
      </router-link>

      <router-link to="/admin/records" active-class="active" @click="$emit('closeSidebar')">
        <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
        <span class="nav-text">Records</span>
      </router-link>
    </nav>

    <div class="sidebar-spacer"></div>

    <div class="sidebar-footer">
      <div class="admin-badge">
        <div class="admin-avatar">A</div>
        <div class="admin-info">
          <span class="admin-name">Admin</span>
          <span class="admin-role">Full Access</span>
        </div>
      </div>
      <button class="logout-icon-btn" @click="$emit('logout')" title="Logout">
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
.admin-sidebar {
  width: 280px;
  background: #1e1b4b;
  height: auto;
  position: fixed;
  left: 0;
  top: 0;
  display: fixed;
  flex-direction: column;
  padding: 1.5rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 1001;
  border-right: 1px solid rgba(255, 255, 255, 0.05);
  transform: translateX(0);
}

/* Desktop: Always visible */
@media (min-width: 1025px) {
  .admin-sidebar {
    position: sticky;
    transform: translateX(0) !important;
  }

  .admin-sidebar.open {
    transform: translateX(0) !important;
  }
}

/* Tablet: Slightly narrower */
@media (max-width: 1024px) {
  .admin-sidebar {
    width: 220px;
    height: 100vh;
    padding: 1.25rem;
  }
}

/* Mobile: Hidden by default, overlay when open */
@media (max-width: 768px) {
  .admin-sidebar {
    width: 280px;
    transform: translateX(-100%);
    padding: 1.25rem;
  }

  .admin-sidebar.open {
    transform: translateX(0);
    box-shadow: 12px 0 40px rgba(0, 0, 0, 0.4);
  }
}

/* Very small mobile */
@media (max-width: 480px) {
  .admin-sidebar {
    width: 260px;
    padding: 1rem;
  }

  .admin-sidebar.open {
    width: 85vw;
    max-width: 280px;
  }
}

/* Logo responsive */
@media (max-width: 480px) {
  .logo-text {
    font-size: 1rem;
  }
}

/* Nav items responsive */
@media (max-width: 480px) {
  .sidebar-nav a {
    padding: 10px 12px;
    gap: 10px;
  }

  .nav-text {
    font-size: 0.9rem;
  }
}

/* Footer responsive */
@media (max-width: 480px) {
  .admin-info {
    display: none;
  }

  .admin-badge {
    gap: 8px;
  }

  .admin-avatar {
    width: 32px;
    height: 32px;
    font-size: 0.85rem;
  }
}

/* Rest of your existing styles remain the same */
.logo-section {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 2.5rem;
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

.logo-text {
  font-weight: 800;
  font-size: 1.1rem;
  color: white;
  letter-spacing: 1px;
}

.logo-text span {
  color: #818cf8;
}

.nav-label {
  font-size: 0.7rem;
  font-weight: 700;
  color: #4b5563;
  letter-spacing: 1.5px;
  margin-bottom: 1rem;
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.sidebar-nav a {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 15px;
  border-radius: 10px;
  color: #94a3b8;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.2s;
}

.sidebar-nav a:hover,
.sidebar-nav a.active {
  background: rgba(99, 102, 241, 0.1);
  color: white;
}

.sidebar-spacer {
  flex: 1;
}

.sidebar-nav a.active .nav-icon {
  color: #818cf8;
}

.sidebar-footer {
  padding-top: 1.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.05);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.admin-badge {
  display: flex;
  align-items: center;
  gap: 10px;
}

.admin-avatar {
  width: 35px;
  height: 35px;
  background: linear-gradient(135deg, #6366f1, #4f46e5);
  border-radius: 8px;
  display: grid;
  place-items: center;
  font-weight: bold;
  color: white;
}

.admin-name {
  display: block;
  font-size: 0.85rem;
  font-weight: 600;
  color: white;
}

.admin-role {
  font-size: 0.7rem;
  color: #64748b;
}

.logout-icon-btn {
  background: none;
  border: none;
  color: #ef4444;
  cursor: pointer;
  font-size: 1.1rem;
  transition: transform 0.2s;
  padding: 4px;
  border-radius: 6px;
}

.logout-icon-btn:hover {
  background: rgba(239, 68, 68, 0.1);
  transform: scale(1.1);
}
</style>