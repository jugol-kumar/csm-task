<script setup>
import BaseModal from './BaseModal.vue';

defineProps({
    open: Boolean,
    title: { type: String, default: 'Please confirm' },
    message: { type: String, default: '' },
    confirmLabel: { type: String, default: 'Delete' },
    busy: Boolean,
});
const emit = defineEmits(['confirm', 'cancel']);
</script>

<template>
    <BaseModal :open="open" :title="title" @close="emit('cancel')">
        <!-- Warning Icon & Message Layout -->
        <div class="flex items-start gap-4">
            <!-- Warning Icon Badge -->
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-500 border border-rose-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>

            <div class="flex-1">
                <p class="text-sm leading-relaxed text-slate-600">
                    {{ message }}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-200 pt-4">
            <!-- Cancel Button -->
            <button
                type="button"
                class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 active:scale-[0.98] disabled:opacity-50"
                :disabled="busy"
                @click="emit('cancel')"
            >
                Cancel
            </button>

            <!-- Confirm / Danger Button -->
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg bg-rose-600 px-5 py-2.5 font-semibold text-white shadow-md transition-all duration-200 hover:bg-rose-700 active:scale-[0.99] disabled:opacity-70"
                :disabled="busy"
                @click="emit('confirm')"
            >
                <span v-if="busy" class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Working…
                </span>
                <span v-else>{{ confirmLabel }}</span>
            </button>
        </div>
    </BaseModal>
</template>
