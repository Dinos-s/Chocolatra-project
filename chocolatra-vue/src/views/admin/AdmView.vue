<script setup>
    import { RouterLink, RouterView, useRouter } from 'vue-router';
    import SideBar from '../../components/SideBar.vue';
    import { ref } from 'vue';
    import api from '../../services/api.js';

    const router = useRouter()
    const isSidebarOpen = ref(false)

    const toggleSidebar = () => {
        isSidebarOpen.value = !isSidebarOpen.value
    }

    const logout = async () => {
        await api.post('/logout')
        localStorage.removeItem('token')
        router.push('/adm')
    }
</script>

<template>
    <div class="dashboard-layout">
        <header class="header">
            <div class="header-container">
                <div class="header-left">
                    <button class="btn-toggle" @click="toggleSidebar" aria-label="Toggle Navigation">☰</button>
                    <span class="logo">Dashboard</span>
                </div>

                <nav class="nav">
                    <!-- <RouterLink to="/" class="nav-link">Home</RouterLink>
                    <RouterLink to="/about" class="nav-link">About</RouterLink> -->
                    
                    <button @click="logout" class="btn-logout">Sair</button>
                </nav>
            </div>
        </header>

        <div class="dashboard-body">
            <SideBar :is-open="isSidebarOpen" @close="isSidebarOpen = false"/>

            <main class="main-content">
                <div class="content-container">
                    <RouterView />
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
    .dashboard-layout {
        min-height: 100vh;
        background-color: #f8fafc;
        color: #1e293b;
        display: flex;
        flex-direction: column;
    }

    .header {
        height: 64px;
        background-color: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        padding: 0 1.5rem;
    }

    .header-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .btn-toggle {
        display: none;
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 1.5rem;
        color: #0f172a;
        padding: 0.25rem;
    }

    .logo {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }

    .nav {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .nav-link {
        text-decoration: none;
        color: #64748b;
        font-weight: 500;
        font-size: 0.95rem;
        transition: color 0.2s ease;
    }

    .nav-link:hover {
        color: #2563eb;
    }

    .btn-logout {
        background-color: #ef4444;
        color: #ffffff;
        border: none;
        padding: 0.4rem 0.85rem;
        border-radius: 0.375rem;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .btn-logout:hover {
        background-color: #dc2626;
    }

    .dashboard-body {
        flex: 1;
        display: flex;
    }

    .main-content {
        flex: 1;
        width: 0;
        padding: 2rem 1.5rem;
    }

    .content-container {
        max-width: 1200px;
        margin: 0 auto;
    }


    @media(max-width: 768px) {
        .btn-toggle {
            display: block;
        }
    }
</style>