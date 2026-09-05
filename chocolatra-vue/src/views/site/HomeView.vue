<script setup>
import { RouterLink } from 'vue-router'
import SiteHeader from '../../components/SiteHeader.vue';
import SiteFooter from '../../components/SiteFooter.vue';
</script>

<template>
    <main class="home">

        <SiteHeader />

        <section class="hero">
            <div class="container hero-content">
                <div>
                    <h2>Trufas feitas para adoçar o seu dia 🍫</h2>
                    <p>
                        Escolha suas trufas favoritas e faça seu pedido
                        de forma rápida e fácil.
                    </p>

                    <RouterLink to="/catalogo" class="btn-primary">
                        Ver trufas
                    </RouterLink>
                </div>
            </div>
        </section>

        <section class="about">
            <div class="container">
                <h2>Por que escolher a Chocolatra?</h2>
                <div class="features">
                    <div class="feature">
                        <h3>🍫 Trufas deliciosas</h3>
                        <p>
                            Diversos sabores para você escolher.
                        </p>
                    </div>

                    <div class="feature">
                        <h3>🛒 Compra fácil</h3>
                        <p>
                            Escolha seus produtos e monte seu pedido.
                        </p>
                    </div>

                    <div class="feature">
                        <h3>❤️ Feito com carinho</h3>
                        <p>Produtos preparados com cuidado.</p>
                    </div>
                </div>
            </div>
        </section>

        <SiteFooter />

    </main>
</template>

<style scoped>
.home {
    min-height: 100vh;
    background-color: #f8f5f0;
    color: #4a3c31;
    line-height: 1.6;
}

/* CONTAINER */
.container {
    width: min(800px, 100%);
    margin: 0 auto;
    padding: 0 20px;
}

/* HERO */
.hero {
    margin-bottom: 30px;
}

.hero-content {
    background-color: white;
    border-radius: 12px;
    padding: 45px 35px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.hero h2 {
    margin: 0 0 15px;
    color: #8b4513;
    font-size: 2rem;
    line-height: 1.3;
}

.hero p {
    margin: 0 0 30px;
    color: #8a7866;
    font-size: 1rem;
    max-width: 550px;
}

/* BOTÃO PRINCIPAL */
.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    background-color: #8b4513;
    color: white;

    padding: 12px 24px;

    border: none;
    border-radius: 8px;

    font-size: 1rem;
    font-weight: 600;

    text-decoration: none;

    cursor: pointer;

    transition:
        background-color 0.3s ease,
        transform 0.2s ease;
}

.btn-primary:hover {
    background-color: #6b3410;
    transform: translateY(-1px);
}

/* SOBRE */
.about {
    margin-bottom: 30px;
}

.about .container {
    background-color: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.about h2 {
    margin: 0 0 25px;

    color: #8b4513;

    font-size: 1.8rem;

    padding-bottom: 10px;

    border-bottom: 1px solid #e8d9c5;
}

/* CARDS */
.features {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.feature {
    background-color: #f8f5f0;

    padding: 20px;

    border-radius: 10px;

    border-left: 4px solid #8b4513;

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease;
}

.feature:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}

.feature h3 {
    margin: 0 0 8px;
    color: #5d4a36;
    font-size: 1rem;
}

.feature p {
    margin: 0;
    color: #8a7866;
    font-size: 0.9rem;
}

/* RESPONSIVO */
@media (max-width: 768px) {

    .container {
        width: 100%;
        padding: 0 15px;
    }

    .hero h2 {
        font-size: 1.7rem;
    }

    .about .container {
        padding: 25px 20px;
    }

    .about h2 {
        font-size: 1.5rem;
    }

    .features {
        grid-template-columns: 1fr;
    }

    .feature {
        padding: 18px;
    }
}

/* CELULAR PEQUENO */
@media (max-width: 480px) {

    .hero-content {
        padding: 25px 20px;
    }

    .hero h2 {
        font-size: 1.5rem;
    }

    .hero p {
        font-size: 0.95rem;
    }

    .btn-primary {
        width: 100%;
    }
}
</style>