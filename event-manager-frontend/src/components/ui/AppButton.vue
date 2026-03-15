<script setup>
const props = defineProps({
    type: {
        type: String,
        default: 'button',
    },
    variant: {
        type: String,
        default: 'primary',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
})
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        :class="['app-button', `app-button--${variant}`]"
    >
        <slot />
    </button>
</template>

<style scoped>
.app-button {
    border: 0;
    border-radius: 10px;
    padding: 10px 14px;
    color: white;
    font-weight: 600;
    transition: background 0.15s ease, opacity 0.15s ease;
}

.app-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.app-button--primary {
    background: var(--primary);
}

.app-button--primary:hover:not(:disabled) {
    background: var(--primary-hover);
}

.app-button--danger {
    background: var(--danger);
}

.app-button--danger:hover:not(:disabled) {
    background: var(--danger-hover);
}

.app-button--secondary {
    background: #475569;
}

.app-button--secondary:hover:not(:disabled) {
    background: #334155;
}
</style>