<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { storefrontService } from '@/services/storefront.service';
import EmptyState from '@/components/EmptyState.vue';
import { formatedPrice } from "@/utils/priceFormater.js";

const route = useRoute();
const listing = ref(null);
const missing = ref(false);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await storefrontService.product(route.params.slug, route.params.id);
        listing.value = response.data ?? response;
    } catch {
        missing.value = true;
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div v-if="loading" class="py-24 text-center font-mono text-xs text-slate-400">
            Loading product details…
        </div>

        <EmptyState v-else-if="missing" title="Product not found" text="It may have been taken off the shelf or the link is incorrect.">
            <RouterLink
                :to="`/shops/${route.params.slug}`"
                class="mt-4 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/20 transition-all duration-200 hover:opacity-95 active:scale-[0.99]"
            >
                Back to the shop
            </RouterLink>
        </EmptyState>

        <div v-else-if="listing" class="space-y-6">
            <div class="flex items-center justify-between">
                <RouterLink
                    :to="`/shops/${route.params.slug}`"
                    class="group inline-flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 transition-colors hover:text-orange-600"
                >
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white shadow-sm transition-transform duration-200 group-hover:-translate-x-1">
                        ←
                    </span>
                    <span>Back to {{ listing.shop?.name || 'Shop' }}</span>
                </RouterLink>

                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3.5 py-1 font-mono text-xs font-semibold text-emerald-600 border border-emerald-200 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    In Stock & Ready
                </span>
            </div>

            <div class="grid gap-8 lg:grid-cols-12 lg:items-start rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-sm">
                <div class="lg:col-span-6">
                    <div class="overflow-hidden rounded-2xl bg-slate-50 border border-slate-100 aspect-square shadow-sm">
                        <img
                            :src="listing.image || listing.product?.image"
                            :alt="listing.title || listing.product?.name"
                            class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                        />
                    </div>
                </div>

                <div class="lg:col-span-6 flex flex-col justify-center space-y-6 lg:pl-6">
                    <div v-if="listing.shop">
                        <RouterLink
                            :to="`/shops/${listing.shop.slug}`"
                            class="inline-flex items-center gap-1.5 font-mono text-xs font-semibold tracking-wider uppercase text-orange-600 bg-orange-50 px-3 py-1.5 rounded-lg border border-orange-100 hover:bg-orange-100 transition-colors"
                        >
                            <span>🏪</span> {{ listing.shop.name }}
                        </RouterLink>
                    </div>

                    <h1 class="text-xl font-extrabold tracking-tight text-slate-900 sm:text-2xl leading-snug">
                        {{ listing.title || listing.product?.name }}
                    </h1>

                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-slate-400 block font-mono text-xs uppercase tracking-wider mb-1">Price</span>
                        <span class="font-mono text-4xl font-extrabold text-slate-900 tabular-nums">
                        {{ formatedPrice(listing.price) }}
                    </span>
                    </div>

                    <div v-if="listing.store" class="rounded-2xl border border-slate-200/80 bg-slate-50/70 p-4 shadow-sm space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                            <span class="font-mono text-[11px] font-bold uppercase tracking-wider text-slate-500">Store & Pickup Details</span>
                            <span class="inline-flex items-center gap-1 font-mono text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Verified Merchant
                            </span>
                        </div>

                        <div class="space-y-3 font-mono text-xs">
                            <div v-if="listing.store" class="flex items-start gap-3 rounded-xl border border-slate-200/60 bg-white p-3">
                                <div class="relative h-11 w-11 shrink-0 overflow-hidden rounded-xl border border-slate-100 bg-slate-50">
                                    <div
                                        class="flex h-full w-full items-center justify-center bg-gradient-to-br from-amber-500 via-orange-500 to-rose-500 font-bold text-white"
                                    >
                                        <span class="text-base font-sans uppercase">
                                            {{ listing.store.name?.charAt(0) || 'S' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                        <div class="truncate">
                                            <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Store Name</span>
                                            <span class="font-semibold text-slate-900 text-sm block font-sans leading-tight mt-0.5 truncate">
                                            {{ listing.store.name }}
                                        </span>
                                        </div>

                                        <div v-if="listing.store.owner?.name" class="truncate">
                                            <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Store Owner</span>
                                            <span class="font-medium text-slate-700 text-sm block font-sans leading-tight mt-0.5 truncate">
                                            {{ listing.store.owner.name }}
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="listing.store?.address" class="flex items-start gap-2.5 pt-1 pl-1">
                                <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Pickup Location</span>
                                    <span class="font-medium text-slate-800 text-sm block font-sans leading-tight mt-0.5">
                                        {{ listing.store.address }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <div class="lg:col-span-8 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-4">
                    <h3 class="font-mono text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3">
                        Product Description
                    </h3>
                    <p class="whitespace-pre-line text-sm leading-relaxed text-slate-600 sm:text-base">
                        {{ listing.description || listing.product?.description || 'No description provided for this product.' }}
                    </p>
                </div>
        </div>
    </div>
</template>
