<template>
  <header class="navbar">
    <div class="navbar-container">
      <div class="left-section">
        <button class="menu-trigger" @click="$emit('toggleSidebar')" aria-label="Toggle Menu" v-show="showMenuButton">
          <i class="fa fa-bars-staggered"></i>
        </button>
        <h2 class="page-title" v-if="showPageTitle">{{ currentPageTitle }}</h2>
      </div>

      <div class="user-section">
        <div class="user-meta" v-show="showUserMeta">
          <span class="user-name">{{ userFullName }}</span>
          <span class="user-status">Online</span>
        </div>
        <div class="avatar-container">
          <img :src="userAvatar" class="avatar-img" />
          <span class="pulse-indicator"></span>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useUser } from '@/composables/useUser'

defineProps({
  currentPageTitle: {
    type: String
  }
})

const { user, fetchUser, userFullName, userAvatar } = useUser()

const emit = defineEmits(['toggleSidebar', 'logout'])
const route = useRoute()

const loggingOut = ref(false)

// Responsive visibility
const showMenuButton = computed(() => window.innerWidth <= 1024)
const showPageTitle = computed(() => window.innerWidth > 768)
const showUserMeta = computed(() => window.innerWidth > 640)
const showAvatar = computed(() => window.innerWidth > 480)

// Handle window resize
window.addEventListener('resize', () => {
  // Trigger reactivity
  showMenuButton.value
  showPageTitle.value
  showUserMeta.value
  showAvatar.value
})

const handleLogout = () => {
  loggingOut.value = true
  emit('logout')
}


onMounted(async () => {
  await fetchUser()
})
</script>

<style scoped>
.navbar {
  position: sticky;
  top: 0;
  z-index: 999;
  border-bottom: 1px solid #e2e8f0;
  height: 70px;
  background: white;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  width: 100%;
}

.navbar-container {
  width: 100%;
  max-width: 1280px;
  padding: 0 1rem;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 100%;
}

.left-section {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-shrink: 0;
}

.page-title {
  font-size: clamp(1rem, 2.5vw, 1.25rem);
  font-weight: 700;
  color: #1e1b4b;
  margin: 0;
  white-space: nowrap;
}

.menu-trigger {
  background: #f1f5f9;
  border: none;
  width: 44px;
  height: 44px;
  border-radius: 12px;
  color: #1e293b;
  cursor: pointer;
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.menu-trigger:hover,
.menu-trigger:focus {
  background: #e2e8f0;
  color: #6366f1;
  transform: scale(1.05);
}

.user-section {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-shrink: 0;
}

.user-meta {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 0.25rem;
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

.avatar-img {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  object-fit: cover;
  display: block;
}

.pulse-indicator {
  position: absolute;
  bottom: 1px;
  right: 1px;
  width: 11px;
  height: 11px;
  background: #10b981;
  border: 2px solid #fff;
  border-radius: 50%;
  box-shadow: 0 0 0 2px white;
  animation: pulse 2s infinite;
}

.minimal-logout {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: #ef4444;
  color: white;
  border: none;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
}

.minimal-logout:hover:not(:disabled) {
  background: #dc2626;
  transform: translateY(-1px);
}

.minimal-logout:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

@keyframes pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
  }

  70% {
    box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
  }

  100% {
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
  }
}

/* Ultra small screens */
@media (max-width: 480px) {
  .navbar-container {
    padding: 0 0.75rem;
    gap: 0.5rem;
  }

  .left-section {
    gap: 0.75rem;
  }

  .menu-trigger {
    width: 40px;
    height: 40px;
  }

  .avatar-img {
    width: 38px;
    height: 38px;
  }
}
</style>