<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '../../../services/api'
import SiteHeader from '../../../components/SiteHeader.vue'
import SiteFooter from '../../../components/SiteFooter.vue'

const trufas = ref([])
const carregando = ref(true)
const erro = ref('')

const carregarTrufas = async () => {
    carregando.value = true
    erro.value = ''

    try {
        const response = await api.get('/trufas')

        trufas.value = response.data.trufas.data ?? response.data.trufas
    } catch (error) {
        console.error(error)

        erro.value = 'Não foi possível carregar as trufas.'
    } finally {
        carregando.value = false
    }
}

const formatarPreco = (preco) => {
    return Number(preco || 0).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    })
}

onMounted(() => {
    carregarTrufas()
})

    const adicionarAoCarrinho = (trufa) => {
        const carrinho = JSON.parse(
            localStorage.getItem('carrinho') || '[]'
        )

        const item = carrinho.find(item => item.id === trufa.id)

        if (item) {
            item.quantidade++
        } else {
            carrinho.push({
                id: trufa.id,
                sabor: trufa.sabor,
                preco: trufa.preco,
                quantidade: 1
            })
        }

        localStorage.setItem(
            'carrinho',
            JSON.stringify(carrinho)
        )
    }
</script>

<template>
    <div class="trufas-page">

        <SiteHeader />

        <main class="catalogo">

            <section class="catalogo-header">
                <h1>Nossas trufas 🍫</h1>

                <p>
                    Escolha seus sabores favoritos e deixe seu dia
                    ainda mais gostoso.
                </p>
            </section>

            <div v-if="carregando" class="estado">
                <p>Carregando nossas trufas...</p>
            </div>

            <div v-else-if="erro" class="estado erro">
                <p>{{ erro }}</p>

                <button @click="carregarTrufas">
                    Tentar novamente
                </button>
            </div>

            <div
                v-else-if="trufas.length === 0"
                class="estado"
            >
                <p>Nenhuma trufa disponível no momento.</p>
            </div>

            <section v-else class="trufas-grid">

                <article
                    v-for="trufa in trufas"
                    :key="trufa.id"
                    class="trufa-card"
                >

                    <div class="trufa-imagem">
                        <span>🍫</span>
                    </div>

                    <div class="trufa-info">

                        <h2>
                            {{ trufa.sabor }}
                        </h2>

                        <p class="descricao">
                            Uma deliciosa trufa artesanal
                            preparada com muito carinho.
                        </p>

                        <div class="trufa-footer">

                            <strong>
                                {{ formatarPreco(trufa.preco) }}
                            </strong>

                            <button class="btn-adicionar" @click="adicionarAoCarrinho(trufa)">
                                Adicionar
                            </button>

                        </div>

                    </div>

                </article>

            </section>

        </main>

        <SiteFooter />

    </div>
</template>

<style scoped>

    .trufas-page {
        min-height: 100vh;
        background-color: #f8f5f0;
        color: #4a3c31;
    }

    /* CATÁLOGO */

    .catalogo {
        width: min(1100px, 100%);
        margin: 0 auto;
        padding: 45px 20px;
    }

    .catalogo-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .catalogo-header h1 {
        margin: 0 0 10px;

        color: #8b4513;
        font-size: 2.2rem;
    }

    .catalogo-header p {
        margin: 0;

        color: #8a7866;
    }

    /* GRID */

    .trufas-grid {
        display: grid;

        grid-template-columns: repeat(3, 1fr);

        gap: 25px;
    }

    /* CARD */

    .trufa-card {
        overflow: hidden;

        background-color: white;

        border-radius: 12px;

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .trufa-card:hover {
        transform: translateY(-4px);

        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
    }

    /* IMAGEM */

    .trufa-imagem {
        height: 190px;

        display: flex;
        align-items: center;
        justify-content: center;

        background-color: #f0e6d8;
    }

    .trufa-imagem span {
        font-size: 5rem;
    }

    /* INFORMAÇÕES */

    .trufa-info {
        padding: 20px;
    }

    .trufa-info h2 {
        margin: 0 0 8px;

        color: #5d4a36;
        font-size: 1.25rem;
    }

    .descricao {
        min-height: 45px;

        margin: 0 0 20px;

        color: #8a7866;
        font-size: 0.9rem;
    }

    /* RODAPÉ DO CARD */

    .trufa-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 10px;
    }

    .trufa-footer strong {
        color: #8b4513;
        font-size: 1.15rem;
    }

    /* BOTÃO */

    .btn-adicionar {
        border: none;

        background-color: #8b4513;
        color: white;

        padding: 10px 16px;

        border-radius: 8px;

        font-size: 0.9rem;
        font-weight: 600;

        cursor: pointer;

        transition: background-color 0.2s ease;
    }

    .btn-adicionar:hover {
        background-color: #6b3410;
    }

    /* ESTADOS */

    .estado {
        padding: 50px 20px;

        text-align: center;

        color: #8a7866;
    }

    .estado.erro {
        color: #8b4513;
    }

    .estado button {
        margin-top: 15px;

        padding: 10px 18px;

        border: none;
        border-radius: 8px;

        background-color: #8b4513;
        color: white;

        cursor: pointer;
    }

    /* RESPONSIVO */
    @media (max-width: 900px) {
        .trufas-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 650px) {

        .header-content {
            flex-direction: column;
        }

        nav {
            gap: 15px;
        }

        .catalogo {
            padding: 30px 15px;
        }

        .catalogo-header h1 {
            font-size: 1.8rem;
        }

        .trufas-grid {
            grid-template-columns: 1fr;
        }

        .trufa-imagem {
            height: 210px;
        }
    }
</style>