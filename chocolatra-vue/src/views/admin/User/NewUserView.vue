<script setup>
    import { ref, computed, onMounted } from 'vue';
    import AlertMessage from '../../../components/AlertMessage.vue';
    import api from '../../../services/api.js';

    // Estados da Tabela e Modos de Operação
    const usuarios = ref([]);
    const carregando = ref(true);
    const editandoId = ref(null); // null = Inserção/Novo, número/string = Edição

    // Campos do Formulário
    const nome = ref('');
    const email = ref('');
    const senha = ref('');
    const confirmarSenha = ref('');
    const telefone = ref('');
    const cpf = ref('')
    const msgError = ref('');
    const msgSucesso = ref('');

    // Alternar o texto de títulos e botões dinamicamente
    const tituloFormulario = computed(() => editandoId.value ? 'Edição de Usuário' : 'Inserção de Novo Usuário');

    const textoBotao = computed(() => editandoId.value ? 'Salvar Alterações' : 'Cadastrar Usuário');

    // Buscar usuários ao carregar a tabela
    const carregarUsuarios = async () => {
        try {
            carregando.value = true;
            const response = await api.get('/usuarios');
            console.log(response.data.users.data);
            
            usuarios.value = response.data.users.data;
        } catch (error) {
            console.error('Erro ao buscar usuários:', error);
        } finally {
            carregando.value = false;
        }
    };

    onMounted(() => {
        carregarUsuarios();
    });

    const validarSenha = (senhaVal) => {
        if (!senhaVal) return null; // Se estiver editando e vazio, opcional (ou ajuste regra)
        if (senhaVal.length < 8) return 'A senha deve ter pelo menos 8 caracteres.';

        if (!/[0-9]/.test(senhaVal)) return 'A senha deve conter pelo menos um número.';

        if (!/[!@#$%^&*(),.?":{}|<>_\-\\[\]/'`~+=;]/.test(senhaVal)) return 'A senha deve conter pelo menos um caractere especial.';

        return null;
    };

    // Preparar formulário para preencher com dados do usuário selecionado
    const selecionarParaEditar = (usuario) => {
        editandoId.value = usuario.id;
        nome.value = usuario.name || usuario.nome;
        email.value = usuario.email;
        telefone.value = usuario.phone;
        cpf.value = usuario.cpf
        senha.value = '';
        confirmarSenha.value = '';
        msgError.value = '';
        msgSucesso.value = '';

        // Rolar suavemente para o formulário abaixo
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    };

    // Limpar e resetar formulário para modo inserção
    const cancelarEdicao = () => {
        editandoId.value = null;
        nome.value = '';
        email.value = '';
        telefone.value = '';
        cpf.value = '';
        senha.value = '';
        confirmarSenha.value = '';
        msgError.value = '';
        msgSucesso.value = '';
    };

    const salvarUsuario = async () => {
        msgError.value = '';
        msgSucesso.value = '';

        // Validação de senha obrigatória apenas no cadastro ou se preenchida na edição
        if (!editandoId.value || senha.value) {
            const erroSenha = validarSenha(senha.value);
            if (erroSenha) {
                msgError.value = erroSenha;
                return;
            }

            if (senha.value !== confirmarSenha.value) {
                msgError.value = 'As senhas devem ser iguais.';
                return;
            }
        }

        try {
            if (editandoId.value) {
                // Lógica de Edição (PUT/PATCH)
                const payload = {
                    name: nome.value,
                    email: email.value,
                    phone: telefone.value,
                    cpf: cpf.value
                };

                if (senha.value) {
                    payload.password = senha.value;
                    payload.password_confirmation = confirmarSenha.value;
                }

                await api.put(`/editUser/${editandoId.value}`, payload);

                await carregarUsuarios();
                msgSucesso.value = 'Usuário atualizado com sucesso!';
            } else {
                // Lógica de Inserção (POST)
                if (!cpf.value) {
                    msgError.value = "Por favor, insira um CPF.";
                }

                if (!validarCpf(cpf.value)) {
                    msgError.value = 'CPF inválido.';
                    return;
                }
            
                await api.post('/novo', {
                    name: nome.value,
                    email: email.value,
                    phone: telefone.value,
                    cpf: cpf.value,
                    password: senha.value,
                    password_confirmation: confirmarSenha.value
                });

                msgSucesso.value = 'Usuário cadastrado com sucesso!';
            }

            await carregarUsuarios();
            cancelarEdicao();
        } catch (error) {
            if (error.response?.status === 422) {
                msgError.value = 'Verifique os campos e tente novamente.';
                return;
            }
            msgError.value = 'Ocorreu um erro ao salvar o registro.';
        }
    };

    const excluirUsuario = async (id) => {
        if (!confirm('Deseja realmente excluir este usuário?')) return;

        try {
            await api.delete(`/users/${id}`);
            msgSucesso.value = 'Usuário excluído com sucesso.';
            carregarUsuarios();
        } catch (error) {
            msgError.value = 'Erro ao excluir o usuário.';
        }
    };

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
    <div class="content-wrapper">

        <!-- SEÇÃO 1: TABELA DE USUÁRIOS -->
        <div class="card mb-6">
            <div class="card-header-flex">
                <h2 class="section-title">Usuários Cadastrados</h2>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>CPF</th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="carregando">
                            <td colspan="3" class="text-center py-4 text-slate-500">Carregando registros...</td>
                        </tr>
                        <tr v-else-if="usuarios.length === 0">
                            <td colspan="3" class="text-center py-4 text-slate-500">Nenhum usuário encontrado.</td>
                        </tr>
                        <tr v-for="user in usuarios" :key="user.id" :class="{ 'row-selected': editandoId === user.id }">
                            <td class="font-medium text-slate-800">{{ user.name || user.nome }}</td>
                            <td class="text-slate-600">{{ user.email }}</td>
                            <td class="text-slate-600">{{ user.phone }}</td>
                            <td class="text-slate-600">{{ user.cpf }}</td>
                            <td class="text-right action-buttons">
                                <button @click="selecionarParaEditar(user)" class="btn-icon btn-edit" title="Editar">
                                    ✏️
                                </button>
                                <button @click="excluirUsuario(user.id)" class="btn-icon btn-delete" title="Excluir">
                                    🗑️
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SEÇÃO 2: FORMULÁRIO DE INSERÇÃO / EDIÇÃO -->
        <div class="card form-card" :class="{ 'editing-mode': editandoId }">
            <div class="form-header-row">
                <h2 class="section-title">{{ tituloFormulario }}</h2>
                <button v-if="editandoId" @click="cancelarEdicao" class="btn-cancel">
                    Cancelar Edição
                </button>
            </div>

            <AlertMessage :message="msgError" type="danger" />
            <AlertMessage :message="msgSucesso" type="success" />

            <form @submit.prevent="salvarUsuario" class="form">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="nome">Nome*</label>
                        <input type="text" id="nome" v-model="nome" placeholder="Informe um nome" required
                            class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">E-mail*</label>
                        <input type="email" id="email" v-model="email" placeholder="seu@email.com" required
                            class="form-input">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="senha">
                            Senha{{ editandoId ? '(opcional)' :'*' }}
                        </label>
                        <input type="password" id="senha" v-model="senha" placeholder="••••••••" :required="!editandoId"
                            class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="confirmarSenha">
                            Confirmar Senha{{ editandoId ? '(opcional)' : '*' }}
                        </label>
                        <input type="password" id="confirmarSenha" v-model="confirmarSenha" placeholder="••••••••"
                            :required="!editandoId && !!senha" class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="telefone">Telefone</label>
                        <input type="tel" id="telefone" v-model="telefone" placeholder="(00) 00000-0000"
                            class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="cpf">CPF*</label>
                        <input type="text" id="cpf" v-model="cpf" placeholder="000.000.000-00" class="form-input" @input="formatarCPF" :readonly="editandoId" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        {{ textoBotao }}
                    </button>
                </div>
            </form>
        </div>

    </div>
</template>

<style scoped>
    .content-wrapper {
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .card {
        background-color: #ffffff;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    .form-card.editing-mode {
        border-color: #3b82f6;
        background-color: #f8fafc;
    }

    .card-header-flex,
    .form-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }

    /* Estilos da Tabela */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.9rem;
    }

    .data-table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }

    .data-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    .row-selected {
        background-color: #eff6ff !important;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .btn-icon {
        background: transparent;
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem;
        padding: 0.35rem 0.5rem;
        cursor: pointer;
        font-size: 0.85rem;
        transition: background-color 0.2s;
    }

    .btn-icon:hover {
        background-color: #f1f5f9;
    }

    .btn-cancel {
        background-color: #e0190b;
        color: #f5f6f7;
        border: none;
        padding: 0.4rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background-color: #b80b0b;
    }

    /* Estilos do Formulário */
    .form {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
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
        padding: 0.625rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem;
        color: #1e293b;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        box-sizing: border-box;
        background-color: #ffffff;
    }

    .form-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 0.5rem;
    }

    .btn-submit {
        background-color: #2563eb;
        color: #ffffff;
        font-weight: 500;
        font-size: 0.875rem;
        padding: 0.625rem 1.5rem;
        border: none;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.1s ease;
    }

    .btn-submit:hover {
        background-color: #1d4ed8;
    }

    .btn-submit:active {
        transform: scale(0.98);
    }

    @media (max-width: 640px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .btn-submit {
            width: 100%;
        }
    }
</style>