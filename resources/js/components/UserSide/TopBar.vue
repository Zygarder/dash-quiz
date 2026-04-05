<template>
  <header class="navbar">
    <div class="navbar-container">

      <div class="left-section">
        <button class="menu-trigger" @click="$emit('toggleSidebar')" v-if="isMobile">
          <i class="fas fa-bars"></i>
        </button>

        <h2 class="page-title">{{ currentPageTitle }}</h2>
      </div>

      <div class="user-section">
        <div class="user-meta">
          <span class="user-name">{{ userFullName }}</span>
          <span class="user-status">Online</span>
        </div>
        <div v-if="currentPageTitle !== 'Profile'">
          <router-link to="/user/profile">
            <div class="avatar-container">
              <img :src="userAvatar" alt="no img" width="30" height="30" class="avatar-img">
              <span class="pulse-indicator"></span>
            </div>
          </router-link>
        </div>

      </div>

    </div>
  </header>

</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useUser } from '@/composables/useUser.js'

defineProps({
  currentPageTitle: String,
})

const { userAvatar, userFullName, fetchUser } = useUser();

const screenWidth = ref(window.innerWidth)

const updateWidth = () => {
  screenWidth.value = window.innerWidth
}

onMounted(() => window.addEventListener('resize', updateWidth))
onUnmounted(() => window.removeEventListener('resize', updateWidth))

const isMobile = computed(() => screenWidth.value <= 768)


onMounted(() => {
  fetchUser()
})
</script>

<style scoped>
.navbar {
  height: 70px;
  background: white;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  align-items: center;
}

.navbar-container {
  width: 100%;
  max-width: 1280px;
  margin: auto;
  padding: 0 1rem;
  display: flex;
  justify-content: space-between;
}

.left-section {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.menu-trigger {
  width: 42px;
  height: 42px;
  border: none;
  background: #f1f5f9;
  border-radius: 10px;
  cursor: pointer;
}

.page-title {
  font-weight: 700;
  color: #1e1b4b;
}

.user-name {
  font-weight: 600;
}
</style>

<style scoped>
.user-navbar {
  position: sticky;
  top: 0;
  z-index: 1000;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(102, 126, 234, 0.1);
  height: clamp(60px, 7vh, 70px);
}

.navbar-container {
  width: 100%;
  max-width: 1400px;
  padding: 0 clamp(1rem, 2.5vw, 1.5rem);
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 100%;
}

.left-section {
  display: flex;
  align-items: center;
  gap: clamp(12px, 2vw, 20px);
}

.page-title {
  font-size: clamp(1.1rem, 2.5vw, 1.4rem);
  font-weight: 700;
  background: linear-gradient(135deg, #667eea, #764ba2);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin: 0;
}

.menu-trigger {
  background: rgba(102, 126, 234, 0.1);
  border: 1px solid rgba(102, 126, 234, 0.2);
  width: clamp(42px, 6vw, 46px);
  height: clamp(42px, 6vw, 46px);
  border-radius: 12px;
  color: #667eea;
  cursor: pointer;
  font-size: 1.1rem;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.menu-trigger:hover {
  background: rgba(102, 126, 234, 0.2);
  transform: scale(1.05);
}

.right-section {
  display: flex;
  align-items: center;
  gap: 16px;
}

.notifications {
  position: relative;
}

.notif-btn {
  position: relative;
  background: rgba(102, 126, 234, 0.1);
  border: 1px solid rgba(102, 126, 234, 0.2);
  width: 42px;
  height: 42px;
  border-radius: 12px;
  color: #667eea;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  transition: all 0.2s;
}

.notif-btn:hover {
  background: rgba(102, 126, 234, 0.2);
  transform: scale(1.05);
}

.avatar-img {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  object-fit: cover;
  display: block;
}

.user-name {
  font-size: 0.95rem;
  font-weight: 600;
  color: #1e293b;
  white-space: nowrap;
}

.user-status {
  font-size: 0.75rem;
  color: #10b981;
  font-weight: 500;
}

.avatar-container {
  position: relative;
  padding: 3px;
  border: 2px solid #e2e8f0;
  border-radius: 50%;
  transition: all 0.3s ease;
}

.avatar-container:hover {
  border-color: #6366f1;
  transform: scale(1.05);
}

.notif-badge {
  position: absolute;
  top: 4px;
  right: 4px;
  background: #ef4444;
  color: white;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  font-size: 0.7rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
}

.user-section {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-meta {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 2px;
}

.user-name {
  font-size: clamp(0.85rem, 2vw, 0.95rem);
  font-weight: 600;
  color: #1e293b;
  white-space: nowrap;
  text-overflow: ellipsis;
  max-width: 120px;
}

.user-role {
  font-size: 0.7rem;
  color: #667eea;
  font-weight: 500;
}

.avatar-container {
  position: relative;
  padding: 3px;
  border: 2px solid rgba(102, 126, 234, 0.1);
  border-radius: 50%;
  transition: all 0.3s ease;
  cursor: pointer;
}

.avatar-container:hover {
  border-color: #667eea;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.avatar-img {
  width: clamp(38px, 5.5vw, 44px);
  height: clamp(38px, 5.5vw, 44px);
  border-radius: 50%;
  object-fit: cover;
}

.status-indicator {
  position: absolute;
  bottom: 2px;
  right: 2px;
  width: 10px;
  height: 10px;
  background: #10b981;
  border: 2px solid white;
  border-radius: 50%;
}

@media (max-width: 480px) {
  .navbar-container {
    padding: 0 1rem;
  }
}
</style>