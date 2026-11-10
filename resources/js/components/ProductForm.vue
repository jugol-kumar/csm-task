<script setup>
import { reactive } from 'vue';
import { useFormSubmit } from '@/composables/useFormSubmit';
import { useToast } from '@/composables/useToast';
import FormField from '@/components/FormField.vue';
import ImagePicker from '@/components/ImagePicker.vue';

const props = defineProps({
    product: { type: Object, default: null },
    saver: { type: Function, required: true },
    submitLabel: { type: String, default: '' },
});
const emit = defineEmits(['saved', 'cancel']);

const toast = useToast();
const { busy, run, err } = useFormSubmit();

const form = reactive({
    name: props.product?.name ?? '',
    description: props.product?.description ?? '',
    base_price: props.product?.price ?? '',
    thumbnail: null,
    remove_thumbnail: false,
});

async function submit() {
    const res = await run(() => props.saver({ ...form }));

    if (res) {
        toast.success(props.product ? 'Product updated' : 'Product created');
        emit('saved', res);
    }
}
</script>

<template>
    <form class="space-y-4" @submit.prevent="submit">
        <FormField label="Product name" :error="err('name')">
            <input
                v-model="form.name"
                type="text"
                placeholder="Enter product name"
                class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                required
            />
        </FormField>

        <FormField label="Base price" :error="err('base_price')" hint="Shops start from this price and can change it for themselves.">
            <input
                v-model="form.base_price"
                type="number"
                min="0"
                step="0.01"
                placeholder="0.00"
                class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 font-mono text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                required
            />
        </FormField>

        <FormField label="Description" :error="err('description')">
            <textarea
                v-model="form.description"
                rows="4"
                placeholder="Brief description about the product"
                class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-400 shadow-sm transition-colors focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
            ></textarea>
        </FormField>

        <div class="pt-1">
            <ImagePicker
                v-model="form.thumbnail"
                v-model:remove="form.remove_thumbnail"
                :current-url="product?.image"
                :name="form.name"
                :error="err('thumbnail')"
            />
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-4">
            <button
                type="button"
                class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 active:scale-[0.98]"
                @click="emit('cancel')"
            >
                Cancel
            </button>
            <button
                type="submit"
                class="rounded-lg bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-5 py-2.5 font-semibold text-white shadow-md transition-all duration-200 hover:opacity-95 active:scale-[0.99] disabled:opacity-70"
                :disabled="busy"
            >
                <span v-if="busy" class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving…
                </span>
                <span v-else>{{ submitLabel || (product ? 'Save changes' : 'Create product') }}</span>
            </button>
        </div>
    </form>
</template>
