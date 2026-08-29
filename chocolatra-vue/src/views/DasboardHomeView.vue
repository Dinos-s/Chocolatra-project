<template>
    <h1 class="page-title">Visão Geral</h1>
                    
    <section class="metrics-grid">
        <div class="metric-card">
            <span class="metric-title">Usuários Ativos</span>
            <p class="metric-value">1,248</p>
        </div>

        <div class="metric-card">
            <span class="metric-title">Sessões do Dia</span>
            <p class="metric-value">342</p>
        </div>

        <div class="metric-card">
            <span class="metric-title">Status do Sistema</span>
            <p class="metric-value status-online">Online</p>
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
</style>