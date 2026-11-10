<script setup>
import { useToast } from '@/composables/useToast';
const { toasts, removeToast } = useToast();
</script>

<template>
    <div class="pointer-events-none fixed right-5 top-5 z-50 flex w-80 flex-col gap-3">
        <transition-group
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="translate-y-[-1rem] opacity-0 scale-95"
            enter-to-class="translate-y-0 opacity-100 scale-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="translate-y-0 opacity-100 scale-100"
            leave-to-class="translate-y-[-1rem] opacity-0 scale-95"
        >
            <div
                v-for="toast in toasts"
                :key="toast.id"
                class="pointer-events-auto flex items-start gap-3 rounded-2xl border p-4 shadow-lg backdrop-blur-md transition-all"
                :class="
                    toast.type === 'ok'
                        ? 'border-emerald-200/80 bg-white/95 text-slate-800 shadow-emerald-500/5'
                        : 'border-rose-200/80 bg-white/95 text-slate-800 shadow-rose-500/5'
                "
                role="status"
            >
                <div
                    class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-xl text-white shadow-sm"
                    :class="toast.type === 'ok' ? 'bg-emerald-500' : 'bg-rose-500'"
                >
                    <svg v-if="toast.type === 'ok'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-3.5 w-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 1.375 9-13.5" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-3.5 w-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="font-sans text-xs font-semibold capitalize tracking-wider" :class="toast.type === 'ok' ? 'text-emerald-600' : 'text-rose-600'">
                        {{ toast.type === 'ok' ? 'Success' : 'Error' }}
                    </p>
                    <p class="font-sans text-sm text-slate-700 mt-0.5 leading-snug">
                        {{ toast.message }}
                    </p>
                </div>

                <button
                    v-if="removeToast"
                    type="button"
                    class="text-slate-400 hover:text-slate-600 transition-colors"
                    @click="removeToast(toast.id)"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </transition-group>
    </div>
</template>
