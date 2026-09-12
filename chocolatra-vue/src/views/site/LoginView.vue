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
    <div>
        <SiteHeader />
        <main class="login-page">
            <form @submit.prevent="entrar" class="login-form">
                <h1>Entrar</h1>

                <p v-if="erro" class="erro">{{ erro }}</p>

                <label>E-mail</label>
                <input type="email" v-model="email" required />

                <label>Senha</label>
                <input type="password" v-model="senha" required />

                <button type="submit" :disabled="carregando">
                    {{ carregando ? 'Entrando...' : 'Entrar' }}
                </button>

                <RouterLink to="/registro">Ainda não tem conta? Cadastre-se</RouterLink>
            </form>
        </main>
        <SiteFooter />
    </div>
</template>