<template>
    <SideBarComp :show="showSidebar" @close="closeSidebar" />
    <div>
        <header>
            <div class="top-bar">
                <button class="menu-btn" @click="toggleSidebar">&#9776;</button>

                <div class="header-right">
                    <router-link v-if="route.path !== '/profile'" to="/profile">
                        <img :src="avatar || '/storage/images/profiles/default.png'" alt="DP" class="user-avatar top-avatar" />
                    </router-link>

                    <button v-else class="logout-btn" @click="handleLogout">
                        Logout
                    </button>
                </div>
            </div>
        </header>
    </div>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router' // Import route tools
import { useSidebar } from '../composables/useSidebar';
import SideBarComp from "@/components/SideBar.vue"
import { useUser } from "@/composables/useUser"
import { onMounted } from 'vue';

const { showSidebar, toggleSidebar, closeSidebar } = useSidebar()
const { user, fetchUser, avatar } = useUser()

const route = useRoute()   // Access current path
const router = useRouter() // For redirecting after logout

// profile data is fetched via composable

const handleLogout = async () => {
  try {

    // correct path for logout API
    await axios.post("/api/logout")

    // clear auth flag
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('userRole');

    // clear global user state
    clearUser();

    router.push("/")

  } catch (err) {
    console.error("Logout failed", err)
  }
}

onMounted(async () => {
    await fetchUser()
})
</script>

<style scoped>
/* === TOP BAR === */
.top-bar {
  background-color: #4b3fc2; /* match login/profile theme */
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 20px;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
  font-weight: 600;
}

/* Menu button */
.menu-btn {
  font-size: 1.3rem;
  color: white;
  background: none;
  border: none;
  cursor: pointer;
}

/* Right side container */
.header-right {
  display: flex;
  align-items: center;
  gap: 12px;
}

/* Logout button */
.logout-btn {
  background-color: #f44336; /* Red */
  color: white;
  border: none;
  padding: 6px 14px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.3s;
}

.logout-btn:hover {
  background-color: #d32f2f;
}

/* Avatar styling */
.user-avatar.top-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid white;
}

/* Optional: make top bar sticky */
header {
  position: sticky;
  top: 0;
  z-index: 999;
}
</style>