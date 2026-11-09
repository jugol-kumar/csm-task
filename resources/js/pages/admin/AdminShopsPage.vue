<script setup>
import { shopService } from '@/services/shop.service';
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
} = useCrudPage({ list: shopService.list, remove: shopService.remove, noun: 'Shop' });
</script>

<template>
    <div class="space-y-6">
        <!-- Page Heading with Orange Gradient Action Button -->
        <PageHeading eyebrow="Module 01" title="Shops" back="/admin" back-label="Admin home">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-4 py-2 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99]"
                @click="openForm()"
            >
                <span>+</span> New shop
            </button>
        </PageHeading>

        <!-- Search Bar -->
        <div class="max-w-sm">
            <SearchBar v-model="query.search" placeholder="Search shops..." />
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="py-8 text-center font-mono text-xs text-slate-400">
            Loading…
        </div>

        <!-- Shops Table Container -->
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
                    <!-- Shop Name & Slug -->
                    <td class="px-4 py-3">
                        <p class="font-semibold text-slate-900">{{ shop.name }}</p>
                        <p class="font-mono text-xs text-slate-400">/{{ shop.slug }}</p>
                    </td>

                    <!-- Owner Details -->
                    <td class="px-4 py-3">
                        <template v-if="shop.owner">
                            <p class="font-medium text-slate-800">{{ shop.owner.name }}</p>
                            <p class="text-xs text-slate-400">{{ shop.owner.email }}</p>
                        </template>
                        <span v-else class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 font-mono text-[11px] font-medium text-slate-500">
                                Unassigned
                            </span>
                    </td>

                    <!-- Products Count -->
                    <td class="px-4 py-3 text-right font-mono font-medium tabular-nums text-slate-900">
                        {{ shop.listings_count ?? 0 }}
                    </td>

                    <!-- Actions -->
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
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

        <!-- Empty State -->
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

        <!-- Pagination -->
        <Pagination :meta="meta" @change="setPage" />

        <!-- Modal for Create/Edit -->
        <BaseModal :open="modal.open" :title="modal.record ? 'Edit shop' : 'New shop'" @close="closeForm">
            <ShopForm :shop="modal.record" @saved="saved" @cancel="closeForm" />
        </BaseModal>

        <!-- Confirm Dialog for Delete -->
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
