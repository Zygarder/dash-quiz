<template>
    <div class="admin-container-wrapper">
        <header class="admin-header">
            <h2>Dash Quiz Admin Dashboard</h2>
            <a href="#" @click.prevent="handleLogout" class="logout-btn">Log Out</a>
        </header>

        <div class="admin-container">
            <aside class="admin-sidebar">
                <h3 class="sidebar-title">Admin Menu</h3>
                <nav>
                    <ul>
                        <li><router-link to="/admin/dashboard" active-class="active">Dashboard</router-link></li>
                        <li><router-link to="/admin/quizzes/manage" active-class="active">Manage Quizzes</router-link></li>
                        <li><router-link to="/admin/users" active-class="active">Users Table</router-link></li>
                        <li><router-link to="/admin/records" active-class="active">Student Records</router-link></li>
                    </ul>
                </nav>
            </aside>

            <main class="admin-main">
                <router-view /> 
                <footer class="admin-footer">
                    <p>&copy; 2026 Dash Quiz. All rights reserved. | Built with Vue & Laravel</p>
                </footer>
            </main>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const handleLogout = async () => {
  try {
    await axios.post('/api/logout');
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('userRole');
    router.push('/');
  } catch (e) {
    console.error("Logout failed", e);
  }
}
</script>
