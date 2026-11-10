<script setup>
import {formatedPrice} from "@/utils/priceFormater.js";

defineProps({
    listing: { type: Object, required: true },
    showShop: { type: Boolean, default: false },
});
</script>

<template>
    <RouterLink
        :to="`/shops/${listing.store.slug}/products/${listing.id}`"
        class="group relative flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-orange-300 hover:shadow-md"
    >
        <div class="relative aspect-square w-full overflow-hidden rounded-lg bg-slate-100">
            <img
                v-if="listing.image || listing.product.image"
                :src="listing.image || listing.product.image"
                :alt="listing.name || listing.product.name || 'Product image'"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
            <div
                v-else
                class="flex h-full w-full items-center justify-center bg-gradient-to-br from-amber-50 to-orange-100 text-orange-400"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 opacity-70">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
            </div>
        </div>
        <div class="mt-3.5 flex flex-1 flex-col justify-between">
            <div>
                <p
                    v-if="showShop"
                    class="mb-1 font-mono text-[11px] font-medium uppercase tracking-wider text-slate-400 transition-colors group-hover:text-amber-600"
                >
                    {{ listing.store.name }}
                </p>

                <h3 class="line-clamp-2 font-medium text-slate-800 transition-colors group-hover:text-slate-900">
                    {{ listing.name || listing.product.name}}
                </h3>
            </div>
            <div class="mt-4 flex items-baseline justify-between border-t border-slate-100 pt-3">
                <span class="font-mono text-xs text-slate-400">Price</span>
                <span class="font-mono text-lg font-bold tabular-nums text-slate-900 transition-colors group-hover:bg-gradient-to-r group-hover:from-amber-500 group-hover:to-orange-600 group-hover:bg-clip-text group-hover:text-transparent">
                    {{ formatedPrice(listing.price) }}
                </span>
            </div>
        </div>
    </RouterLink>
</template>
