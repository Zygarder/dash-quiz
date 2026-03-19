<template>
    <aside class="admin-sidebar">
        <div class="logo-section">
            <div class="logo-hexagon">S</div>
            <h2 class="logo-text">Dash Quiz</h2>
        </div>

        <h3 class="sidebar-title">Admin Menu</h3>
        
        <nav class="nav-container">
            <ul>
                <li>
                    <router-link to="/admin/dashboard" active-class="active">
                        <span class="nav-icon">📊</span> Dashboard
                    </router-link>
                </li>
                <li>
                    <router-link to="/admin/quizzes/manage" active-class="active">
                        <span class="nav-icon">👤</span> Manage Quizzes
                    </router-link>
                </li>
                <li>
                    <router-link to="/admin/users" active-class="active">
                        <span class="nav-icon">💬</span> Users Table
                    </router-link>
                </li>
                <li>
                    <router-link to="/admin/records" active-class="active">
                        <span class="nav-icon">⚙️</span> Student Records
                    </router-link>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <button @click="handleLogout" class="logout-btn">Log Out</button>
            
            <div class="illustration-box">
                <img src="https://cdn-icons-png.flaticon.com/512/6840/6840478.png" alt="Admin Illustration" />
            </div>
        </div>
    </aside>
</template>

<script setup>
import { useRouter } from 'vue-router';
const router = useRouter();

const handleLogout = () => {
    localStorage.clear();
    router.push('/');
};
</script>

<style scoped>
.admin-sidebar {
    width: 280px;
    background-color: #ffffff; /* White background like the image */
    height: 100vh;
    padding: 2.5rem 1.5rem;
    display: flex;
    flex-direction: column;
    border-right: 1px solid #f1f5f9;
    flex-shrink: 0;
}

/* Logo Section with Hexagon */
.logo-section {
    text-align: center;
    margin-bottom: 3rem;
}

.logo-hexagon {
    width: 55px;
    height: 55px;
    background-color: #a3e635; /* Lime Green */
    margin: 0 auto 10px;
    clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 900;
    font-size: 1.4rem;
}

.logo-text {
    color: #ff4d4d; /* Red text from image */
    font-size: 1.1rem;
    font-weight: 800;
    margin: 0;
}

.sidebar-title {
    font-size: 0.75rem;
    text-transform: capitalize;
    color: #94a3b8;
    margin-bottom: 1.5rem;
    padding-left: 0.75rem;
}

.nav-container {
    flex: 1;
}

.admin-sidebar ul {
    list-style: none;
    padding: 0;
}

.admin-sidebar li {
    margin-bottom: 0.75rem;
}

/* Link Styles */
.admin-sidebar a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0.85rem 1.25rem;
    color: #64748b;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.nav-icon {
    font-size: 1.1rem;
}

.admin-sidebar a:hover {
    background-color: #f8fafc;
    color: #a3e635;
}

/* Active State: Lime Green like the image */
.admin-sidebar a.active {
    background-color: #a3e635;
    color: white;
    box-shadow: 0 4px 12px rgba(163, 230, 53, 0.3);
}

/* Bottom Footer Section */
.sidebar-footer {
    margin-top: auto;
    text-align: center;
}

.logout-btn {
    width: 100%;
    background-color: #a3e635;
    color: white;
    border: none;
    padding: 0.8rem;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
    margin-bottom: 2rem;
    transition: transform 0.2s;
}

.logout-btn:hover {
    transform: scale(1.02);
    filter: brightness(1.05);
}

.illustration-box {
    background-color: #ecfccb; /* Very light green circle */
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.illustration-box img {
    width: 100%;
    height: auto;
    object-fit: contain;
}
</style>