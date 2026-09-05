<script setup>
    import { RouterLink } from 'vue-router';
</script>


<template>
    <header class="page-header">
        <div class="header-content">
            <RouterLink to="/" class="logo">Chocolatra</RouterLink>

            <nav>
                <RouterLink to="/">Início</RouterLink>

                <RouterLink to="/catalogo" class="btn-catalogo">Trufas</RouterLink>

                <RouterLink to="/cart">Carrinho</RouterLink>

                <RouterLink to="/sobre" class="btn-sobre">Sobre</RouterLink>
            </nav>
        </div>
    </header>
</template>

<style scoped>
    .page-header {
        background-color: #f8f5f0;
        border-bottom: 2px solid #d4a574;
        margin-bottom: 30px;
    }

    .header-content {
        width: min(1100px, 100%);
        margin: 0 auto;
        padding: 20px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 30px;
    }

    .logo {
        color: #8b4513;

        font-size: 1.8rem;
        font-weight: 700;

        text-decoration: none;
    }

    nav {
        display: flex;
        align-items: center;

        gap: 25px;
    }

    nav a {
        color: #5d4a36;

        font-weight: 600;

        text-decoration: none;

        transition: color 0.2s ease;
    }

    nav a:hover {
        color: #8b4513;
    }

    nav a.router-link-active {
        color: #8b4513;
    }

    /* Responsivo */

    @media (max-width: 650px) {

        .header-content {
            flex-direction: column;
            gap: 15px;
        }

        nav {
            gap: 18px;
        }
    }

    @media (max-width: 400px) {

        .logo {
            font-size: 1.6rem;
        }

        nav {
            gap: 12px;
            font-size: 0.9rem;
        }
    }
</style>