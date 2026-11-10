<script setup>
import { reactive } from 'vue';
import { listingService } from '@/services/listing.service';
import { useFormSubmit } from '@/composables/useFormSubmit';
import { useToast } from '@/composables/useToast';
import FormField from '@/components/FormField.vue';
import ImagePicker from '@/components/ImagePicker.vue';
import {formatedPrice} from "../utils/priceFormater.js";

const props = defineProps({ product: { type: Object, required: true } });
const emit = defineEmits(['saved', 'cancel']);

const toast = useToast();
const { busy, run, err } = useFormSubmit();

const form = reactive({
    price: '',
    product_id: props.product.product_id,
    title: '',
    description: '',
    thumbnail: null,
    remove_thumbnail: false,
});

async function submit() {
    const res = await run(() => listingService.add({ ...form, product_id: props.product.product_id }));

    if (res) {
        toast.success(`${props.product.name} added to your shop`);
        emit('saved');
    }
}
</script>

<template>
    <form class="space-y-5" @submit.prevent="submit">
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm shadow-sm flex items-center justify-between gap-4">
            <div>
                <p class="font-semibold text-slate-900">{{ product.name }}</p>
                <p class="mt-1 text-xs leading-relaxed text-slate-500">
                    Catalog entry. Anything you leave empty below is taken from it.
                </p>
            </div>
            <div class="shrink-0 text-right">
            <span class="inline-flex items-center rounded-lg bg-white px-3 py-1.5 font-mono text-sm font-bold text-slate-900 border border-slate-200 shadow-sm">
                {{ formatedPrice(product.price) }}
            </span>
            </div>
        </div>

        <FormField label="Your price" :error="err('price')">
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 font-mono text-sm">$</div>
                <input
                    v-model="form.price"
                    type="number"
                    min="0"
                    step="0.01"
                    class="w-full rounded-lg border border-slate-200 bg-white py-2.5 pr-4 pl-8 font-mono text-sm text-slate-800 shadow-sm transition-colors hover:border-slate-300 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                    required
                />
            </div>
        </FormField>

        <FormField label="Shop title" :error="err('title')" hint="Optional. Overrides the catalog name in your shop only.">
            <input
                v-model="form.title"
                type="text"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm transition-colors hover:border-slate-300 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                :placeholder="product.name"
            />
        </FormField>

        <FormField label="Shop description" :error="err('description')" hint="Optional.">
            <textarea
                v-model="form.description"
                rows="3"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm transition-colors hover:border-slate-300 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                :placeholder="product.description ?? ''"
            ></textarea>
        </FormField>

        <ImagePicker
            v-model="form.thumbnail"
            v-model:remove="form.remove_thumbnail"
            label="Shop thumbnail"
            hint="Optional. Without one, the catalog thumbnail is shown."
            :name="product.name"
            :error="err('thumbnail')"
        />

        <div v-if="err('product_id')" class="text-xs font-medium text-rose-500">
            {{ err('product_id') }}
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-4">
            <button
                type="button"
                class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 active:scale-95"
                @click="emit('cancel')"
            >
                Cancel
            </button>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99] disabled:opacity-70"
                :disabled="busy"
            >
                <span v-if="busy" class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Adding…
                </span>
                <span v-else>Add to my shop</span>
            </button>
        </div>
    </form>
</template>
