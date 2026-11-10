<script setup>
defineProps({ modules: { type: Array, required: true } });
</script>

<template>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <RouterLink
            v-for="module in modules"
            :key="module.to"
            :to="module.to"
            class="group relative flex min-h-[190px] flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-orange-300 hover:shadow-md"
        >
            <div class="flex items-start justify-between">
                <span class="font-mono text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200">
                    {{ module.no }}
                </span>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-all duration-300 group-hover:bg-gradient-to-r group-hover:from-amber-500 group-hover:to-orange-600 group-hover:text-white group-hover:translate-x-1">
                    →
                </span>
            </div>

            <div>
                <h3 class="text-xl font-bold tracking-tight text-slate-900 transition-colors group-hover:bg-gradient-to-r group-hover:from-amber-500 group-hover:to-orange-600 group-hover:bg-clip-text group-hover:text-transparent">
                    {{ module.title }}
                </h3>
                <p class="mt-1.5 text-sm text-slate-500 line-clamp-2">
                    {{ module.text }}
                </p>
            </div>
        </RouterLink>
    </div>
</template>
