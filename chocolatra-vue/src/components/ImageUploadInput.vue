<script setup>
    import { computed, ref, watch, onBeforeUnmount } from 'vue';

    const props = defineProps({
        modelValue: {
            type: [File, null],
            default: null
        },
        initialUrl: {
            type: String,
            default: null
        },
        label: {
            type: String,
            default: null
        },
        required: {
            type: Boolean,
            default: false  
        },
        accept: {
            type: String,
            default: 'image/*'
        },
    })

    const emit = defineEmits(['update:modelValue'])

    const inputRef = ref(null)
    const previewLocal = ref(null)
    const arrastandoArquivo = ref(false)
    const erroArquivo = ref(null)

    // Se existir um arquivo recém-selecionado, o preview local tem prioridade;
    // caso contrário, cai pra imagem que já veio do banco (initialUrl).
    const previewAtual = computed(() => previewLocal.value || props.initialUrl)

    const liberarPreviewLocal = () => {
        if (previewLocal.value) {
            URL.revokeObjectURL(previewLocal.value)
            previewLocal.value = null
        }
    }

    const processarArquivo = (file) => {
        if (!file) return

        if(!file.type.startsWith('image/')) {
            erroArquivo.value = 'Arquivo inválido. Selecione uma imagem.'
            
            if (inputRef.value) {
                inputRef.value.value = ''
            }

            return
        }

        erroArquivo.value = null
        
        liberarPreviewLocal()
        previewLocal.value = URL.createObjectURL(file)
        emit('update:modelValue', file)
    }

    const aoSelecionarArquivo = (event) => {
        const file = event.target.files[0]
        processarArquivo(file)
    }

    const aoSoltarArquivo = (event) => {
        arrastandoArquivo.value = false
        const file = event.dataTransfer.files[0]
        processarArquivo(file)
    }

    const removerImagem = () => {
        liberarPreviewLocal()
        emit('update:modelValue', null)

        if (inputRef.value) {
            inputRef.value.value = ''
        }
    }

    // Se o pai trocar o registro sendo editado, o initialUrl muda;
    // nesse caso, descarta qualquer preview local antigo (de outro registro).
    watch(() => props.initialUrl, () => {
        liberarPreviewLocal();
    });

    const reset = () => {
        liberarPreviewLocal()
        if (inputRef.value) {
            inputRef.value.value = ''
        }
    }

    defineExpose({ reset })

    onBeforeUnmount(() => {
        liberarPreviewLocal()
    })
</script>

<template>
    <div class="image-upload">
        <label class="form-label" v-if="label">
            {{ label }}
            {{ required ? '*' : '' }}
        </label>
        
        <div class="dropzone" :class="{ 'dropzone-active': arrastandoArquivo, 'dropzone-filled': previewAtual }" @click="inputRef?.click" @dragover.prevent="arrastandoArquivo = true" @dragleave.prevent="arrastandoArquivo = false" @drop.prevent="aoSoltarArquivo">
            <input type="file" class="hidden-input" ref="inputRef" :accept="accept" @change="aoSelecionarArquivo">

            <template v-if="previewAtual">
                <img :src="previewAtual" alt="Prévia da imagem" class="preview-img">

                <button type="button" class="btn-remove" @click.stop="removerImagem" title="Remover imagem">X</button>
            </template>

            <div class="placeholder" v-else>
                <span class="placeholder-icon">📷</span>
                <span class="placeholder-text">Arraste ou selecione uma imagem</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .image-upload {
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
    }
 
    .form-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #334155;
    }
 
    .dropzone {
        position: relative;
        width: 140px;
        height: 140px;
        border: 2px dashed #cbd5e1;
        border-radius: 0.5rem;
        background-color: #f8fafc;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        transition: border-color 0.2s ease, background-color 0.2s ease;
    }
 
    .dropzone:hover {
        border-color: #94a3b8;
        background-color: #f1f5f9;
    }
 
    .dropzone-active {
        border-color: #2563eb;
        background-color: #eff6ff;
    }
 
    .dropzone-filled {
        border-style: solid;
        border-color: #e2e8f0;
        background-color: #ffffff;
    }
 
    .hidden-input {
        display: none;
    }
 
    .placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem;
        text-align: center;
    }
 
    .placeholder-icon {
        font-size: 1.5rem;
    }
 
    .placeholder-text {
        font-size: 0.75rem;
        color: #94a3b8;
        line-height: 1.2;
    }
 
    .preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
 
    .btn-remove {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: none;
        background-color: rgba(15, 23, 42, 0.65);
        color: #ffffff;
        font-size: 0.7rem;
        line-height: 1;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
 
    .btn-remove:hover {
        background-color: #dc2626;
    }
</style>