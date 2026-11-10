<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { usePagedList } from '@/composables/usePagedList';
import { storefrontService } from '@/services/storefront.service';
import SearchBar from '@/components/SearchBar.vue';
import Pagination from '@/components/Pagination.vue';
import EmptyState from '@/components/EmptyState.vue';
import ShopHeader from '@/components/ShopHeader.vue';
import ProductCard from '@/components/ProductCard.vue';

const route = useRoute();
const slug = computed(() => route.params.slug);
const shop = ref(null);
const missing = ref(false);
const filters = reactive({ sort: '' });

const { items, meta, loading, query, load, setPage } = usePagedList(
    (params) => storefrontService.products(slug.value, params),
    filters
);

async function init() {
    missing.value = false;
    shop.value = null;
    try {
        shop.value = (await storefrontService.shop(slug.value));
        await load();
    } catch (error) {
        if (error.response?.status === 404) missing.value = true;
    }
}

onMounted(init);

watch(slug, (value) => {
    if (value) {
        query.search = '';
        query.page = 1;
        init();
    }
});
</script>

<template>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <EmptyState v-if="missing" title="Shop not found" text="This shop is closed or the address is wrong.">
            <RouterLink
                to="/"
                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99]"
            >
                Back to shops
            </RouterLink>
        </EmptyState>

        <div v-else-if="shop" class="space-y-8">
            <ShopHeader :shop="shop" />

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="w-full sm:max-w-md flex-1">
                    <SearchBar v-model="query.search" placeholder="Search products in this shop..." />
                </div>

                <div class="relative w-full sm:w-auto">
                    <select
                        v-model="filters.sort"
                        class="w-full sm:w-auto appearance-none rounded-lg border border-slate-200 bg-white px-4 py-2.5 pr-10 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:border-slate-300 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                    >
                        <option value="">Newest first</option>
                        <option value="price_asc">Price, low to high</option>
                        <option value="price_desc">Price, high to low</option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
            </div>

            <div v-if="loading" class="py-12 text-center font-mono text-xs text-slate-400">
                Loading products…
            </div>

            <div v-if="items.length" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <ProductCard v-for="listing in items" :key="listing.id" :listing="listing" />
            </div>

            <EmptyState
                v-else-if="!loading"
                title="Nothing here"
                text="No products match your search criteria."
            />

            <div class="pt-4">
                <Pagination :meta="meta" @change="setPage" />
            </div>
        </div>
    </div>
</template>
