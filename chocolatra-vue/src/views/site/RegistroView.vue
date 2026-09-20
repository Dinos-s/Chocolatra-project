<script setup>
    import { ref } from 'vue'
    import { useRouter, RouterLink } from 'vue-router'
    import api from '../../services/api'
    import SiteHeader from '../../components/SiteHeader.vue'
    import SiteFooter from '../../components/SiteFooter.vue'
    import AlertMessage from '../../components/AlertMessage.vue'

    const router = useRouter()

    // Campos do Formulário
    const nome = ref('');
    const email = ref('');
    const senha = ref('');
    const confirmarSenha = ref('');
    const telefone = ref('');
    const cpf = ref('')
    const erro = ref('')
    const carregando = ref(false)

    const validarSenha = (senhaVal) => {
        if (!senhaVal) return null; // Se estiver editando e vazio, opcional (ou ajuste regra)
        if (senhaVal.length < 8) return 'A senha deve ter pelo menos 8 caracteres.';

        if (!/[0-9]/.test(senhaVal)) return 'A senha deve conter pelo menos um número.';

        if (!/[!@#$%^&*(),.?":{}|<>_\-\\[\]/'`~+=;]/.test(senhaVal)) return 'A senha deve conter pelo menos um caractere especial.';

        return null;
    }

    const cadastrar = async () => {
        erro.value = ''
        carregando.value = true

        if(senha.value) {
            const erroSenha = validarSenha(senha.value);
            if (erroSenha) {
                erro.value = erroSenha;
                return;
            }

            if (senha.value !== confirmarSenha.value) {
                erro.value = 'As senhas devem ser iguais.';
                return;
            }
        }

        try {
            if(!cpf.value) return erro.value = 'O CPF deve ser preenchido.'

            if(!validarCpf(cpf.value)) return erro.value = 'O CPF informado é inválido.'

            const payload = {
                name: nome.value,
                email: email.value,
                phone: telefone.value,
                cpf: cpf.value,
                password: senha.value,
                password_confirmation: confirmarSenha.value
            };

            const { data } = await api.post('/registro', payload)

            localStorage.setItem('token', data.token)
            localStorage.setItem('user', JSON.stringify(data.user))

            router.push('/catalogo')
        } catch (e) {
            erro.value = e.response?.data?.message || 'Não foi possível cadastrar.'
        } finally {
            carregando.value = false
        }
    }

    const validarCpf = (cpf) => {
        const limpo = String(cpf).replace(/[^\d]+/g, '');

        if (limpo.length !== 11 || /^(\d)\1+$/.test(limpo)) return false;

        let soma = 0;
        for(let i = 0; i < 9; i++) {
            soma += parseInt(limpo[i]) * (10 - i);
        }

        let resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) {
            resto = 0;
        }
        if (resto !== parseInt(limpo.charAt(9))) return false;

        soma = 0;
        for(let i = 0; i < 10; i++) {
            soma += parseInt(limpo[i]) * (11 - i);
        }

        resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) {
            resto = 0;
        }
        if (resto !== parseInt(limpo.charAt(10))) return false;

        return true;
    }

    const formatarCPF = (event) => {
        let valor = event.target.value.replace(/\D/g, '');
        
        if (valor.length > 11) {
            valor = valor.slice(0, 11);
        }

        valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
        valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
        valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        event.target.value = valor;
    }
</script>

<template>
    <div class="page-container">
        <SiteHeader />
        <main class="login-page">

            <form @submit.prevent="cadastrar" class="login-form">
                <h1>Criar conta</h1>
                
                <AlertMessage :message="erro" type="danger" />

                <div class="campo">
                    <label>Nome*</label>
                    <input type="text" v-model="nome" required />
                </div>

                <div class="campo">
                    <label>E-mail*</label>
                    <input type="email" v-model="email" required />
                </div>

                <div class="campo">
                    <label>CPF*</label>
                    <input type="text" v-model="cpf" @input="formatarCPF" required />
                </div>

                <div class="campo">
                    <label>Telefone</label>
                    <input type="tel" v-model="telefone" required />
                </div>

                <div class="campo">
                    <label>Senha*</label>
                    <input type="password" v-model="senha" required />
                </div>

                <div class="campo">
                    <label>Confirmar senha*</label>
                    <input type="password" v-model="confirmarSenha" required />
                </div>
                
                <button type="submit" class="btn-acessar" :disabled="carregando">
                    {{ carregando ? 'Cadastrando...' : 'Cadastrar' }}
                </button>

                <RouterLink class="link-secundario" to="/login">Já tem conta? Entrar</RouterLink>
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