<script setup>
import { storeService } from '@/services/store.service.js';
import { useCrudPage } from '@/composables/useCrudPage';
import PageHeading from '@/components/PageHeading.vue';
import SearchBar from '@/components/SearchBar.vue';
import Pagination from '@/components/Pagination.vue';
import EmptyState from '@/components/EmptyState.vue';
import BaseModal from '@/components/BaseModal.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import ShopForm from '@/components/ShopForm.vue';

const {
    items,
    meta,
    loading,
    query,
    setPage,
    modal,
    confirm,
    openForm,
    closeForm,
    saved,
    askRemove,
    cancelRemove,
    confirmRemove,
} = useCrudPage({ list: storeService.list, remove: storeService.remove, noun: 'Shop' });
</script>

<template>
    <div class="space-y-6">
        <PageHeading eyebrow="Module 01" title="Shops" back="/admin" back-label="Admin home">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-4 py-2 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99]"
                @click="openForm()"
            >
                <span>+</span> New shop
            </button>
        </PageHeading>

        <div class="max-w-sm">
            <SearchBar v-model="query.search" placeholder="Search shops..." />
        </div>

        <div v-if="loading" class="py-8 text-center font-mono text-xs text-slate-400">
            Loading…
        </div>

        <div v-if="items.length" class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full min-w-[44rem] text-left text-sm text-slate-700">
                <thead class="border-b border-slate-200 bg-slate-50 font-mono text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-4 py-3">Shop</th>
                    <th class="px-4 py-3">Owner</th>
                    <th class="px-4 py-3 text-right">Products</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                <tr v-for="shop in items" :key="shop.id" class="transition-colors hover:bg-slate-50/50">
                    <td class="px-4 py-3">
                        <div v-if="shop" class="inline-flex items-center gap-3 ">
                            <div class="relative h-9 w-9 shrink-0 overflow-hidden rounded-lg border border-slate-200/70 bg-white shadow-xs">
                                <img
                                    v-if="shop.logo"
                                    :src="shop.logo"
                                    :alt="shop.name"
                                    class="h-full w-full object-cover"
                                />
                                <div
                                    v-else
                                    class="flex h-full w-full items-center justify-center bg-gradient-to-br from-amber-500 via-orange-500 to-rose-500 font-bold text-white shadow-inner"
                                >
                                    <span class="text-xs uppercase font-sans">
                                        {{ shop.name?.charAt(0) || 'S' }}
                                    </span>
                                </div>
                            </div>

                            <div class="min-w-0 space-y-0.5">
                                <div class="flex items-center gap-1.5 leading-none">
                                    <span class="font-bold text-xs text-slate-900 truncate">{{ shop.name }}</span>
                                 </div>
                            </div>
                        </div>
                    </td>

                    <td class="px-4 py-3">
                        <template v-if="shop.owner">
                            <p class="font-medium text-slate-800">{{ shop.owner.name }}</p>
                            <p class="text-xs text-slate-400">{{ shop.owner.email }}</p>
                        </template>
                        <span v-else class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 font-mono text-[11px] font-medium text-slate-500">
                                Unassigned
                            </span>
                    </td>

                    <td class="px-4 py-3 text-right font-mono font-medium tabular-nums text-slate-900">
                        {{ shop.listings_count ?? 0 }}
                    </td>

                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <RouterLink :to="`/shops/${shop.slug}`" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:border-orange-500 hover:text-orange-600 active:scale-95"
                             >
                                View Store
                            </RouterLink>
                            <button
                                type="button"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:border-orange-500 hover:text-orange-600 active:scale-95"
                                @click="openForm(shop)"
                            >
                                Edit
                            </button>
                            <button
                                type="button"
                                class="rounded-lg border border-rose-200 bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 shadow-sm transition-all duration-200 hover:bg-rose-50 active:scale-95"
                                @click="askRemove(shop)"
                            >
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <EmptyState
            v-else-if="!loading"
            title="No shops yet"
            text="Create the first shop, then link an owner to it."
        >
            <button
                type="button"
                class="mt-3 inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-4 py-2 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99]"
                @click="openForm()"
            >
                New shop
            </button>
        </EmptyState>

        <Pagination :meta="meta" @change="setPage" />

        <BaseModal :open="modal.open" :title="modal.record ? 'Edit shop' : 'New shop'" @close="closeForm">
            <ShopForm :shop="modal.record" @saved="saved" @cancel="closeForm" />
        </BaseModal>

        <ConfirmDialog
            :open="confirm.open"
            title="Delete shop"
            :message="`Delete ${confirm.record?.name}? Its product listings are deleted with it. Catalog products stay.`"
            :busy="confirm.busy"
            @confirm="confirmRemove"
            @cancel="cancelRemove"
        />
    </div>
</template>
