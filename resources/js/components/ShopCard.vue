<script setup>
import { computed } from 'vue';

const props = defineProps({
    shop: { type: Object, required: true },
});

const shopLink = computed(() => `/shops/${encodeURIComponent(props.shop.slug)}`);
const listingsCount = computed(() => props.shop.listings_count ?? 0);
</script>

<template>
    <RouterLink
        :to="shopLink"
        class="group relative flex min-h-[190px] flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-orange-300 hover:shadow-md"
    >
        <div>
            <h3 class="text-xl font-bold tracking-tight text-slate-900 transition-colors group-hover:bg-gradient-to-r group-hover:from-amber-500 group-hover:to-orange-600 group-hover:bg-clip-text group-hover:text-transparent">
                {{ shop.name }}
            </h3>

            <p v-if="shop.address" class="mt-1 font-mono text-xs text-slate-400">
                {{ shop.address }}
            </p>

            <p v-if="shop.description" class="mt-3 line-clamp-2 text-sm text-slate-500">
                {{ shop.description }}
            </p>
        </div>

        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4 font-mono text-xs tracking-wider uppercase">
            <span class="font-medium text-slate-400">
                {{ listingsCount }} products
            </span>

            <span class="inline-flex items-center gap-1 font-semibold text-orange-600 transition-transform duration-300 group-hover:translate-x-1">
                Open <span>→</span>
            </span>
        </div>
    </RouterLink>
</template>
