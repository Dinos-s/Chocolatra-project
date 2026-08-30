<script setup>
    import { ref,onMounted } from 'vue';
    import api from '../services/api';

    const carregando = ref(true)
    const apiOnline = ref(false)

    const totalUsers = ref(0)
    const totalTrufas = ref(0)

    const loadDashboard = async () => {
        carregando.value = true

        try {
            const responseUsers = await api.get('/usuarios')
            totalUsers.value = responseUsers.data.users.data.length

            const responseTrufas = await api.get('/trufas')
            totalTrufas.value = responseTrufas.data.trufas.data.length

            apiOnline.value = true
        } catch (error) {
            apiOnline.value = false   
        }
    }
    
    onMounted(() => {
        loadDashboard();
    })
</script>

<template>
    <h1 class="page-title">Visão Geral</h1>
                    
    <section class="metrics-grid">
        <div class="metric-card">
            <span class="metric-title">Total de Usuários</span>
            <p class="metric-value">{{ totalUsers }}</p>
        </div>

        <div class="metric-card">
            <span class="metric-title">Total de Trufas</span>
            <p class="metric-value">{{ totalTrufas }}</p>
        </div>

        <div class="metric-card">
            <span class="metric-title">Status do Sistema</span>
            <p class="metric-value" :class="apiOnline ? 'status-online' : 'status-offline'">{{ apiOnline ? 'Online' : 'Offline' }}</p>
        </div>
    </section>
</template>

<style scoped>
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #0f172a;
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
    }

    .metric-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 1.25rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }

    .metric-title {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }

    .metric-value {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 0.5rem;
        color: #0f172a;
    }

    .status-online {
        color: #16a34a;
    }

    .status-offline {
        color: #dc2626;
    }
</style>