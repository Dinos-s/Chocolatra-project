<script setup>
    import { RouterLink } from 'vue-router';
    import SiteFooter from '../../components/SiteFooter.vue';
    import SiteHeader from '../../components/SiteHeader.vue';
    import { computed, onMounted, ref } from 'vue';

    const carrinho = ref([]);

    const carregarCarrinho = () => {
        carrinho.value = JSON.parse(localStorage.getItem('carrinho')) || []
    }

    const salvarCarrinho = () => {
        localStorage.setItem('carrinho', JSON.stringify(carrinho.value))
    }

    const aumentarQuantidade = (item) => {
        item.quantidade++
        salvarCarrinho()
    }

    const diminuirQuantidade = (item) => {
        if (item.quantidade > 1) {
            item.quantidade--
            salvarCarrinho()
        } else {
            removerItem(item)
            return
        }
    }

    const removerItem = (item) => {
        carrinho.value = carrinho.value.filter(i => i.id !== item.id)
        salvarCarrinho()
    }

    const total = computed(() => {
        return carrinho.value.reduce((acc, item) => {
            return acc + Number(item.preco)* item.quantidade
        }, 0)
    })

    const quantidadeItens = computed(() => {
        return carrinho.value.reduce((acc, item) => {
            return acc + item.quantidade
        }, 0)
    })

    const formatarPreco = (valor) => {
        return Number(valor || 2).toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        })
    }

    onMounted(() => {
        carregarCarrinho()
    })
</script>

<template>
    <div class="carrinho-page">
        <SiteHeader />

        <main class="carrinho">
            <header class="carrinho-header">
                <h1>Minhas Trufas</h1>

                <p v-if="carrinho.length">
                    {{ quantidadeItens }}
                    {{ quantidadeItens === 1 ? 'item' : 'itens' }}
                    no carrinho
                </p>
            </header>

            <!-- Carrinho Vazio -->
            <section v-if="carrinho.length === 0" class="carrinho-vazio">
                <span class="icone-vazio"><i class="fa-solid fa-cart-shopping"></i></span>

                <h2>Seu carrinho esta vazio</h2>

                <p>Escolha suas trufas favoritas e deixe seu dia ainda mais gostoso</p>

                <RouterLink to="/catalogo" class="btn-catalogo">Ver Trufas</RouterLink>
            </section>

            <!-- Carrinho com Trufas -->
            <section v-else class="carrinho-conteudo">
                <div class="lista-carrinho">

                    <article v-for="item in carrinho" :key="item.id" class="item-carrinho">
                        <div class="item-imagem">🍫</div>

                        <div class="item-info">
                            <h2>{{ item.sabor }}</h2>

                            <p class="item-preco">
                                {{ formatarPreco(item.preco) }}
                                <span>por unidade</span>
                            </p>
                        </div>

                        <div class="item-quantidade">
                            <button class="btn-quantidade" @click="diminuirQuantidade(item)"> <i class="fa-solid fa-minus"></i> </button>

                            <span>{{ item.quantidade }}</span>

                            <button class="btn-quantidade" @click="aumentarQuantidade(item)"> <i class="fa-solid fa-plus"></i> </button>
                        </div>

                        <div class="item-subtotal">
                            <strong>{{ formatarPreco(item.preco * item.quantidade) }}</strong>

                            <button class="btn-remover" @click="removerItem(item)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </article>
                </div>

                <!-- Resumo -->
                <aside class="resumo">
                    <h2>Resumo da Compra</h2>

                    <div class="resumo-linha">
                        <span>Produtos</span>

                        <strong>{{ formatarPreco(total) }}</strong>
                    </div>

                    <div class="resumo-linha">
                        <span>Frete</span>
                        <strong>A calcular</strong>
                    </div>

                    <div class="resumo-divisor"></div>

                    <div class="resumo-total">
                        <span>Total</span>

                        <strong>{{ formatarPreco(total) }}</strong>
                    </div>

                    <button class="btn-finalizar">
                        Finalizar compra
                    </button>

                    <RouterLink
                        to="/catalogo"
                        class="continuar-comprando"
                    >
                        <i class="fa-solid fa-arrow-left"></i> Continuar comprando
                    </RouterLink>
                </aside>
            </section>
        </main>

        <SiteFooter />
    </div>
</template>

