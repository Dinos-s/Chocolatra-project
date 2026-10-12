import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api, { getCsrfCookie } from '../services/api.js'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(JSON.parse(localStorage.getItem('user')) || null)

  const isAuthenticated = computed(() => !!user.value)
  const isAdmin = computed(() => user.value?.role === 'admin')
  const isClient = computed(() => user.value?.role === 'cliente')

  // Executado ao carregar a aplicação ou navegar nas rotas
  async function fetchUser() {
    try {
      const { data } = await api.get('/user')
      user.value = data
      localStorage.setItem('user', JSON.stringify(data))
      return data
    } catch (error) {
      user.value = null
      localStorage.removeItem('user')
      throw error
    }
  }

  async function login(email, password) {
    await getCsrfCookie()    
    await api.post('/login', { email, password })
    return await fetchUser()
  }

  async function logout() {
    try {
      await api.post('/logout')
    } catch (error) {
      console.error('Erro ao encerrar sessão:', error)
    } finally {
      user.value = null
      localStorage.removeItem('user')
    }
  }

  return {
    user,
    isAuthenticated,
    isAdmin,
    isClient,
    login,
    logout,
    fetchUser
  }
})