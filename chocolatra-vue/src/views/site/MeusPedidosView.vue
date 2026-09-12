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
    <div>
        <SiteHeader />

        <main class="meus-pedidos">
            <h1>Meus Pedidos</h1>

            <div class="filtros">
                <label>
                    De: <input type="date" v-model="dataInicio" @change="carregarPedidos" />
                </label>
                <label>
                    Até: <input type="date" v-model="dataFim" @change="carregarPedidos" />
                </label>
            </div>

            <div v-if="carregando">Carregando...</div>

            <div v-else-if="pedidos.length === 0">Nenhum pedido encontrado.</div>

            <article v-for="pedido in pedidos" :key="pedido.id" class="pedido-card">
                <header>
                    <span>Pedido #{{ pedido.id }}</span>
                    <span :class="['status', pedido.status]">{{ pedido.status }}</span>
                    <span>{{ new Date(pedido.created_at).toLocaleDateString('pt-BR') }}</span>
                </header>

                <ul>
                    <li v-for="item in pedido.itens" :key="item.id">
                        {{ item.quantidade }}x {{ item.sabor.sabor }}
                        — {{ formatarPreco(item.preco_unitario) }} cada
                    </li>
                </ul>

                <footer>
                    <strong>Total: {{ formatarPreco(pedido.total) }}</strong>
                </footer>
            </article>
        </main>

        <SiteFooter />
    </div>
</template>