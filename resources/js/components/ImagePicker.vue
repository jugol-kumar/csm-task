<script setup>
import {computed, onBeforeUnmount, ref, watch} from 'vue';

const props = defineProps({
    modelValue: { type: Object, default: null },
    currentUrl: { type: String, default: null },
    remove: Boolean,
    label: { type: String, default: 'Thumbnail' },
    hint: { type: String, default: 'JPG, PNG or WebP, up to 2 MB.' },
    error: { type: String, default: '' },
    name: { type: String, default: '' },
});
const emit = defineEmits(['update:modelValue', 'update:remove']);

const input = ref(null);
const preview = ref(null);

watch(
    () => props.modelValue,
    (file) => {
        if (preview.value) URL.revokeObjectURL(preview.value);
        preview.value = file ? URL.createObjectURL(file) : null;
    },
    { immediate: true }
);

onBeforeUnmount(() => {
    if (preview.value) URL.revokeObjectURL(preview.value);
});

const shown = computed(() => preview.value ?? (props.remove ? null : props.currentUrl));
const hasImage = computed(() => Boolean(shown.value));

function pick(event) {
    const file = event.target.files?.[0];

    if (file) {
        emit('update:modelValue', file);
        emit('update:remove', false);
    }

    event.target.value = '';
}

function clear() {
    emit('update:modelValue', null);

    if (props.currentUrl) {
        emit('update:remove', true);
    }
}
</script>

<template>
    <div>
        <span class="mb-2 block font-mono text-xs font-semibold uppercase tracking-wider text-slate-700">
        {{ label }}
    </span>

        <div class="flex items-start gap-4">
            <div class="relative h-28 w-28 shrink-0 overflow-hidden rounded-xl border border-slate-200 bg-slate-50 shadow-sm">
                <img
                    v-if="shown"
                    :src="shown"
                    :alt="name || 'Image preview'"
                    class="h-full w-full object-cover"
                />

                <div
                    v-else
                    class="flex h-full w-full items-center justify-center bg-gradient-to-br from-amber-50 to-orange-100 text-orange-400"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 opacity-70">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </div>
            </div>

            <div class="flex flex-1 flex-col justify-center space-y-2 pt-1">
                <div class="flex flex-wrap gap-2">
                    <button
                        type="button"
                        class="rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:border-orange-500 hover:text-orange-600 active:scale-95"
                        @click="input.click()"
                    >
                        {{ hasImage ? 'Replace' : 'Choose file' }}
                    </button>

                    <button
                        v-if="hasImage"
                        type="button"
                        class="rounded-lg border border-rose-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-rose-600 shadow-sm transition-all duration-200 hover:bg-rose-50 active:scale-95"
                        @click="clear"
                    >
                        Remove
                    </button>
                </div>

                <p v-if="!error" class="text-xs text-slate-400">
                    {{ hint || 'PNG, JPG, or WEBP.' }}
                </p>

                <p v-else class="text-xs font-medium text-rose-500">
                    {{ error }}
                </p>
            </div>
        </div>

        <input
            ref="input"
            type="file"
            accept="image/png,image/jpeg,image/webp"
            class="hidden"
            @change="pick"
        />
    </div>
</template>
