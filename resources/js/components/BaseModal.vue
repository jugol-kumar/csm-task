<script setup>
import { onBeforeUnmount, onMounted, watch } from 'vue';

const props = defineProps({
    open: Boolean,
    title: { type: String, default: '' },
    wide: Boolean,
});
const emit = defineEmits(['close']);

function onKey(event) {
    if (event.key === 'Escape' && props.open) emit('close');
}

onMounted(() => window.addEventListener('keydown', onKey));

onBeforeUnmount(() => {
    window.removeEventListener('keydown', onKey);
    document.body.style.overflow = '';
});

watch(
    () => props.open,
    (value) => {
        document.body.style.overflow = value ? 'hidden' : '';
    }
);
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="open"
                class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-900/50 p-4 backdrop-blur-sm sm:py-12"
                @mousedown.self="emit('close')"
            >
                <div
                    class="w-full transform overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl transition-all"
                    :class="wide ? 'max-w-3xl' : 'max-w-xl'"
                    role="dialog"
                    aria-modal="true"
                >
                    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                        <h2 class="font-mono text-xs font-bold uppercase tracking-wider text-slate-800">
                            {{ title }}
                        </h2>
                        <button
                            type="button"
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700"
                            aria-label="Close"
                            @click="emit('close')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <slot />
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
