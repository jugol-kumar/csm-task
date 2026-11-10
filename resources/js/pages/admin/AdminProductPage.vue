<script setup>
import { reactive } from 'vue';
import { productService } from '@/services/product.service';
import { useCrudPage } from '@/composables/useCrudPage';
import PageHeading from '@/components/PageHeading.vue';
import SearchBar from '@/components/SearchBar.vue';
import Pagination from '@/components/Pagination.vue';
import EmptyState from '@/components/EmptyState.vue';
import BaseModal from '@/components/BaseModal.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import ProductForm from '@/components/ProductForm.vue';
import {formatedPrice} from "@/utils/priceFormater.js";

const filters = reactive({ source: '' });

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
} = useCrudPage({ list: productService.list, remove: productService.remove, noun: 'Product', filters });

function saver(payload) {
    console.log('yes called up', payload)
    return modal.record ? productService.update(modal.record.id, payload) : productService.create(payload);
}
</script>

<template>
    <div class="space-y-6">
        <PageHeading eyebrow="Module 02" title="Products" back="/admin" back-label="Admin home">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-4 py-2 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99]"
                @click="openForm()"
            >
                <span>+</span> New product
            </button>
        </PageHeading>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:max-w-md flex-1">
                <SearchBar v-model="query.search" placeholder="Search by product name..." />
            </div>

            <div class="flex items-center gap-2.5">
                <div class="hidden items-center gap-1.5 font-mono text-xs font-medium text-slate-400 lg:flex">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                    <span>Filter:</span>
                </div>

                <div class="relative w-full sm:w-auto">
                    <select
                        v-model="filters.source"
                        class="w-full sm:w-auto appearance-none rounded-lg border border-slate-200 bg-white px-4 py-2.5 pr-10 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:border-slate-300 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                    >
                        <option value="">Added by anyone</option>
                        <option value="admin">Added by admin</option>
                        <option value="shop_owner">Added by shop owners</option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="loading" class="py-8 text-center font-mono text-xs text-slate-400">
            Loading…
        </div>

        <div v-if="items.length" class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full min-w-[48rem] text-left text-sm text-slate-700">
                <thead class="border-b border-slate-200 bg-slate-50 font-mono text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="w-20 px-4 py-3"></th>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3 text-right">Base price</th>
                    <th class="px-4 py-3">Added by</th>
                    <th class="px-4 py-3 text-right">In shops</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                <tr v-for="product in items" :key="product.id" class="transition-colors hover:bg-slate-50/50">
                    <td class="px-4 py-3">
                        <div class="h-12 w-12 overflow-hidden rounded-lg bg-slate-100 border border-slate-200">
                            <img
                                v-if="product.image"
                                :src="product.image"
                                :alt="product.name"
                                class="h-full w-full object-contain"
                            />
                            <div v-else class="flex h-full w-full items-center justify-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                            </div>
                        </div>
                    </td>

                    <td class="px-4 w-72 py-3">
                        <p class="font-semibold text-slate-900">{{ product.name }}</p>
                        <p class="line-clamp-1 max-w-xs text-xs text-slate-500">{{ product.description }}</p>
                    </td>

                    <td class="px-4 py-3 text-right font-mono font-medium tabular-nums text-slate-900">
                        {{ formatedPrice(product.price) }}
                    </td>

                    <td class="px-4 py-3">
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 font-mono text-[11px] font-semibold uppercase tracking-wide border"
                                :class="product.source === 'admin' ? 'bg-amber-50 border-amber-200 text-amber-600' : 'bg-slate-100 border-slate-200 text-slate-600'"
                            >
                                {{ product.source === 'admin' ? 'Admin' : 'Owner' }}
                            </span>
                        <p v-if="product.creator" class="mt-1 text-xs text-slate-400">{{ product.creator.name }}</p>
                    </td>

                    <td class="px-4 py-3 text-right font-mono tabular-nums">
                            <span :class="product.listings_count ? 'text-slate-900 font-semibold' : 'text-rose-500 font-semibold'">
                                {{ product.listings_count ?? 0 }}
                            </span>
                        <p v-if="!product.listings_count" class="font-sans text-[11px] font-medium text-rose-500">hidden</p>
                    </td>

                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button
                                type="button"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:border-orange-500 hover:text-orange-600 active:scale-95"
                                @click="openForm(product)"
                            >
                                Edit
                            </button>
                            <button
                                type="button"
                                class="rounded-lg border border-rose-200 bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 shadow-sm transition-all duration-200 hover:bg-rose-50 active:scale-95"
                                @click="askRemove(product)"
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
            title="No products found"
            text="Products added here join the shared catalog for every shop owner."
        >
            <button
                type="button"
                class="mt-3 inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-4 py-2 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99]"
                @click="openForm()"
            >
                New product
            </button>
        </EmptyState>

        <Pagination :meta="meta" @change="setPage" />

        <BaseModal :open="modal.open" :title="modal.record ? 'Edit product' : 'New product'" @close="closeForm">
            <ProductForm :product="modal.record" :saver="saver" @saved="saved" @cancel="closeForm" />
        </BaseModal>

        <ConfirmDialog
            :open="confirm.open"
            title="Delete product"
            :message="`Delete ${confirm.record?.name}? It also disappears from ${confirm.record?.listings_count ?? 0} shop(s).`"
            :busy="confirm.busy"
            @confirm="confirmRemove"
            @cancel="cancelRemove"
        />
    </div>
</template>
