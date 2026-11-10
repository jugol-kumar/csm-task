<script setup>
defineProps({ shop: { type: Object, required: true } });
</script>

<template>
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm">
        <!-- Top Gradient Stripe -->
        <div class="absolute inset-x-0 top-0 z-30 h-1.5 bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500"></div>

        <!-- Background Banner with Gradient Fade (Left to Right) -->
        <div v-if="shop.banner || shop.banner_url" class="absolute inset-0 z-0">
            <img
                :src="shop.banner || shop.banner_url"
                :alt="shop.name"
                class="h-full w-full object-cover object-right"
            />
            <!-- Gradient Overlay: Solid White on Left -> Fade/Transparent to Right -->
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/95 to-white/40 sm:to-transparent"></div>
        </div>

        <!-- Header Content Container -->
        <div class="relative z-20 p-6 sm:p-10">
            <!-- Top Bar: Back Link & Top-Right Logo -->
            <div class="flex items-center justify-between gap-4 mb-6">
                <!-- Back Link -->
                <RouterLink
                    to="/"
                    class="group inline-flex items-center gap-1.5 font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 transition-colors hover:text-orange-600"
                >
                    <span class="transition-transform duration-200 group-hover:-translate-x-1">←</span>
                    <span>All shops</span>
                </RouterLink>

                <!-- Store Logo placed at Top Right Corner (Floating over banner) -->
                <div class="relative shrink-0 w-40 p-3 rounded-lg overflow-hidden  border-white bg-white shadow-orange-100 shadow-lg ring-1 ring-slate-200/80">
                    <img
                        v-if="shop.logo || shop.logo_url"
                        :src="shop.logo || shop.logo_url"
                        :alt="shop.name"
                        class="h-full w-full object-cover"
                    />
                    <!-- Fallback Avatar -->
                    <div
                        v-else
                        class="flex h-full w-full items-center justify-center bg-gradient-to-br from-amber-500 via-orange-500 to-rose-500 font-extrabold text-xl sm:text-2xl text-white shadow-inner"
                    >
                        <span class="uppercase font-sans">{{ shop.name?.charAt(0) || 'S' }}</span>
                    </div>
                </div>
            </div>

            <!-- Shop Title & Description (Max-w set so it won't overlap with the right logo) -->
            <div class="max-w-2xl pr-0 sm:pr-12">
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-4xl leading-tight">
                    {{ shop.name }}
                </h1>

                <p v-if="shop.description" class="mt-2.5 text-sm leading-relaxed text-slate-600 sm:text-base">
                    {{ shop.description }}
                </p>
            </div>

            <!-- Meta Info Strip -->
            <dl class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-slate-200/70 pt-5 font-mono text-xs text-slate-600">
                <!-- Address -->
                <div v-if="shop.address" class="flex items-center gap-2 rounded-lg bg-slate-50/90 px-3 py-1.5 border border-slate-200/60 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 text-orange-500 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    <dt class="sr-only">Where</dt>
                    <dd class="font-medium text-slate-800">{{ shop.address }}</dd>
                </div>

                <!-- Phone -->
                <div v-if="shop.phone" class="flex items-center gap-2 rounded-lg bg-slate-50/90 px-3 py-1.5 border border-slate-200/60 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 text-orange-500 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.826-1.47-5.112-3.756-6.58-6.58l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                    </svg>
                    <dt class="sr-only">Call</dt>
                    <dd class="font-medium text-slate-800">
                        <a :href="`tel:${shop.phone}`" class="hover:text-orange-600 hover:underline">
                            {{ shop.phone }}
                        </a>
                    </dd>
                </div>

                <!-- Products Count -->
                <div class="flex items-center gap-2 rounded-lg bg-orange-50/90 px-3 py-1.5 border border-orange-100 text-orange-700 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 text-orange-600 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                    <dt class="font-medium text-orange-600/80">Products:</dt>
                    <dd class="font-bold text-orange-700">{{ shop.listings_count ?? 0 }}</dd>
                </div>
            </dl>
        </div>
    </div>
</template>
