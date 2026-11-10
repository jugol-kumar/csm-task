<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from '@/composables/useToast';
import {useAuthStore} from "@/stores/auth.js";

const router = useRouter();
const toast = useToast();
const authStore = useAuthStore();
const form = reactive({
    email: '',
    password: '',
});

const loading = ref(false);

async function handleLogin() {
    loading.value = true;
    try {
        await authStore.adminLogin(form);
        toast.success('Welcome back, Admin!');
        router.push('/admin');
    } catch (err) {
        toast.error(err.response?.data?.message || 'Invalid credentials. Please try again.');
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <div class="relative min-h-screen w-full flex items-center justify-center bg-slate-50 px-4 py-12 sm:px-6 lg:px-8 overflow-hidden">
        <div class="absolute -top-40 -right-40 h-96 w-96 rounded-full bg-orange-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -left-40 h-96 w-96 rounded-full bg-rose-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative w-full max-w-md space-y-8">
            <div class="text-center space-y-3">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 via-orange-500 to-rose-500 text-white shadow-lg shadow-orange-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-7 w-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                    Admin Portal
                </h1>
                <p class="text-sm text-slate-500 font-mono">
                    Sign in to manage store operations and users
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-white p-8 shadow-xl shadow-slate-200/50 backdrop-blur-xl">
                <form class="space-y-6" @submit.prevent="handleLogin">
                    <div class="space-y-1.5">
                        <label class="block font-mono text-xs font-bold uppercase tracking-wider text-slate-600">
                            Admin Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </span>
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pr-4 pl-10 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition-all focus:border-orange-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                                placeholder="admin@storefront.com"
                            />
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </span>
                            <input
                                v-model="form.password"
                                type="password"
                                required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pr-4 pl-10 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition-all focus:border-orange-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                                placeholder="••••••••"
                            />
                        </div>
                    </div>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 py-3.5 px-4 text-sm font-bold text-white shadow-lg shadow-orange-500/25 transition-all hover:opacity-95 active:scale-[0.99] disabled:opacity-70"
                    >
                        <svg v-if="loading" class="h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>{{ loading ? 'Authenticating...' : 'Sign in to Dashboard' }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