<style scoped>
    .carrinho-page {
        min-height: 100vh;
        background-color: #f8f5f0;
        color: #5d4a36;
    }

    .carrinho {
        width: min(1100px, 100%);
        margin: 0 auto;
        padding: 50px 20px;
    }

    /* HEADER */
    .carrinho-header {
        margin-bottom: 35px;
    }

    .carrinho-header h1 {
        margin-bottom: 5px;
        color: #8b4513;
        font-size: 2rem;
    }

    .carrinho-header p {
        color: #8a7866;
    }

    /* CONTEÚDO */
    .carrinho-conteudo {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 30px;
        align-items: start;
    }

    /* ITEM */
    .lista-carrinho {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .item-carrinho {
        display: grid;
        grid-template-columns: 90px 1fr auto auto;
        align-items: center;
        gap: 20px;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #e8d9c5;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    /* IMAGEM */
    .item-imagem {
        width: 90px;
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0e6d8;
        border-radius: 10px;
        font-size: 2.5rem;
    }

    /* INFORMAÇÕES */
    .item-info h2 {
        margin-bottom: 8px;
        color: #5d4a36;
        font-size: 1.1rem;
    }

    .item-preco {
        color: #8b4513;
        font-weight: 600;
    }

    .item-preco span {
        color: #8a7866;
        font-size: 0.8rem;
        font-weight: normal;
    }

    /* QUANTIDADE */
    .item-quantidade {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .item-quantidade button {
        width: 32px;
        height: 32px;
        border: 1px solid #d4a574;
        border-radius: 50%;
        background-color: #f8f5f0;
        color: #8b4513;
        font-size: 1.2rem;
        cursor: pointer;
        transition: 0.2s;
    }

    .item-quantidade button:hover {
        background-color: #e8d9c5;
    }

    .item-quantidade span {
        min-width: 20px;
        text-align: center;
        font-weight: 600;
    }

    /* SUBTOTAL */
    .item-subtotal {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }

    .item-subtotal strong {
        color: #8b4513;
        font-size: 1.05rem;
    }

    .btn-remover {
        padding: 0;
        border: none;
        background: none;
        color: #8a7866;
        font-size: 0.8rem;
        cursor: pointer;
    }

    .btn-remover:hover {
        color: #aa2a13;
        text-decoration: underline;
    }

    /* RESUMO */
    .resumo {
        padding: 25px;
        background-color: #fff;
        border: 1px solid #e8d9c5;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        position: sticky;
        top: 20px;
    }

    .resumo h2 {
        margin-bottom: 25px;
        color: #5d4a36;
        font-size: 1.2rem;
    }

    .resumo-linha,
    .resumo-total {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 15px;
    }

    .resumo-linha {
        color: #8a7866;
    }

    .resumo-linha strong {
        color: #5d4a36;
    }

    .resumo-divisor {
        height: 1px;
        margin: 20px 0;
        background-color: #e8d9c5;
    }

    .resumo-total {
        margin-bottom: 25px;
        color: #5d4a36;
        font-size: 1.1rem;
    }

    .resumo-total strong {
        color: #8b4513;
        font-size: 1.3rem;
    }

    /* BOTÃO */
    .btn-finalizar,
    .btn-catalogo {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 13px 20px;
        border: none;
        border-radius: 8px;
        background-color: #8b4513;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .btn-finalizar:hover,
    .btn-catalogo:hover {
        background-color: #6b3410;
    }

    .continuar-comprando {
        display: block;
        margin-top: 15px;
        color: #8b4513;
        font-size: 0.9rem;
        text-align: center;
        text-decoration: none;
    }

    .continuar-comprando:hover {
        text-decoration: underline;
    }

    /* VAZIO */
    .carrinho-vazio {
        padding: 70px 20px;
        background-color: #fff;
        border: 1px solid #e8d9c5;
        border-radius: 12px;
        text-align: center;
    }

    .icone-vazio {
        display: block;
        margin-bottom: 15px;
        font-size: 3rem;
    }

    .carrinho-vazio h2 {
        margin-bottom: 10px;
        color: #5d4a36;
    }

    .carrinho-vazio p {
        margin-bottom: 25px;
        color: #8a7866;
    }

    .btn-catalogo {
        width: auto;
        display: inline-flex;
    }

    /* RESPONSIVO */
    @media (max-width: 850px) {
        .carrinho-conteudo {
            grid-template-columns: 1fr;
        }

        .resumo {
            position: static;
        }
    }

    @media (max-width: 650px) {
        .item-carrinho {
            grid-template-columns: 70px 1fr;
            gap: 15px;
        }

        .item-imagem {
            width: 70px;
            height: 70px;

            font-size: 2rem;
        }

        .item-quantidade {
            grid-column: 2;
        }

        .item-subtotal {
            grid-column: 2;
            align-items: flex-start;
        }
    }
</style>