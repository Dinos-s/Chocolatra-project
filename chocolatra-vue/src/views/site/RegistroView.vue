<script setup>
import { ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import api from '../../services/api'
import SiteHeader from '../../components/SiteHeader.vue'
import SiteFooter from '../../components/SiteFooter.vue'

const router = useRouter()

const form = ref({ name: '', email: '', password: '', password_confirmation: '' })
const erro = ref('')
const carregando = ref(false)

const cadastrar = async () => {
    erro.value = ''
    carregando.value = true

    try {
        const { data } = await api.post('/registro', form.value)

        localStorage.setItem('token', data.token)
        localStorage.setItem('user', JSON.stringify(data.user))

        router.push('/catalogo')
    } catch (e) {
        erro.value = e.response?.data?.message || 'Não foi possível cadastrar.'
    } finally {
        carregando.value = false
    }
}
</script>

<template>
    <div>
        <SiteHeader />
        <main class="login-page">
            <form @submit.prevent="cadastrar" class="login-form">
                <h1>Criar conta</h1>

                <p v-if="erro" class="erro">{{ erro }}</p>

                <label>Nome</label>
                <input v-model="form.name" required />

                <label>E-mail</label>
                <input type="email" v-model="form.email" required />

                <label>Senha</label>
                <input type="password" v-model="form.password" required />

                <label>Confirmar senha</label>
                <input type="password" v-model="form.password_confirmation" required />

                <button type="submit" :disabled="carregando">
                    {{ carregando ? 'Cadastrando...' : 'Cadastrar' }}
                </button>

                <RouterLink to="/login">Já tem conta? Entrar</RouterLink>
            </form>
        </main>
        <SiteFooter />
    </div>
</template>