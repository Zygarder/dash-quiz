<template>
    <nav ref="sidebarRef" class="sidebar" :class="{ active: show }">
        <h2>DASH QUIZ</h2>

        <ul>
            <li>
                <router-link to="/home">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </router-link>
            </li>
            <li>
                <router-link to="/quizzes">
                    <i class="fas fa-gamepad"></i>
                    <span>Take Quiz</span>
                </router-link>
            </li>

            <li>
                <router-link to="/records">
                    <i class="fas fa-chart-bar"></i>
                    <span>Check Records</span>
                </router-link>
            </li>

            <li>
                <router-link to="/profile">
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </router-link>
            </li>

        </ul>
    </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue"

const props = defineProps({
    show: Boolean
})

const emit = defineEmits(["close"])

const sidebarRef = ref(null)

const handleClickOutside = (event) => {
    if (!props.show) return

    if (sidebarRef.value && !sidebarRef.value.contains(event.target)) {
        emit("close")
    }
}

onMounted(() => {
    document.addEventListener("mousedown", handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener("mousedown", handleClickOutside)
})
</script>

<style scoped>
.sidebar {
    position: fixed;
    left: -250px;
    top: 0;
    height: 100%;
    width: 250px;
    background-color: #4b3fc2;
    color: #fff;
    padding: 30px 20px;
    transition: left 0.3s ease;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    border-radius: 0 10px 10px 0;
}

.sidebar.active {
    left: 0;
}

ul {
    list-style: none;
    padding: 0;
    margin-top: 20px;
}

li {
    margin-bottom: 10px;
}

.sidebar a {
    display: flex;
    align-items: center;
    gap: 12px;

    color: #fff;
    text-decoration: none;
    background-color: rgba(255, 255, 255, 0.1);
    padding: 12px;
    border-radius: 8px;

    transition: 0.3s;
}

.sidebar a:hover {
    background-color: white;
    color: #4b3fc2;
}

.sidebar i {
    font-size: 16px;
    width: 20px;
    text-align: center;
}
</style>