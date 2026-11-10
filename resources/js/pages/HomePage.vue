<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import ShopCard from '@/components/ShopCard.vue';
import { storefrontService } from '@/services/storefront.service';

const PER_PAGE = 12;

const route = useRoute();
const router = useRouter();

const shops = ref([]);
const loading = ref(true);
const error = ref(false);
const total = ref(0);
const lastPage = ref(1);

const page = ref(Math.max(1, Number(route.query.page) || 1));
const search = ref(String(route.query.q ?? ''));

let requestId = 0;
let searchTimer;

async function fetchShops() {
    const id = ++requestId;
    loading.value = true;
    error.value = false;

    try {
        const res = await storefrontService.shops({
            page: page.value,
            per_page: PER_PAGE,
            search: search.value || undefined,
        });

        if (id !== requestId) return;

        const meta = res.meta ?? res;
        shops.value = res.data ?? [];
        total.value = meta.total ?? shops.value.length;
        lastPage.value = meta.last_page ?? 1;
    } catch {
        if (id !== requestId) return;
        error.value = true;
        shops.value = [];
    } finally {
        if (id === requestId) loading.value = false;
    }
}

function syncQuery() {
    router.replace({
        query: {
            ...(page.value > 1 && { page: page.value }),
            ...(search.value && { q: search.value }),
        },
    });
}

function goTo(p) {
    if (p < 1 || p > lastPage.value || p === page.value) return;
    page.value = p;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

watch(page, () => {
    syncQuery();
    fetchShops();
});

watch(search, () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        if (page.value !== 1) {
            page.value = 1;
        } else {
            syncQuery();
            fetchShops();
        }
    }, 350);
});

const pages = computed(() => {
    const last = lastPage.value;
    const current = page.value;
    if (last <= 7) return Array.from({ length: last }, (_, i) => i + 1);

    const items = [1];
    const start = Math.max(2, current - 1);
    const end = Math.min(last - 1, current + 1);

    if (start > 2) items.push('…');
    for (let i = start; i <= end; i++) items.push(i);
    if (end < last - 1) items.push('…');
    items.push(last);

    return items;
});

const from = computed(() => (total.value === 0 ? 0 : (page.value - 1) * PER_PAGE + 1));
const to = computed(() => Math.min(page.value * PER_PAGE, total.value));

function onBeforeEnter(el) {
    const index = Math.min(Number(el.dataset.index) || 0, 11);
    el.style.transitionDelay = `${index * 60}ms`;
}

function onAfterEnter(el) {
    el.style.transitionDelay = '';
}

onMounted(fetchShops);
</script>

