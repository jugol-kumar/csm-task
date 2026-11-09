<script setup>
defineProps({
    label: { type: String, required: true },
    error: { type: String, default: '' },
    hint: { type: String, default: '' },
});
</script>

<template>
    <label class="block space-y-1.5">
        <span class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
            {{ label }}
        </span>
        <slot />
        <span v-if="hint && !error" class="block text-xs text-slate-400">
            {{ hint }}
        </span>
        <span v-if="error" class="block text-xs font-medium text-rose-500">
            {{ error }}
        </span>
    </label>
</template>
