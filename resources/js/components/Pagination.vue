<script setup>
defineProps({ meta: { type: Object, default: null } });
const emit = defineEmits(['change']);
</script>

<template>
    <div v-if="meta && meta.last_page > 1" class="mt-6 flex flex-wrap items-center justify-between gap-4 border-t border-slate-200 pt-4 font-mono text-xs">
        <span class="text-slate-500">
            Page <span class="font-semibold text-slate-700">{{ meta.current_page }}</span> of <span class="font-semibold text-slate-700">{{ meta.last_page }}</span>
            <span class="mx-1 text-slate-300">·</span>
            <span class="text-slate-400">{{ meta.total }} total</span>
        </span>
        <div class="flex items-center gap-2">
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3.5 py-2 font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:border-orange-500 hover:text-orange-600 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-slate-200 disabled:hover:text-slate-700"
                :disabled="meta.current_page <= 1"
                @click="emit('change', meta.current_page - 1)"
            >
                Prev
            </button>
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3.5 py-2 font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:border-orange-500 hover:text-orange-600 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-slate-200 disabled:hover:text-slate-700"
                :disabled="meta.current_page >= meta.last_page"
                @click="emit('change', meta.current_page + 1)"
            >
                Next
            </button>
        </div>
    </div>
</template>
