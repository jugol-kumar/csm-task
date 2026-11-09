<script setup>
defineProps({
    items: { type: Array, required: true },
    loading: Boolean,
});
</script>

<template>
    <dl class="grid grid-cols-[repeat(auto-fit,minmax(12rem,1fr))] gap-4">
        <div
            v-for="item in items"
            :key="item.label"
            class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:border-orange-300 hover:shadow-md"
        >
            <!-- Label -->
            <dt class="font-mono text-[11px] font-medium tracking-wider text-slate-400 uppercase">
                {{ item.label }}
            </dt>

            <!-- Value / Loading State -->
            <dd class="mt-2 font-mono text-3xl font-bold tabular-nums text-slate-900 transition-colors group-hover:bg-gradient-to-r group-hover:from-amber-500 group-hover:to-orange-600 group-hover:bg-clip-text group-hover:text-transparent">
                {{ loading ? '–' : item.value }}
            </dd>

            <!-- Optional Note -->
            <p v-if="item.note" class="mt-1 text-xs text-slate-400">
                {{ item.note }}
            </p>
        </div>
    </dl>
</template>
