<script setup>
import { formatedPrice } from "@/utils/priceFormater.js";

defineProps({ items: { type: Array, required: true } });
const emit = defineEmits(['add', 'edit', 'remove', 'detach']);
</script>

<template>
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <article
            v-for="product in items"
            :key="product.id"
            class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-orange-300 hover:shadow-md"
        >
            <div>
                <div class="overflow-hidden rounded-xl bg-slate-50 border border-slate-100">
                    <img :src="product.image" :alt="product.name" class="transition-transform duration-300 group-hover:scale-105" />
                </div>

                <div class="mt-4 flex-1">
                    <h3 class="font-semibold text-slate-900 line-clamp-1 group-hover:text-orange-600 transition-colors">
                        {{ product.name }}
                    </h3>

                    <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-slate-500">
                        {{ product.description }}
                    </p>

                    <p class="mt-3 font-mono text-base font-bold text-slate-900 tabular-nums">
                        {{ formatedPrice(product.price) }}
                    </p>

                    <p class="mt-1 font-mono text-[11px] tracking-wider text-slate-400 uppercase">
                        {{ product.is_mine ? 'Added by you' : product.source === 'admin' ? 'Added by admin' : `Added by ${product.creator?.name ?? 'an owner'}` }}
                    </p>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3">
                <!-- If already listed/attached, show Detach Button -->
                <button
                    v-if="product.is_listed"
                    type="button"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600 shadow-sm transition-all duration-200 hover:border-rose-300 hover:bg-rose-50 hover:text-rose-600 active:scale-95"
                    @click="emit('detach', product)"
                    title="Remove from your shop"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 text-rose-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    Remove from shop
                </button>

                <button
                    v-else
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition-all duration-200 hover:opacity-95 active:scale-95"
                    @click="emit('add', product)"
                >
                    Add to shop
                </button>

                <!-- Owner Actions (Edit / Delete) -->
                <template v-if="product.is_mine">
                    <button
                        type="button"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:border-orange-500 hover:text-orange-600 active:scale-95"
                        @click="emit('edit', product)"
                    >
                        Edit
                    </button>

                    <button
                        type="button"
                        class="rounded-lg border border-rose-200 bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 shadow-sm transition-all duration-200 hover:bg-rose-50 active:scale-95"
                        @click="emit('remove', product)"
                    >
                        Delete
                    </button>
                </template>
            </div>
        </article>
    </div>
</template>
