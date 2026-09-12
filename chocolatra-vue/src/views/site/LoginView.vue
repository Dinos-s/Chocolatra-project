<script setup>
import { ref } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router'
import api from '../../services/api.js'
import SiteHeader from '../../components/SiteHeader.vue'
import SiteFooter from '../../components/SiteFooter.vue'

const router = useRouter()
const route = useRoute()

const email = ref('')
const senha = ref('')
const erro = ref('')
const carregando = ref(false)

const entrar = async () => {
    erro.value = ''
    carregando.value = true

    try {
        const { data } = await api.post('/login', {
            email: email.value,
            password: senha.value
        })

        localStorage.setItem('token', data.token)
        localStorage.setItem('user', JSON.stringify(data.user))

        // volta pra onde o usuário estava tentando ir (ex: o carrinho)
        router.push(route.query.redirect || '/catalogo')
    } catch (e) {
        erro.value = 'E-mail ou senha inválidos.'
    } finally {
        carregando.value = false
    }
}
</script>

<template>
    <div class="page-container">
        <SiteHeader />
        <main class="login-page">
            <form @submit.prevent="entrar" class="login-form">
                <h1>Entrar</h1>

                <p v-if="erro" class="erro">{{ erro }}</p>

                <div class="campo">
                    <label for="email">E-mail</label>
                    <input id="email" type="email" v-model="email" required />
                </div>

                <div class="campo">
                    <label for="senha">Senha</label>
                    <input id="senha" type="password" v-model="senha" required />
                </div>

                <button type="submit" class="btn-acessar" :disabled="carregando">
                    {{ carregando ? 'Entrando...' : 'Entrar' }}
                </button>

                <RouterLink to="/registro" class="link-secundario">
                    Ainda não tem conta? Cadastre-se
                </RouterLink>
            </form>
        </main>
        <SiteFooter />
    </div>
</template>

<style scoped>
.page-container {
    min-height: 100vh;
    background-color: #f8f5f0;
}

.login-page {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 45px 20px;
}

.login-form {
    width: 100%;
    max-width: 400px;
    padding: 35px 30px;
    background-color: #fff;
    border: 1px solid #e8d9c5;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.login-form h1 {
    margin: 0 0 25px;
    color: #8b4513;
    font-size: 1.6rem;
    text-align: center;
}

.erro {
    margin: 0 0 18px;
    padding: 10px 14px;
    background-color: #fbeceb;
    border: 1px solid #f0c4c0;
    border-radius: 8px;
    color: #aa2a13;
    font-size: 0.85rem;
    text-align: center;
}

.campo {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 18px;
}

.campo label {
    color: #5d4a36;
    font-size: 0.85rem;
    font-weight: 600;
}

.campo input {
    padding: 10px 14px;
    border: 1px solid #d4a574;
    border-radius: 8px;
    background-color: #f8f5f0;
    color: #4a3c31;
    font-size: 0.95rem;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.campo input:focus {
    border-color: #8b4513;
    box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.12);
}

.btn-acessar {
    width: 100%;
    padding: 12px 16px;
    margin-top: 5px;
    border: none;
    border-radius: 8px;
    background-color: #8b4513;
    color: #fff;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-acessar:hover {
    background-color: #6b3410;
}

.btn-acessar:disabled {
    background-color: #c9a17e;
    cursor: not-allowed;
}

.link-secundario {
    display: block;
    margin-top: 18px;
    color: #8b4513;
    font-size: 0.85rem;
    text-align: center;
    text-decoration: none;
}

.link-secundario:hover {
    text-decoration: underline;
}
</style>