<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Logo from "@/components/Logo.vue";
import {app_name} from "../utils/config.js";
const auth = useAuthStore();
const router = useRouter();

const desk = computed(() => {
    if (auth.isAdmin) return { to: '/admin', label: 'Admin' };
    if (auth.isOwner) return { to: '/owner', label: 'My shop' };
    return null;
});

async function logout() {
    await auth.logout();
    router.push('/');
}
</script>

<template>
    <div class="flex min-h-screen flex-col bg-white text-slate-700">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4">
                <Logo class="text-slate-900" />

                <nav class="flex items-center gap-6 font-mono text-xs font-medium tracking-wider uppercase">
                    <RouterLink to="/" class="transition-colors hover:bg-gradient-to-r hover:from-amber-500 hover:to-orange-600 hover:bg-clip-text hover:text-transparent">
                        Shops
                    </RouterLink>

                    <RouterLink v-if="desk" :to="desk.to" class="transition-colors hover:bg-gradient-to-r hover:from-amber-500 hover:to-orange-600 hover:bg-clip-text hover:text-transparent">
                        {{ desk.label }}
                    </RouterLink>

                    <template v-if="auth.isAuthenticated">
                        <span class="hidden text-slate-400 sm:inline">{{ auth.user.name }}</span>
                        <button
                            type="button"
                            class="tracking-wider uppercase transition-colors hover:bg-gradient-to-r hover:from-amber-500 hover:to-orange-600 hover:bg-clip-text hover:text-transparent"
                            @click="logout"
                        >
                            Logout
                        </button>
                    </template>

                    <template v-else>
                        <RouterLink to="/login" class="transition-colors hover:bg-gradient-to-r hover:from-amber-500 hover:to-orange-600 hover:bg-clip-text hover:text-transparent">
                            Login
                        </RouterLink>
                        <RouterLink
                            to="/register"
                            class="rounded-md bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 px-4 py-2 font-semibold text-white shadow-md transition-opacity hover:opacity-90"
                        >
                            Register
                        </RouterLink>
                    </template>
                </nav>
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8 bg-white text-slate-800">
            <RouterView/>
        </main>

        <footer class="border-t border-dashed border-slate-200 bg-white">
            <div class="mx-auto max-w-6xl px-4 py-5 font-mono text-xs text-slate-400">
                {{ app_name }} · one shared catalog, every shop sets its own price
            </div>
        </footer>
    </div>
</template>
