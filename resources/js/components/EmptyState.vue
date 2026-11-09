<script setup>
defineProps({
    title: { type: String, required: true },
    text: { type: String, default: '' },
});
</script>

<template>
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25-2.25M12 13.875V3.75" />
            </svg>
        </div>

        <p class="font-mono text-xs font-bold uppercase tracking-wider text-slate-800">
            {{ title }}
        </p>

        <p v-if="text" class="mx-auto mt-1.5 max-w-sm text-sm text-slate-500">
            {{ text }}
        </p>

        <div class="mt-6 flex justify-center">
            <slot />
        </div>
    </div>
</template>
