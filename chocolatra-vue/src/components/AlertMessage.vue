<script setup>
    import { ref, watch } from 'vue';

    const props = defineProps({
        message: [String, null],
        type: {
            type: String,
            validator: (value) => ['primary', 'success', 'warning', 'info', 'danger'].includes(value),
        },
        duration: Number
    })

    const visible = ref(false);

    watch(
        () => props.message,
        
        (msg) => {

            if (msg) {
                visible.value = true;
            }

            if (props.duration !== 0) {
                setTimeout(() => {
                    visible.value = false;
                }, props.duration ?? 4000);
            }
        },

        { immediate: true }
    )
</script>

<template>
    <Transition name="fade">
        <div v-if="visible && message" :class="[
            props.type === 'primary' && 'alert-primary',
            props.type === 'info' && 'alert-info',
            props.type === 'success' && 'alert-success',
            props.type === 'warning' && 'alert-warning',
            props.type === 'danger' && 'alert-danger',
        ]">
            {{ props.message }}
        </div>
    </Transition>
</template>

<style scoped>
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.5s ease;
    }

    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
    }
</style>