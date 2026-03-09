<template>
    <nav ref="sidebarRef" class="sidebar" :class="{ active: show }">
        <h2>DASH QUIZ</h2>

        <ul>
            <li><router-link to="/dashboard">Dashboard</router-link></li>
            <li><router-link to="/quizzes">Take a Quiz</router-link></li>
            <li><router-link to="/records">Check Records</router-link></li>
            <li><router-link to="/profile">My Profile</router-link></li>
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
}

.sidebar.active {
    left: 0;
}
</style>