<template>
    <div class="min-h-screen ">
        <Transition name="fade">
            <div v-if="loading" class="fixed inset-x-0 top-0 z-50 h-1 overflow-hidden bg-orange-100">
                <div class="loading-bar h-full w-1/3 rounded-full bg-gradient-to-r from-orange-500 to-amber-400"></div>
            </div>
        </Transition>

        <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:py-12">
            <header
                class="relative overflow-hidden rounded-3xl border border-orange-100 bg-gradient-to-br from-orange-50 via-white to-orange-50/40 px-6 py-10 shadow-sm sm:px-10 lg:py-14"
            >
                <div class="pointer-events-none absolute -top-24 -right-24 size-80 rounded-full bg-orange-200/40 blur-3xl"></div>

                <div class="relative flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <span
                            class="reveal inline-flex items-center gap-2 rounded-full border border-orange-200 bg-white px-3 py-1 text-xs font-medium text-orange-600 shadow-sm"
                            style="--d: 0ms"
                        >
                            <span class="size-1.5 animate-pulse rounded-full bg-orange-500"></span>
                            Shop directory
                        </span>
                        <h1
                            class="reveal mt-4 text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl"
                            style="--d: 80ms"
                        >
                            Discover <span class="text-orange-600">all shops</span>
                        </h1>
                        <p class="reveal mt-3 max-w-md text-sm leading-relaxed text-slate-600" style="--d: 160ms">
                            Browse every shop on the platform and open one to explore its products.
                        </p>
                    </div>

                    <div class="reveal w-full lg:max-w-sm" style="--d: 240ms">
                        <label for="shop-search" class="sr-only">Search shops</label>
                        <div
                            class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 shadow-sm transition-all duration-200 focus-within:border-orange-400 focus-within:ring-4 focus-within:ring-orange-500/15"
                        >
                            <svg class="size-5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="7" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                            <input
                                id="shop-search"
                                v-model="search"
                                type="search"
                                placeholder="Search by name or address…"
                                class="min-w-0 flex-1 bg-transparent py-3.5 text-sm text-slate-900 outline-none placeholder:text-slate-400 [&::-webkit-search-cancel-button]:hidden"
                            />
                            <Transition name="pop">
                                <button
                                    v-if="search"
                                    type="button"
                                    aria-label="Clear search"
                                    class="flex size-6 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition-colors hover:bg-orange-500 hover:text-white"
                                    @click="search = ''"
                                >
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                        <path d="M18 6 6 18M6 6l12 12" />
                                    </svg>
                                </button>
                            </Transition>
                        </div>
                    </div>
                </div>
            </header>

            <div
                class="reveal mt-8 flex items-center justify-between text-sm text-slate-500"
                style="--d: 320ms"
            >
                <p v-if="!loading && !error">
                    Showing
                    <span class="font-semibold text-slate-900">{{ from }}–{{ to }}</span>
                    of
                    <span class="font-semibold text-slate-900">{{ total }}</span>
                    shops
                </p>
                <p v-else>&nbsp;</p>
                <p v-if="lastPage > 1 && !error" class="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-600 shadow-sm ring-1 ring-slate-200">
                    Page {{ page }} of {{ lastPage }}
                </p>
            </div>

            <div
                v-if="error"
                class="pop-in mt-5 rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm"
            >
                <div class="mx-auto flex size-14 items-center justify-center rounded-full bg-orange-50 text-orange-600">
                    <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 8v4M12 16h.01" />
                    </svg>
                </div>
                <h2 class="mt-5 text-lg font-semibold text-slate-900">Couldn't load shops</h2>
                <p class="mt-1 text-sm text-slate-500">Something went wrong. Please try again.</p>
                <button
                    type="button"
                    class="mt-6 rounded-lg bg-orange-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-700"
                    @click="fetchShops"
                >
                    Try again
                </button>
            </div>

            <div v-else-if="loading" class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="n in 6"
                    :key="n"
                    class="min-h-52 animate-pulse rounded-2xl border border-slate-200 bg-white p-6"
                >
                    <div class="flex items-start gap-4">
                        <div class="size-12 rounded-xl bg-slate-200"></div>
                        <div class="flex-1">
                            <div class="h-5 w-2/3 rounded bg-slate-200"></div>
                            <div class="mt-2 h-3 w-1/2 rounded bg-slate-100"></div>
                        </div>
                    </div>
                    <div class="mt-5 h-3 w-full rounded bg-slate-100"></div>
                    <div class="mt-2 h-3 w-4/5 rounded bg-slate-100"></div>
                    <div class="mt-6 flex justify-between border-t border-slate-100 pt-4">
                        <div class="h-6 w-24 rounded-full bg-slate-100"></div>
                        <div class="h-5 w-20 rounded bg-slate-100"></div>
                    </div>
                </div>
            </div>

            <div
                v-else-if="shops.length === 0"
                class="pop-in mt-5 rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center"
            >
                <div class="mx-auto flex size-14 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                    <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
                <h2 class="mt-5 text-lg font-semibold text-slate-900">No shops found</h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ search ? `Nothing matches "${search}".` : 'There are no shops yet.' }}
                </p>
                <button
                    v-if="search"
                    type="button"
                    class="mt-6 rounded-lg bg-orange-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-700"
                    @click="search = ''"
                >
                    Clear search
                </button>
            </div>

            <TransitionGroup
                v-else
                tag="div"
                name="card"
                appear
                class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-3"
                @before-enter="onBeforeEnter"
                @after-enter="onAfterEnter"
            >
                <ShopCard
                    v-for="(shop, i) in shops"
                    :key="shop.id ?? shop.slug"
                    :data-index="i"
                    :shop="shop"
                />
            </TransitionGroup>

            <Transition name="fade">
                <nav
                    v-if="!error && lastPage > 1"
                    class="mt-12 flex flex-wrap items-center justify-center gap-2"
                    aria-label="Pagination"
                >
                    <button
                        type="button"
                        :disabled="page === 1"
                        class="group flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 shadow-sm transition-all enabled:hover:border-orange-300 enabled:hover:text-orange-600 disabled:cursor-not-allowed disabled:opacity-40"
                        @click="goTo(page - 1)"
                    >
                        <svg class="size-4 transition-transform group-enabled:group-hover:-translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                        Prev
                    </button>

                    <template v-for="(p, idx) in pages" :key="`${p}-${idx}`">
                        <span v-if="p === '…'" class="px-1.5 text-slate-400">…</span>
                        <button
                            v-else
                            type="button"
                            :aria-current="p === page ? 'page' : undefined"
                            class="min-w-10 rounded-lg border px-3 py-2 text-sm font-medium transition-all duration-200"
                            :class="
                                p === page
                                    ? 'border-orange-600 bg-orange-600 text-white shadow-md shadow-orange-500/30'
                                    : 'border-slate-200 bg-white text-slate-700 shadow-sm hover:-translate-y-0.5 hover:border-orange-300 hover:text-orange-600'
                            "
                            @click="goTo(p)"
                        >
                            {{ p }}
                        </button>
                    </template>

                    <button
                        type="button"
                        :disabled="page === lastPage"
                        class="group flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 shadow-sm transition-all enabled:hover:border-orange-300 enabled:hover:text-orange-600 disabled:cursor-not-allowed disabled:opacity-40"
                        @click="goTo(page + 1)"
                    >
                        Next
                        <svg class="size-4 transition-transform group-enabled:group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </button>
                </nav>
            </Transition>
        </div>
    </div>
</template>

