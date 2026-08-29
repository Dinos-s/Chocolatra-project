<script setup>
  import { RouterLink, useRouter } from 'vue-router';
  import AlertMessage from '../components/AlertMessage.vue'
  import { ref } from 'vue';
  import api from '../services/api.js';

  const router = useRouter()
  const email = ref('')
  const senha = ref('')
  const msgError = ref('')
  const loading = ref(false)

  const submitLogin = async () => {
    msgError.value = ''
    loading.value = true

    try {
      const response = await api.post('/login', {
        email: email.value,
        password: senha.value
      })

      const token = response.data.token

      localStorage.setItem('token', token)

      router.push('/dashboard')

    } catch (error) {
      console.log(error);
      
      if (error.response?.status === 401) {
        msgError.value = 'Email ou senha inválidos.'
        return
      }

      msgError.value = 'Email ou senha inválidos.'
    }
  }

</script>

<!-- <template>
  <main class="page-container">
    <AlertMessage :message="msgError" type="danger" />

    <div class="card">
      <h2 class="card-title">Login</h2>

      <form @submit.prevent="submitLogin" class="form">
        <div class="form-group">
          <label class="form-label" for="email">Email:</label>
          <input type="email" name="email" id="email" v-model="email" class="form-input">
        </div>

        <div class="form-group">
          <label class="form-label" for="senha">Senha:</label>
          <input type="password" name="senha" id="senha" v-model="senha" class="form-input">
        </div>

        <RouterLink to="/new-user">Novo Usuário</RouterLink>

        <button type="submit" class="btn-submit">Entrar</button>
      </form>
    </div>
    
  </main>
</template> -->

<template>
  <main class="min-h-[calc(100vh-64px)] flex items-center justify-center bg-slate-50 p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-md border border-slate-100 p-6">
      <h2 class="text-2xl font-bold text-slate-800 mb-6 text-center">Entrar no Sistema</h2>
      
      <AlertMessage :message="msgError" type="danger" />

      <form @submit.prevent="submitLogin" class="flex flex-col gap-4">
        <div class="flex flex-col gap-1.5">
          <label for="email" class="text-sm font-medium text-slate-700">E-mail</label>
          <input 
            type="email" 
            name="email" 
            id="email" 
            v-model="email"
            placeholder="seu@email.com"
            required
            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
          >
        </div>

        <div class="flex flex-col gap-1.5">
          <label for="senha" class="text-sm font-medium text-slate-700">Senha</label>
          <input 
            type="password" 
            name="senha" 
            id="senha" 
            v-model="senha"
            placeholder="••••••••"
            required
            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
          >
        </div>

        <!-- <RouterLink to="/new-user" class="text-sm text-slate-500 hover:text-slate-600 transition-colors">Novo Usuário</RouterLink> -->

        <button 
          type="submit"
          class="w-full mt-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors cursor-pointer active:scale-[0.99]"
        >
          Entrar
        </button>
      </form>
    </div>
  </main>
</template>

<!-- <style scoped>
  .page-container {
    min-height: calc(100vh - 64px);
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8fafc;
    padding: 1rem;
  }

  .card {
    width: 100%;
    max-width: 28rem;
    background-color: #ffffff;
    border-radius: 0.75rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #f1f5f9;
    padding: 1.5rem;
  }

  .card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
    text-align: center;
  }

  .form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1rem;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
  }

  .form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #334155;
  }

  .form-input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #cbd5e1;
    border-radius: 0.5rem;
    color: #1e293b;
    outline: none;
    transition: all 0.2s ease;
    box-sizing: border-box;
  }

  .form-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
  }

  .btn-submit {
    width: 100%;
    margin-top: 0.5rem;
    background-color: #2563eb;
    color: #ffffff;
    font-weight: 500;
    padding: 0.625rem 1rem;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.1s ease;
  }

  .btn-submit:hover {
    background-color: #1d4ed8;
  }

  .btn-submit:active {
    transform: scale(0.99);
  }
</style> -->