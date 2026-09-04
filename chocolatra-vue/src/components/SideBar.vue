<script setup>
    import { ref } from 'vue';
    import { RouterLink } from 'vue-router';
    
    defineProps({
        isOpen: {
            type: Boolean,
            default: false
        }
    })

    const emit = defineEmits(['close'])
    const menuAberto = ref(null)

    const toggleMenu = (menu) => {
        menuAberto.value = menuAberto.value === menu ? null : menu
    }
</script>

<template>
    <div class="sidebar-overlay" :class="{ active: isOpen }" @click="emit('close')"></div>

    <aside class="sidebar" :class="{ open: isOpen }">

        <div class="sidebar-title">
            <h1>Logo / Sidebar</h1>
        </div>

        <nav class="sidebar-nav">

            <!-- Dashboard -->
            <RouterLink to="/adm/dashboard" class="sidebar-link" @click="emit('close')">
                Dashboard
            </RouterLink>

            <!-- Usuarios -->
            <div class="menu-group">

                <button type="button" class="sidebar-link menu-button" @click="toggleMenu('usuarios')">
                    <span class="menu-label">
                        <span>Usuários</span>
                    </span>

                    <span class="arrow" :class="{ rotated: menuAberto === 'usuarios' }"
                    >
                        ▼
                    </span>
                </button>

                <!-- Submenu -->
                <div class="submenu" v-if="menuAberto === 'usuarios'">
                    <!-- <RouterLink to="/users" class="submenu-link" active-class="active">Usuários</RouterLink> -->

                    <RouterLink :to="{name:'users'}" class="submenu-link" active-class="active" @click="emit('close')">Novo Usuário</RouterLink>
                </div>
            </div>

            <!-- Trufas -->
             <div class="menu-group">

                <button type="button" class="sidebar-link menu-button" @click="toggleMenu('trufas')">
                    <span class="menu-label">
                        <span>Trufas</span>
                    </span>

                    <span class="arrow" :class="{ rotated: menuAberto === 'trufas' }"
                    >
                        ▼
                    </span>
                </button>

                <!-- Submenu -->
                <div class="submenu" v-if="menuAberto === 'trufas'">
                    <RouterLink :to="{name:'trufas'}" class="submenu-link" active-class="active">Trufas</RouterLink>
                </div>
                
             </div>
        </nav>

    </aside>
</template>

<style scoped>
    .sidebar {
        width: 240px;
        min-height: calc(100vh - 64px);
        background-color: #0f172a;
        color: #ffffff;
        flex-shrink: 0;
    }

    .sidebar-title {
        padding: 1.25rem 1.25rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 0.05em;
    }

    .sidebar-nav {
        padding: 0 0.75rem;
    }

    .sidebar-link {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.75rem;

        padding: 0.75rem 1rem;

        border-radius: 0.5rem;
        border: none;

        background: transparent;

        color: #cbd5e1;
        text-decoration: none;

        font-size: 0.9rem;
        font-weight: 500;

        cursor: pointer;

        transition:
            background-color 0.2s ease,
            color 0.2s ease;
    }

    .sidebar-link:hover {
        background-color: #1e293b;
        color: #ffffff;
    }

    .sidebar-link.active {
        background-color: #2563eb;
        color: #ffffff;
    }

    .menu-button {
        justify-content: space-between;
    }

    .menu-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .arrow {
        font-size: 0.65rem;
        transition: transform 0.2s ease;
    }

    .arrow.rotated {
        transform: rotate(180deg);
    }

    .submenu {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;

        margin: 0.25rem 0 0.5rem 1rem;
        padding-left: 0.75rem;

        border-left: 1px solid #334155;
    }

    .submenu-link {
        display: flex;
        align-items: center;

        padding: 0.6rem 0.75rem;

        border-radius: 0.4rem;

        color: #94a3b8;
        text-decoration: none;

        font-size: 0.85rem;

        transition:
            background-color 0.2s ease,
            color 0.2s ease;
    }

    .submenu-link:hover {
        background-color: #1e293b;
        color: #ffffff;
    }

    .submenu-link.active {
        background-color: #1e293b;
        color: #60a5fa;
    }

    /* Resgras de responsividade */
    @media(max-width: 768px) {
        .sidebar-overlay {
            position: fixed;
            top: 64px;
            left: 0;
            width: 100%;
            height: calc(100vh - 64px);
            background-color: rgba(15, 23, 42, 0.5);
            z-index: 40;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .sidebar {
            position: fixed;
            top: 64px;
            left: 0;
            height: calc(100vh - 64px);
            z-index: 50;
            transform: translateX(-100%);
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar.open {
            transform: translateX(0);
        }
    }
</style>