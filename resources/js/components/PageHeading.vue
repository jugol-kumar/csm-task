<script setup>
defineProps({
    eyebrow: { type: String, default: '' },
    title: { type: String, required: true },
    back: { type: String, default: '' },
    backLabel: { type: String, default: 'Back' },
});
</script>

<template>
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <!-- Back Link -->
            <RouterLink
                v-if="back"
                :to="back"
                class="group mb-2 inline-flex items-center gap-1 font-mono text-xs font-medium tracking-wider text-slate-400 uppercase transition-colors hover:text-orange-600"
            >
                <span class="transition-transform duration-200 group-hover:-translate-x-0.5">←</span>
                {{ backLabel }}
            </RouterLink>

            <!-- Eyebrow Text -->
            <p
                v-else-if="eyebrow"
                class="mb-1 font-mono text-xs font-semibold tracking-wider uppercase bg-gradient-to-r from-amber-500 to-orange-600 bg-clip-text text-transparent"
            >
                {{ eyebrow }}
            </p>

            <!-- Main Title -->
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                {{ title }}
            </h1>
        </div>

        <!-- Action Slot (Buttons / Filters) -->
        <div class="flex items-center gap-3">
            <slot />
        </div>
    </div>
</template>
