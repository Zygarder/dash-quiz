<template>
    <SideBarComp :show="showSidebar" @close="closeSidebar" />

    <div>
        <header>
            <div class="top-bar">

                <!-- Sidebar Toggle -->
                <button class="menu-btn" @click="toggleSidebar">
                    <i class="fa fa-bars"></i>
                </button>

                <div class="header-right">

                    <!-- Avatar (when not on profile page) -->
                    <router-link v-if="route.path !== '/profile'" to="/profile" class="avatar-wrapper"
                        title="Go to profile">

                        <img :src="userAvatar || '/storage/images/profiles/default.png'" alt="Profile"
                            class="user-avatar top-avatar" @error="setDefaultImage" />

                        <!-- Online indicator -->
                        <span class="status-dot"></span>

                    </router-link>

                    <!-- Logout button (when on profile page) -->
                    <button v-else class="logout-btn" @click="handleLogout" :disabled="loggingOut">

                        <span v-if="!loggingOut">Logout</span>
                        <span v-else>Logging out...</span>

                    </button>
                </div>
            </div>
        </header>
    </div>
</template>

<script setup>
import { useRoute, useRouter } from "vue-router"
import { useSidebar } from "@/composables/useSidebar"
import SideBarComp from "@/components/UserSide/SideBar.vue"
import { useUser } from "@/composables/useUser"
import { onMounted, ref } from "vue"
import axios from "axios"

const { showSidebar, toggleSidebar, closeSidebar } = useSidebar()
const { clearUser, fetchUser, userAvatar } = useUser()

const route = useRoute()
const router = useRouter()

const loggingOut = ref(false)

/* Image fallback */
const setDefaultImage = (event) => {
    event.target.src = "/storage/images/profiles/default.png"
}

/* Logout */
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

onMounted(async () => {
    await fetchUser()
})
</script>

<style scoped>
/* ===== TOP BAR ===== */
.top-bar {
    background-color: #4b3fc2;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    font-weight: 600;
}

/* ===== MENU BUTTON ===== */
.menu-btn {
    font-size: 1.4rem;
    color: white;
    background: none;
    border: none;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.menu-btn:hover {
    transform: scale(1.1);
}

/* ===== RIGHT HEADER ===== */
.header-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* ===== AVATAR WRAPPER ===== */
.avatar-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

/* ===== AVATAR ===== */
.user-avatar.top-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.user-avatar.top-avatar:hover {
    transform: scale(1.08);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* ===== ONLINE STATUS ===== */
.status-dot {
    position: absolute;
    bottom: 1px;
    right: 2px;
    width: 10px;
    height: 10px;
    background: #28a745;
    border-radius: 50%;
    border: 2px solid white;
}

/* ===== LOGOUT BUTTON ===== */
.logout-btn {
    background-color: #f44336;
    color: white;
    border: none;
    padding: 7px 15px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.25s ease, transform 0.15s ease;
}

.logout-btn:hover {
    background-color: #d32f2f;
    transform: translateY(-1px);
}

.logout-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* ===== STICKY HEADER ===== */
header {
    position: sticky;
    top: 0;
    z-index: 999;
}

/* ===== MOBILE ===== */
@media (max-width:600px) {

    .user-avatar.top-avatar {
        width: 34px;
        height: 34px;
    }

    .menu-btn {
        font-size: 1.2rem;
    }

}
</style>