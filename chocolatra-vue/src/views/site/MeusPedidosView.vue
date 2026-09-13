<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import SiteHeader from '../../components/SiteHeader.vue'
import SiteFooter from '../../components/SiteFooter.vue'

const pedidos = ref([])
const carregando = ref(true)
const dataInicio = ref('')
const dataFim = ref('')

const formatarPreco = (valor) =>
    Number(valor || 0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })

const carregarPedidos = async () => {
    carregando.value = true

    try {
        const { data } = await api.get('/meus-pedidos', {
            params: {
                data_inicio: dataInicio.value || undefined,
                data_fim: dataFim.value || undefined,
            }
        })

        pedidos.value = data.pedidos.data
    } catch (e) {
        console.error(e)
    } finally {
        carregando.value = false
    }
}

onMounted(carregarPedidos)
</script>

<template>
    <div class="pedidos-page">
        <SiteHeader />

        <main class="meus-pedidos">
            <header class="pedidos-header">
                <h1>Meus Pedidos</h1>
                <p>Acompanhe o histórico das suas compras.</p>
            </header>

            <div class="filtros">
                <div class="campo-filtro">
                    <label for="data-inicio">De</label>
                    <input id="data-inicio" type="date" v-model="dataInicio" @change="carregarPedidos" />
                </div>

                <div class="campo-filtro">
                    <label for="data-fim">Até</label>
                    <input id="data-fim" type="date" v-model="dataFim" @change="carregarPedidos" />
                </div>
            </div>

            <div v-if="carregando" class="estado">
                <p>Carregando seus pedidos...</p>
            </div>

            <div v-else-if="pedidos.length === 0" class="estado">
                <p>Nenhum pedido encontrado.</p>
            </div>

            <div v-else class="lista-pedidos">
                <article v-for="pedido in pedidos" :key="pedido.id" class="pedido-card">
                    <header class="pedido-card-header">
                        <span class="pedido-numero">Pedido #{{ pedido.id }}</span>
                        <span :class="['status', pedido.status]">{{ pedido.status }}</span>
                        <span class="pedido-data">
                            {{ new Date(pedido.created_at).toLocaleDateString('pt-BR') }}
                        </span>
                    </header>

                    <ul class="pedido-itens">
                        <li v-for="item in pedido.itens" :key="item.id">
                            <span>{{ item.quantidade }}x {{ item.sabor.sabor }}</span>
                            <span>{{ formatarPreco(item.preco_unitario) }} cada</span>
                        </li>
                    </ul>

                    <footer class="pedido-card-footer">
                        <strong>Total: {{ formatarPreco(pedido.total) }}</strong>
                    </footer>
                </article>
            </div>
        </main>

        <SiteFooter />
    </div>
</template>

<style scoped>
.pedidos-page {
    min-height: 100vh;
    background-color: #f8f5f0;
    color: #4a3c31;
}

.meus-pedidos {
    width: min(900px, 100%);
    margin: 0 auto;
    padding: 45px 20px;
}

.pedidos-header {
    text-align: center;
    margin-bottom: 30px;
}

.pedidos-header h1 {
    margin: 0 0 10px;
    color: #8b4513;
    font-size: 2rem;
}

.pedidos-header p {
    margin: 0;
    color: #8a7866;
}

/* FILTROS */
.filtros {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin-bottom: 35px;
    padding: 18px 20px;
    background-color: #fff;
    border: 1px solid #e8d9c5;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
}

.campo-filtro {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.campo-filtro label {
    color: #5d4a36;
    font-size: 0.85rem;
    font-weight: 600;
}

.campo-filtro input {
    padding: 8px 12px;
    border: 1px solid #d4a574;
    border-radius: 8px;
    background-color: #f8f5f0;
    color: #4a3c31;
    font-size: 0.9rem;
    outline: none;
}

.campo-filtro input:focus {
    border-color: #8b4513;
    box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.12);
}

/* LISTA DE PEDIDOS */
.lista-pedidos {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.pedido-card {
    padding: 20px;
    background-color: #fff;
    border: 1px solid #e8d9c5;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
}

.pedido-card-header {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e8d9c5;
}

.pedido-numero {
    color: #5d4a36;
    font-weight: 700;
}

.pedido-data {
    margin-left: auto;
    color: #8a7866;
    font-size: 0.85rem;
}

.status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: capitalize;
}

.status.pendente {
    background-color: #fdf1de;
    color: #b6790a;
}

.status.pago {
    background-color: #e6f4ea;
    color: #227a3e;
}

.status.cancelado {
    background-color: #fbeceb;
    color: #aa2a13;
}

.pedido-itens {
    list-style: none;
    margin: 0 0 15px;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.pedido-itens li {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    color: #5d4a36;
    font-size: 0.92rem;
}

.pedido-itens li span:last-child {
    color: #8a7866;
}

.pedido-card-footer {
    display: flex;
    justify-content: flex-end;
    padding-top: 15px;
    border-top: 1px solid #e8d9c5;
}

.pedido-card-footer strong {
    color: #8b4513;
    font-size: 1.1rem;
}

/* ESTADOS */
.estado {
    padding: 50px 20px;
    text-align: center;
    color: #8a7866;
    background-color: #fff;
    border: 1px solid #e8d9c5;
    border-radius: 12px;
}

/* RESPONSIVO */
@media (max-width: 650px) {
    .meus-pedidos {
        padding: 30px 15px;
    }

    .pedidos-header h1 {
        font-size: 1.7rem;
    }

    .filtros {
        flex-direction: column;
    }

    .pedido-data {
        margin-left: 0;
        width: 100%;
    }
}
</style>