<script setup>
    import { ref, onMounted, computed } from 'vue';
    import api from '../../../services/api';
    import AlertMessage from '../../../components/AlertMessage.vue';
    import ImageUploadInput from '../../../components/ImageUploadInput.vue';

    const sabores = ref([]);

    const carregando = ref(true);
    const editandoId = ref(null); // null = Inserção/Novo, número/string = Edição

    // Campos do Formulário
    const sabor = ref('');
    const preco = ref('');
    const msgError = ref('');
    const msgSucesso = ref('');

    // upload imagem
    const arquivoImagem = ref(null);   // File novo selecionado (ou null)
    const imagemAtualUrl = ref(null);  // URL da imagem já salva no banco (ao editar)
    const imageUploadRef = ref(null);  // referência ao componente, pra poder chamar .reset()

    // Alternar o texto de títulos e botões dinamicamente
    const tituloFormulario = computed(() => editandoId.value ? 'Edição de Sabor' : 'Inserção de Novo Sabor');

    const textoBotao = computed(() => editandoId.value ? 'Salvar Alterações' : 'Cadastrar Sabor');

    // Buscar sabores ao carregar a tabela
    const buscarSabores = async () => {
        try {
            carregando.value = true;
            const response = await api.get('/sabores');
            console.log(response.data.sabores.data);

            sabores.value = response.data.sabores.data ?? response.data.sabores;
        } catch (error) {
            console.error('Erro ao buscar sabores:', error);
        } finally {
            carregando.value = false;
        }
    };

    onMounted(() => {
        buscarSabores();
    });

    const selecionarImg = (event) => {
        const file = event.target.files[0];
 
        if (!file) {
            arquivoImagem.value = null;
            return;
        }
 
        arquivoImagem.value = file;
 
        // libera a URL de preview anterior (se era um blob local) antes de criar outra
        if (previewImg.value && previewImg.value.startsWith('blob:')) {
            URL.revokeObjectURL(previewImg.value);
        }
 
        previewImg.value = URL.createObjectURL(file);
    };

    const selecionarParaEditar = (s) => {
        editandoId.value = s.id;
        sabor.value = s.sabor;
        preco.value = Number(s.preco).toFixed(2).replace('.', ',');
        imagemAtualUrl.value = s.image ? s.img_url : null;
        arquivoImagem.value = null;
        msgError.value = '';
        msgSucesso.value = '';

        // Rolar suavemente para o formulário abaixo
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        
    }

    const cancelarEdicao = () => {
        editandoId.value = null;
        sabor.value = '';
        preco.value = '';
        arquivoImagem.value = null;
        imagemAtualUrl.value = null;
        msgError.value = '';
        msgSucesso.value = '';

        imageUploadRef.value?.reset()
    }

    const constFormData = () => {
        const formData = new FormData();
        formData.append('sabor', sabor.value);
        formData.append('preco', Number(preco.value.replace(',', '.')));

        if (arquivoImagem.value) {
            formData.append('image', arquivoImagem.value);
        }

        console.log(formData);
        
        return formData;
    };

    const salvarSabor = async () => {
        msgError.value = '';
        msgSucesso.value = '';

        try {
            const formData = constFormData();
            
            if (editandoId.value) {
                formData.append('_method', 'PUT');
                
                await api.post(`/editSabor/${editandoId.value}`, formData);

                msgSucesso.value = 'Sabor editado com sucesso.';
            } else {                
                await api.post('/novoSabor', formData);

                msgSucesso.value = 'Sabor salvo com sucesso.';
            }

            await buscarSabores();
            cancelarEdicao();
        } catch (error) {
            if (error.response?.status === 422) {
                msgError.value = 'Verifique os campos e tente novamente.';
                return
            }
            msgError.value = 'Erro ao salvar o sabor.';
        }
    }

    const excluirSabor = async (id) => {
        if (!confirm('Deseja realmente excluir este sabor?')) return;

        try {
            await api.delete(`/sabor/${id}`);
            msgSucesso.value = 'Sabor excluido com sucesso.';
            buscarSabores();
        } catch (error) {
            msgError.value = 'Erro ao excluir o sabor.';
        }
    }
</script>

<template>
    <div class="content-wrapper">

        <!-- SEÇÃO 1: TABELA DE SABORES -->
        <div class="card mb-6">
            <div class="card-header-flex">
                <h2 class="section-title">Sabores Cadastrados</h2>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Sabor</th>
                            <th>Preço</th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="carregando">
                            <td colspan="3" class="text-center py-4 text-slate-500">Carregando registros...</td>
                        </tr>
                        <tr v-else-if="sabores.length === 0">
                            <td colspan="3" class="text-center py-4 text-slate-500">Nenhum sabor cadastrado.</td>
                        </tr>
                        <tr v-for="sabor in sabores" :key="sabor.id" :class="{ 'row-selected': editandoId === sabor.id }">
                            <td class="font-medium text-slate-800">{{ sabor.sabor }}</td>
                            <td class="text-slate-600">R$ {{ Number(sabor.preco).toFixed(2).replace('.', ',') }}</td>
                            <td class="text-right action-buttons">
                                <button @click="selecionarParaEditar(sabor)" class="btn-icon btn-edit" title="Editar">
                                    ✏️
                                </button>
                                <button @click="excluirSabor(sabor.id)" class="btn-icon btn-delete" title="Excluir">
                                    🗑️
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SEÇÃO 2: FORMULARIO DE CADASTRO DE SABORES -->
        <div class="card form-card">
            <div class="form-header-row">
                <h2 class="section-title">{{ tituloFormulario }}</h2>
                <button v-if="editandoId" @click="cancelarEdicao" class="btn-cancel">
                    Cancelar Edição
                </button>
            </div>

            <AlertMessage :message="msgError" type="danger" />
            <AlertMessage :message="msgSucesso" type="success" />

            <form @submit.prevent="salvarSabor" class="form" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="sabor">Sabor*</label>
                        <input type="text" id="sabor" v-model="sabor" placeholder="Informe um sabor" required class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="preco">Preco R$*</label>
                        <input type="text" id="preco" v-model="preco" placeholder="Informe um preco" required class="form-input">
                    </div> 

                    <!-- <div class="form-group">
                        <label class="form-label" for="image">Imagem{{ editandoId ? '' : '*' }}</label>
                        <input
                            ref="fileInput"
                            class="form-input"
                            type="file"
                            id="image"
                            name="image"
                            accept="image/*"
                            :required="!editandoId"
                            @change="selecionarImg"
                        >
                        <img
                            v-if="previewImg"
                            :src="previewImg"
                            alt="Prévia da imagem"
                            class="img-preview"
                        >
                    </div> -->
                    
                    <ImageUploadInput
                        ref="imageUploadRef"
                        v-model="arquivoImagem"
                        :initial-url="imagemAtualUrl"
                        label="Imagem"
                        accept="image/*"
                        :required="!editandoId"
                    />
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