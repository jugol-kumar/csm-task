<script setup>
defineProps({ shop: { type: Object, required: true } });
</script>

<template>
    <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <!-- Accent Top Bar Gradient -->
        <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500"></div>

        <!-- Back Link -->
        <RouterLink
            to="/"
            class="group mb-4 inline-flex items-center gap-1.5 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400 transition-colors hover:text-orange-600"
        >
            <span class="transition-transform duration-200 group-hover:-translate-x-1">←</span>
            <span>All shops</span>
        </RouterLink>

        <!-- Shop Name -->
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
            {{ shop.name }}
        </h1>

        <!-- Shop Description -->
        <p v-if="shop.description" class="mt-2.5 max-w-3xl text-sm leading-relaxed text-slate-600 sm:text-base">
            {{ shop.description }}
        </p>

        <!-- Meta Details (Address, Phone, Product Count) -->
        <dl class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-2 border-t border-slate-100 pt-4 font-mono text-xs text-slate-500">
            <!-- Address -->
            <div v-if="shop.address" class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-orange-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                <dt class="sr-only">Where</dt>
                <dd class="font-medium text-slate-700">{{ shop.address }}</dd>
            </div>

            <!-- Phone -->
            <div v-if="shop.phone" class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-orange-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.826-1.47-5.112-3.756-6.58-6.58l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                </svg>
                <dt class="sr-only">Call</dt>
                <dd class="font-medium text-slate-700">{{ shop.phone }}</dd>
            </div>

            <!-- Product Count Badge -->
            <div class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-orange-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>
                <dt class="text-slate-400">Products:</dt>
                <dd class="font-semibold text-slate-900">{{ shop.listings_count ?? 0 }}</dd>
            </div>
        </dl>
    </div>
</template>
